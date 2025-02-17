<?php

namespace App\Http\Controllers;

use App\Company;
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
        return view('fetch_tiles_index',compact('api_details'));
    }

    /**
     * @throws Exception
     */
    public function fetchData(Request $request): JsonResponse
    {
        $getToken = $this->loginAPI();
        set_time_limit(0);
        ini_set('memory_limit', '1024M'); // Adjust the limit as needed

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

        $queryParams = http_build_query([
            'limit' => 2,
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
        Cache::put('tile_processing_progress', [
            'total' => $totalCount,
            'processed' => 0,
            'sku' => null,
            'surface' => null,
            'status' => 'Starting...',
        ], now()->addMinutes(10));

        foreach ($records as $index => $aTile) {
            $product = $aTile['attributes'];
            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

            // Skip specific SKUs
            if (in_array($product['sku'], ['12345678', '1223324324'])) {
                \Log::info("Skipping SKU: {$product['sku']} due to exclusion.");
                continue;
            }

            // Skip records based on a deletion flag
            if (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING'])) {
                \Log::info("Skipping SKU: {$product['sku']} due to deletion flag: {$product['deletion']}");
                continue;
            }

            // Check if Image Already Exists in DB
            $existingTile = \DB::table('tiles')->where('sku', $product['sku'])->first();

            // Store the image filename to reuse for multiple surfaces
            $imageURL = $product['image'] ?? $product['image_variation_1'];
            if ($existingTile && $existingTile->real_file === $imageURL) {
                $imageFileName = $existingTile->file; // Reuse existing image
            } else {
                $imageFileName = $this->fetchAndSaveImage($imageURL);
                if ($imageFileName === null) {
                    continue; // Skip this record safely if image fails
                }
            }

            // Determine the surfaces (Wall, Floor, Counter)
            $surfaces = $this->determineSurfaceValues($product);

            foreach ($surfaces as $surface) {
                $product['surface'] = trim($surface);
                $data = $this->prepareTileData($product, $creation_time, $imageFileName);

                try {
                    $existing = \DB::table('tiles')->where('sku', $product['sku'])->where('surface', $surface)->first();

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

                    // ✅ Store progress update **AFTER EACH RECORD**
                    $progressPercentage = min(($processedCount / $totalCount) * 100, 100);
                    Cache::put('tile_processing_progress', [
                        'total' => $totalCount,
                        'processed' => $processedCount,
                        'sku' => $product['sku'],
                        'surface' => $surface,
                        'status' => "{$processedCount} of {$totalCount} records processed (SKU: {$product['sku']}, Surface: {$surface})",
                        'percentage' => $progressPercentage,
                    ], now()->addMinutes(10));

                } catch (\Exception $e) {
                    $skippedRecords[] = [
                        'sku' => $product['sku'] ?? 'Unknown',
                        'surface' => $surface,
                        'error' => $e->getMessage(),
                    ];
                    \Log::error("Error inserting SKU: {$product['sku']} - " . $e->getMessage());
                }
            }
        }

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
