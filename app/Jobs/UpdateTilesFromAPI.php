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

class UpdateTilesFromAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels , ApiHelper;

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
        Log::info("Updating tiles from API between {$this->startDate} and {$this->endDate}");

        ini_set('max_execution_time', 0);

        // Increase memory limit if needed
        ini_set('memory_limit', '2024M');

        $getToken = $this->loginAPI();

        // Get tiles data
        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";
        $queryParams = http_build_query([
            'limit' => 1,
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
        $updatedRecords = $this->updateMultiple($data, $this->endDate, $totalRecords);

        // Log Success
        Log::info("Total Records: {$totalRecords}");
        Log::info("Tiles data updated successfully from API. Updated Records: {$updatedRecords['updatedCount']}");
        Log::info("Unchanged Records: {$updatedRecords['unchangedCount']}");
    }

    protected function updateMultiple($records, $endDate, $totalCount): array
    {
        $updatedCount = 0;      // Track the number of updated records
        $unchangedCount = 0;    // Track the number of unchanged records
        $imageCache = [];       // Cache to store image URLs and filenames
    
        foreach ($records as $record) {
            $sku = $record['code'];
            $product = $record['attributes'];  
    
            // Skip specific SKUs
            if (isset($product['sku']) && in_array($product['sku'], ['12345678', '1223324324', '1234'])) {
                Log::info("Skipping SKU: {$product['sku']}");
                continue;
            }
    
            // Skip if product is marked for deletion
            if (isset($product['deletion']) && !in_array($product['deletion'], ["RUNNING", "SLOW MOVING"])) {
                continue;
            }
    
            // Retrieve existing tile record by SKU from the database
            $tile = DB::table('tiles')->where('sku', $sku)->first();
    
            if ($tile) {
                $hasChanges = false; // Flag to track changes
    
                // Compare each non-image attribute from API and update if changed
                foreach ($product as $attribute => $value) {
                    // Skip image-related fields
                    if (in_array($attribute, ['image', 'image_variation_1', 'image_variation_2', 'image_variation_3', 'image_variation_4'])) {
                        continue;
                    }
    
                    // If the attribute has changed, update it
                    if (isset($tile->$attribute) && $tile->$attribute !== $value) {
                        // Update the attribute in the database
                        DB::table('tiles')->where('sku', $sku)->update([
                            $attribute => $value,
                            'updated_at' => now(),
                        ]);
                        Log::info("Updated SKU: {$sku} - Attribute: {$attribute} changed to: {$value}");
                        $hasChanges = true;
                    }
                }
    
                // Extract URLs from product attributes (for image variations)
                $real_file_url = $this->extractImageURL($product, 'image');
                $image_variation_1_url = $this->extractImageURL($product, 'image_variation_1');
                $image_variation_2_url = $this->extractImageURL($product, 'image_variation_2');
                $image_variation_3_url = $this->extractImageURL($product, 'image_variation_3');
                $image_variation_4_url = $this->extractImageURL($product, 'image_variation_4');
    
                // Initialize all as blank
                $image_variation_1_url = $image_variation_1_url ?? null;
                $image_variation_2_url = $image_variation_2_url ?? null;
                $image_variation_3_url = $image_variation_3_url ?? null;
                $image_variation_4_url = $image_variation_4_url ?? null;
    
                // Function to check if the image URL has changed and update the database
                $updateImageVariation = function ($existingUrl, $newUrl, $imageType) use ($sku, $imageCache) {
                    // Check if the image URL has changed
                    if ($existingUrl !== $newUrl) {
                        // Update the image URL in the database with the actual URL
                        DB::table('tiles')->where('sku', $sku)->update([
                            $imageType => $newUrl,  // Directly store the URL
                            'updated_at' => now(),
                        ]);
    
                        Log::info("Updated image for SKU: {$sku} - {$imageType} changed to: {$newUrl}");
                        return true;
                    }
                    return false; // No change in URL
                };
    
                // Check and update image URLs if they have changed
                $imageUpdated = false;
                $imageUpdated |= $updateImageVariation($tile->real_file, $real_file_url, 'real_file');
                $imageUpdated |= $updateImageVariation($tile->image_variation_1, $image_variation_1_url, 'image_variation_1');
                $imageUpdated |= $updateImageVariation($tile->image_variation_2, $image_variation_2_url, 'image_variation_2');
                $imageUpdated |= $updateImageVariation($tile->image_variation_3, $image_variation_3_url, 'image_variation_3');
                $imageUpdated |= $updateImageVariation($tile->image_variation_4, $image_variation_4_url, 'image_variation_4');
    
                // If any image URL was updated, mark the record as changed
                if ($imageUpdated) {
                    $hasChanges = true;
                }
    
                if ($hasChanges) {
                    $updatedCount++;
                } else {
                    Log::info("Unchanged SKU: {$sku}");
                    $unchangedCount++;
                }
            }
        }
    
        return [
            'updatedCount' => $updatedCount,
            'unchangedCount' => $unchangedCount,
        ];
    }
    
    /**
     * Extracts the URL from the product attributes.
     */
    private function extractImageURL($product, $key)
    {
        // Check if the key exists in the product attributes
        if (isset($product[$key])) {
            // Return the URL (assuming it's the full URL you want to store)
            return $product[$key];
        }
        return null; // If not found, return null or a default value
    }
}