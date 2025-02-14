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
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage; // Import Intervention Image

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
        $dbStats = $this->updateOrInsertMultiple($data, $endDate, $totalRecords);

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
    public function updateOrInsertMultiple($records, $endDate, $totalCount): array
    {
        $insertedCount = 0; // Track new insertions
        $updatedCount = 0; // Track updates
        $unchangedCount = 0; // Track unchanged records
        $processedCount = 0;  // Variable to track the total number of processed records

        // Define the batch size
        $batchSize = 500; // Adjust based on your system's capacity
        $chunks = array_chunk($records, $batchSize);

        \Log::info('Starting batch processing. Total records: ' . count($records) . ', Batch size: ' . $batchSize);

        foreach ($chunks as $index => $batch) {
            \Log::info('Processing batch ' . ($index + 1) . ' of ' . count($chunks));

            foreach ($batch as $aTile) {
                $product = $aTile['attributes'];
                $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

                // Skip specific SKUs
                if ($product['sku'] == '12345678') {
                    \Log::info('Skipping SKU: ' . $product['sku'] . ' due to exclusion.');
                    continue;
                }

                // Skip records based on the deletion flag
                if (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING'])) {
                    \Log::info('Skipping SKU: ' . $product['sku'] . ' due to deletion flag: ' . $product['deletion']);
                    continue;
                }

                // Store the image filename to reuse for multiple surfaces
                $imageURL = $product['image'] ?? $product['image_variation_1'];
                $imageFileName = $this->fetchAndSaveImage($imageURL);

                // Determine the surface value BEFORE passing to prepareTileData()
                $surfaces = $this->determineSurfaceValues($product);
                // Handle multiple surfaces
                $applications = explode(' & ', $product['application']);
                foreach ($applications as $surface) {
                    $product['surface'] = trim($surface);
                    $data = $this->prepareTileData($product, $creation_time ,$imageFileName);

                    \Log::info('Processing SKU: ' . $product['sku'] . ' for Surface: ' . $surface);

                    $existing = \DB::table('tiles')->where('sku', $product['sku'])->where('surface', $surface)->first();

                    if ($existing) {
                        // Check for differences
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
                            \Log::info('Updating SKU: ' . $product['sku'] . ' for Surface: ' . $surface);
                        } else {
                            $unchangedCount++;
                            \Log::info('No changes for SKU: ' . $product['sku'] . ' for Surface: ' . $surface);
                        }
                    } else {
                        \DB::table('tiles')->insert($data);
                        $insertedCount++;
                        \Log::info('Inserting new SKU: ' . $product['sku'] . ' for Surface: ' . $surface);
                    }

                    $processedCount++;
                }
            }

            // Free up memory after processing each batch
            \Log::info('Completed batch ' . ($index + 1) . '. Processed so far: ' . $processedCount);
            unset($batch);
            gc_collect_cycles(); // Force garbage collection
        }

        // Update the last fetched date
        \DB::table('companies')->update([
            'last_fetch_date_from_api' => $endDate,
            'fetch_products_count' => $totalCount,
            'updated_at' => now(),
        ]);
        \Log::info('Updated last fetch date in companies table.');

        \Log::info('Process complete. Total records processed: ' . $processedCount);
        \Log::info('Summary: Inserted = ' . $insertedCount . ', Updated = ' . $updatedCount . ', Unchanged = ' . $unchangedCount);

        return [
            'insertedCount' => $insertedCount,
            'updatedCount' => $updatedCount,
            'count' => $processedCount,
            'unchangedCount' => $unchangedCount,
        ];
    }
}
