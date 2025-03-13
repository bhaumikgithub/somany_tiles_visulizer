<?php

namespace App\Jobs;

use App\Traits\ApiHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsertTilesFromAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiHelper;

    protected $startDate;
    protected $endDate;


    /**
     * Create a new job instance.
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle()
    {
        Log::info("Inserting tiles from API between {$this->startDate} and {$this->endDate}");

        ini_set('max_execution_time', 0);

        // Increase memory limit if needed
        ini_set('memory_limit', '2024M');

        $getToken = $this->loginAPI();

        // Get tiles data
        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";
        $queryParams = http_build_query([
            's' => $this->startDate,
            'e' => $this->endDate,
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

        // Pass count of data to function
        $this->updateOrInsertMultiple($data, $this->endDate, $totalRecords);

        // Log Success
        Log::info("Tiles data inserted successfully from API. Total Records: {$totalRecords}");
    }

    /**
     * @throws Exception
     */
    protected function updateOrInsertMultiple($records, $endDate, $totalCount): array
    {
        $count = 0;
        $insertedCount = 0;
        $updatedCount = 0;
        $unchangedCount = 0;

        $existingSkus = DB::table('tiles')->pluck('sku')->toArray();

        foreach ($records as $aTile) {
            $product = $aTile['attributes'];
            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

            if (in_array($product['sku'], $existingSkus)) {
                Log::info("Skipping SKU: {$product['sku']} - Already exists in DB.");
                continue;
            }

            if (isset($product['sku']) && in_array($product['sku'], ['12345678', '1223324324', '1234'])) {
                Log::info("Key: {$product['sku']}");
                continue;
            }

            if (isset($product['deletion']) && !in_array($product['deletion'], ["RUNNING", "SLOW MOVING"])) {
                continue;
            }

            $surfaces = $this->determineSurfaceValues($product);

            // Extract image variations, ignoring TIFF & PSD files
            $imageVariations = [];

            if (!empty($product['image']) && !$this->isInvalidFormat($product['image'])) {
                $imageVariations['real_file'] = $product['image'];
            }
            foreach (['image_variation_1', 'image_variation_2', 'image_variation_3','image_variation_4'] as $key) {
                if (!empty($product[$key]) && !$this->isInvalidFormat($product[$key])) {
                    $imageVariations[$key] = $product[$key];
                }
            }

            // Skip record if no valid images exist
            if (empty(array_filter($imageVariations))) {
                Log::info("Skipping SKU: {$product['sku']} - No valid images found.");
                continue;
            }

            $imageCache = [];
            $baseName = trim($product['product_name']);
            $rotoPrintSetName = str_replace(" FP", "", $baseName);
            $variationIndex = 1;

            foreach ($imageVariations as $column => $imageURL) {
                foreach ($surfaces as $surface) {
                    $product['surface'] = trim($surface);

                    if (!isset($imageCache[$imageURL])) {
                        $imageFileName = $this->fetchAndSaveImage($imageURL);
                        if ($imageFileName === null) continue;
                        $imageCache[$imageURL] = $imageFileName;
                    } else {
                        $imageFileName = $imageCache[$imageURL];
                    }

                    if ($imageFileName === null) continue;

                    // Set product name variation
                    $variationName = ($variationIndex === 1) ? $baseName : "{$baseName} " . str_pad($variationIndex, 2, '0', STR_PAD_LEFT);

                    // Clear all image fields
                    $product['real_file'] = $product['image_variation_1'] = $product['image_variation_2'] = $product['image_variation_3'] = null;

                    // Assign the image to the correct column
                    $product[$column] = $imageURL;

                    // Assign variation name
                    $product['product_name'] = $variationName;
                    $product['rotoPrintSetName'] = $rotoPrintSetName;
                    // Prepare data for DB
                    $data = $this->prepareTileData($product, $creation_time, $imageFileName);

//                    $existing = DB::table('tiles')->where([
//                        ['sku', $product['sku']],
//                        ['surface', $surface]])->first();
//
//                    if ($existing) {
//                        $isDifferent = false;
//                        foreach ($data as $key => $value) {
//                            if ($existing->$key != $value) {
//                                $isDifferent = true;
//                                break;
//                            }
//                        }
//
//                        if ($isDifferent) {
//                            $data['updated_at'] = now();
//                            DB::table('tiles')->where([
//                                ['sku', $product['sku']],
//                                ['surface', $surface],
//                                ['file', $imageFileName]
//                            ])->update($data);
//                            $updatedCount++;
//                            Log::info("Updated SKU: {$product['sku']} - Surface: {$surface} - Name: {$variationName}");
//                        } else {
//                            $unchangedCount++;
//                        }
//                    } else {
//
//                    }
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    DB::table('tiles')->insert($data);
                    $insertedCount++;
                    Log::info("Inserted SKU: {$product['sku']} - Surface: {$surface} - Name: {$variationName}");
                    $count++;
                }
                $variationIndex++;
            }
        }

        DB::table('companies')->update([
            'last_fetch_date_from_api' => $endDate,
            'fetch_products_count' => $totalCount,
            'updated_at' => now()
        ]);

        return [
            'insertedCount' => $insertedCount,
            'updatedCount' => $updatedCount,
            'count' => $count,
            'unchangedCount' => $unchangedCount
        ];
    }

    /**
     * Check if the image URL has an invalid format (TIFF/PSD).
     */
    private function isInvalidFormat($url): bool
    {
        $invalidFormats = ['tiff', 'tif', 'psd'];
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $invalidFormats);
    }
}
