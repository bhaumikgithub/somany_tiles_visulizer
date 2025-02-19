<?php

namespace App\Http\Controllers;

use App\Company;
use App\Tile;
use App\Traits\ApiHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FetchTilesController extends Controller
{
    use ApiHelper;

    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $api_details = Company::select('last_fetch_date_from_api','fetch_products_count')->first();
        // Get last successfully inserted record's date
        $lastInsertedDate = Tile::max('record_creation_time');
        $lastFetchDateFromRecord = Carbon::parse($lastInsertedDate)->format('Y-m-d');
        return view('fetch_tiles_index',compact('api_details','lastFetchDateFromRecord'));
    }

    /**
     * @throws Exception
     */
    public function fetchData(Request $request): JsonResponse
    {
        $getToken = $this->loginAPI();
        set_time_limit(0);
        ini_set('memory_limit', '2048M'); // Adjust the limit as needed

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

        $queryParams = http_build_query([
            'limit' => 100,
            's' => $startDate,
            'e' => $endDate,
        ]);

        $headers = [
            'JWTAuthorization: Bearer ' . $getToken,
        ];
        // Use the trait function for GET request
        $data = $this->makeGetRequest($apiUrl, $queryParams, $headers);

        if (isset($data['error'])) {
            return response()->json([
                'error' => 'Unable to fetch total records: ' . $data['error'],
            ], 500);
        }

        if (empty($data)) {
            exit(); // Stop if there are no more records
        }


        $totalRecords = count($data);
        // Insert or update database
        $this->updateOrInsertMultiple($data, $endDate, $totalRecords);

        return response()->json([
            'success' => true,
            'message' => 'Data processed successfully.',
            'total_records' => $totalRecords,
            'new_date' => $endDate
        ]);
    }
    /**
     * @throws Exception
     */
    public function updateOrInsertMultiple($records, $endDate, $totalCount): JsonResponse
    {
        $insertedCount = 0;
        $updatedCount = 0;
        $unchangedCount = 0;
        $processedCount = 0;
        $skippedRecords = [];

        \Log::info('Starting record-by-record processing. Total records: ' . $totalCount);

        // Initialize Progress Cache
        Cache::forever('tile_processing_progress', [
            'total' => $totalCount,
            'sku' => null,
            'surface' => null,
            'processed' => 0,
            'status' => "Waiting...",
            'skipped_records' => [],
        ]);

        foreach ($records as $index => $aTile) {
            $product = $aTile['attributes'];
            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

            // Skip specific SKUs
            if (in_array($product['sku'], ['12345678', '1223324324'])) {
                \Log::info("Skipping SKU: {$product['sku']} due to exclusion.");
                $skippedRecords[] = [
                    'name' => $product['product_name'] ?? 'Unknown',
                    'sku' => $product['sku'],
                    'date' => now()->format('Y-m-d H:i:s'),
                    'reason' => 'Excluded SKU'
                ];
                continue;
            }

            // Skip records based on a deletion flag
            if (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING'])) {
                \Log::info("Skipping SKU: {$product['sku']} due to deletion flag: {$product['deletion']}");
                $skippedRecords[] = [
                    'name' => $product['product_name'] ?? 'Unknown',
                    'sku' => $product['sku'],
                    'date' => now()->format('Y-m-d H:i:s'),
                    'reason' => "Deletion flag: {$product['deletion']}"
                ];
                continue;
            }

            // Check if the Image Already Exists in DB
            $existingTile = \DB::table('tiles')->where('sku', $product['sku'])->first();

            // Store the image filename to reuse for multiple surfaces
            $imageURL = $product['image'] ?? $product['image_variation_1'];

            if ($imageURL) {
                $extension = strtolower(pathinfo(strtok($imageURL, '?'), PATHINFO_EXTENSION));
                // Ignore TIFF and PSD files
                if (in_array($extension, ['tiff', 'tif', 'psd'])) {
                    $imageFileName = null; // Store null in the database for unsupported formats

                    // Store this record in a skipped records list
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $product['sku'],
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => "Unsupported image format: {$extension}"
                    ];

                    // Skip the entire record, ensuring it is NOT inserted or updated in DB
                    continue;
                }

                // If the record already exists with the same image, do nothing
                if ($existingTile && $existingTile->real_file === $imageURL) {
                    $imageFileName = $existingTile->file; // Reuse existing image
                } else {
                    // Fetch and save image if not a PSD/TIFF
                    $imageFileName = $this->fetchAndSaveImage($imageURL);

                    // If image download fails, skip the record
                    if ($imageFileName === null) {
                        $skippedRecords[] = [
                            'name' => $product['product_name'] ?? 'Unknown',
                            'sku' => $product['sku'],
                            'date' => now()->format('Y-m-d H:i:s'),
                            'reason' => "Failed to fetch image"
                        ];
                        continue; // Skip the record safely
                    }
                }
            }

            // Determine the surfaces (Wall, Floor, Counter)
            $surfaces = $this->determineSurfaceValues($product);

            foreach ($surfaces as $surface) {
                $product['surface'] = trim($surface);
                $data = $this->prepareTileData($product, $creation_time, $imageFileName);

                // Skip record if the `skip` flag is set
                if (isset($data['skip']) && $data['skip'] === true) {
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $product['sku'] ?? 'Unknown',
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => $data['reason'] // Dynamic reason based on missing fields
                    ];
                    continue; // Skip processing this record
                }

                try {
                    $existing = \DB::table('tiles')->where('sku', $product['sku'])->where('surface', $surface)->first();
                    $action = $existing ? 'Updated' : 'Inserted';
                    if ($existing) {
                        // Check for changes
                        $isDifferent = false;
                        foreach ($data as $key => $value) {
                            if ($existing->$key != $value) {
                                $isDifferent = true;
                                break;
                            }
                        }

                        if ($isDifferent) {
                            \DB::table('tiles')->where('sku', $product['sku'])->where('surface', $surface)->update($data);
                            $updatedCount++;
                            \Log::info("Updated SKU: {$product['sku']} for Surface: $surface");
                        } else {
                            $unchangedCount++;
                        }
                    } else {
                        \DB::table('tiles')->insert($data);
                        $insertedCount++;
                        \Log::info("Inserted new SKU: {$product['sku']} for Surface: $surface");
                    }

                    $processedCount++;

                    // Store progress update **AFTER EACH RECORD**
                    $progressPercentage = min(($processedCount / $totalCount) * 100, 100);
                    Cache::put('tile_processing_progress', [
                        'total' => $totalCount,
                        'processed' => $processedCount,
                        'percentage' => $progressPercentage,
                        'status' => "$processedCount / $totalCount records processed",
                        'last_record' => [
                            'name' => $product['product_name'] ?? 'Unknown',
                            'sku' => $product['sku'],
                            'surface' => $surface,
                            'action' => $action,
                        ],
                        'skipped_records' => $skippedRecords, // Store skipped/error records
                    ], now()->addMinutes(10));
                } catch (\Exception $e) {
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $product['sku'],
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => $e->getMessage(),
                    ];
                    \Log::error("Error inserting SKU: {$product['sku']} - " . $e->getMessage());
                }
            }
        }

        //update companies table
        // Update the last fetched date
//        \DB::table('companies')->update([
//            'last_fetch_date_from_api' => $endDate,
//            'fetch_products_count' => $totalCount,
//            'updated_at' => now(),
//        ]);
//        \Log::info('Updated last fetch date in companies table.');

        return response()->json([
            'success' => true,
            'message' => 'Data processed successfully.',
            'total_records' => $totalCount,
            'insertedCount' => $insertedCount,
            'updatedCount' => $updatedCount,
            'unchangedCount' => $unchangedCount,
            'skippedRecords' => $skippedRecords,
        ]);
    }


}
