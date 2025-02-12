<?php

namespace App\Http\Controllers;

use App\Company;
use App\Traits\ApiHelper;
use Carbon\Carbon;
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

    public function fetchData(Request $request): JsonResponse
    {
        $getToken = $this->loginAPI();
        set_time_limit(0);
        ini_set('memory_limit', '1024M'); // Adjust the limit as needed

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";
        $recordsProcessed = 0;
        $insertedCount = 0;
        $updatedCount = 0;
        $unchangedCount = 0;

        $page = 1;
        $perPage = 500; // Fetch 500 records per API call

        do {
            $queryParams = http_build_query([
                's' => $startDate,
                'e' => $endDate,
                'page' => $page,
                'limit' => $perPage,
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
                break; // Stop if there are no more records
            }


            $totalRecords = count($data);
            $recordsProcessed += $totalRecords;

            // Insert or update database
            $dbStats = $this->updateOrInsertMultiple($data, $endDate, $totalRecords);
            $insertedCount += $dbStats['count'];
            $updatedCount += $dbStats['updatedCount'];
            $unchangedCount += $dbStats['unchangedCount'];

            $page++; // Fetch the next page
        } while ($totalRecords >= $perPage); // Keep fetching until there are no more records

        $updatedMessage = Carbon::parse($endDate)->format('d M Y') . " (" . $recordsProcessed . ' records fetched)';

        return response()->json([
            'success' => true,
            'message' => 'Data processed successfully.',
            'total_records' => $recordsProcessed,
            'new_date' => $endDate,
            'updated_message' => $updatedMessage,
            'insertedCount' => $insertedCount,
            'updatedCount' => $updatedCount,
            'unchangedCount' => $unchangedCount,
        ]);
    }

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

                // Handle multiple surfaces
                $applications = explode(' & ', $product['application']);
                foreach ($applications as $surface) {
                    $product['surface'] = trim($surface);
                    $data = $this->prepareTileData($product, $creation_time);

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

    protected function prepareTileData(array $product,$creation_time): array
    {
        $surface = strtolower($product['surface']);
        $imageURL = ($product['image'] ) ?? $product['image_variation_1'];
        return [
            'name' => $product['product_name'] ?? null,
            'shape' => $product['shape'] ?? 'square',
            'width' => intval($product['size_wt']) ?? 0,
            'height' => intval($product['size_ht']) ?? 0,
            'size' => $product['size'] ?? null,
            'surface' => $surface ?? null,
            'finish' => $product['design_finish'] ?? null,
            'file' => $this->fetchAndSaveImage($imageURL),
            'image_variation_1' => $product['image_variation_1'] ?? null,
            'image_variation_2' => $product['image_variation_2'] ?? null,
            'grout' => ( $surface === "wall" || $surface === "floor" ) ? 1 : null,
            'url' => $product['url'] ?? null,
            'price' => $product['price'] ?? null,
            'expProps' => json_encode([
                'thickness' => $product['thickness'] ?? null,
                'product code' => $product['design_finish'] ?? null,
                'colour' => $product['color'] ?? null,
                'category' => $product['brand_name'] ?? null,
            ]), // Combined JSON field
            'rotoPrintSetName' => str_replace(" FP", "", $product['product_name']) ?? null,
            'access_level' => $product['access_level'] ?? null,
            'sku' => $product['sku'] ?? null,
            'application_room_area' => $product['application_room_area'] ?? null,
            'brand' => $product['brand_name'] ?? null,
            'sub_brand_1' => $product['sub_brand_1'] ?? null,
            'innovation' => $product['innovation'] ?? null,
            'sub_brand_2' => $product['sub_brand_2'] ?? null,
            'color' => $product['color'] ?? null,
            'poc' => $product['poc'] ?? null,
            'thickness' => $product['thickness'] ?? null,
            'tiles_per_carton' => $product['tiles_per_carton'] ?? null,
            'avg_wt_per_carton' => $product['avg_wt_per_carton'] ?? null,
            'coverage_sq_ft' => $product['coverage_sq_ft'] ?? null,
            'coverage_sq_mt' => $product['coverage_sq_mt'] ?? null,
            'pattern' => $product['pattern'] ?? null,
            'plant' => $product['plant'] ?? null,
            'service_geography' => $product['service_geography'] ?? null,
            'unit_of_production' => $product['unit_of_production'] ?? null,
            'yes_no' => $product['yes_no'] ?? null,
            'record_creation_time' => $creation_time,
            'deletion' => $product['deletion'] ?? null,
            'from_api' => '1',
            'updated_at' => now(),
        ];
    }

    protected function fetchAndSaveImage($imageURL): JsonResponse|string
    {
        // Get tiles data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->getSSLVerfier()); // Ensure SSL verification is enabled

        $imageContent = curl_exec($ch);
        
        // Get the content type of the image (optional: you can validate the type)
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        curl_close($ch);

        if (!$imageContent || !str_starts_with($contentType, 'image/')) {
            return response()->json([
                'error' => 'Invalid image content',
                'message' => 'Fetched content is not a valid image.',
            ], 406);
        }
        
        if (stristr($contentType, 'tiff')) {
            $tempTiffPath = storage_path('temp_image.tiff');
            file_put_contents($tempTiffPath, $imageContent);

            // Convert TIFF to JPG using ImageMagick
            $tempJpgPath = storage_path('temp_image.jpg');
            exec("convert $tempTiffPath $tempJpgPath");

            if (file_exists($tempJpgPath)) {
                $imageContent = file_get_contents($tempJpgPath);
                unlink($tempTiffPath);
                unlink($tempJpgPath);
            } else {
                return response()->json([
                    'error' => 'Failed to convert TIFF to JPG.',
                ], 406);
            }
        }


        $image_name = uniqid() . '.jpg';// Unique ID to avoid overwrite

        // Generate a unique filename for the image
        $fileName = 'tiles/' . $image_name;// Unique ID to avoid overwrite
        $iconFilePath = 'tiles/icons/' . $image_name;
        
         // Use Intervention/Image to handle the image content
        $image = InterventionImage::make($imageContent)
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        Storage::disk('public')->put($iconFilePath, $image->encode());
        Storage::disk('public')->put($fileName, $imageContent);

        return $fileName;
    }
}
