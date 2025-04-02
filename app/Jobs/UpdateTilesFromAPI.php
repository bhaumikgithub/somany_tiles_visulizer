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
use Illuminate\Support\Facades\Storage;

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
            'limit' => 5,
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

    // protected function updateMultiple($records, $endDate, $totalCount): array
    // {
    //     $updatedCount = 0;
    //     $unchangedCount = 0;

    //     foreach ($records as $record) {
    //         $sku = $record['code'];
    //         $product = $record['attributes'];

    //         if (isset($product['sku']) && in_array($product['sku'], ['12345678', '1223324324', '1234'])) {
    //             Log::info("Skipping SKU: {$product['sku']}");
    //             continue;
    //         }

    //         if (isset($product['deletion']) && !in_array($product['deletion'], ["RUNNING", "SLOW MOVING"])) {
    //             continue;
    //         }

    //         // Retrieve tile record by SKU
    //         $tile = DB::table('tiles')->where('sku', $sku)->first();

    //         if ($tile) {
    //             $hasChanges = false;
    //             $tileVersion = $this->determineTileVersion($tile->name); // Now using name instead of SKU

    //             foreach ($product as $attribute => $value) {
    //                 if (in_array($attribute, ['image', 'image_variation_1', 'image_variation_2', 'image_variation_3', 'image_variation_4'])) {
    //                     continue;
    //                 }

    //                 if (isset($tile->$attribute) && $tile->$attribute !== $value) {
    //                     DB::table('tiles')->where('sku', $sku)->update([
    //                         $attribute => $value,
    //                         'updated_at' => now(),
    //                     ]);
    //                     Log::info("Updated SKU: {$sku} - Attribute: {$attribute} changed to: {$value}");
    //                     $hasChanges = true;
    //                 }
    //             }

    //             // Extract image URLs
    //             $realFileUrl = $this->extractImageURL($product, 'image');
    //             $imageVar1Url = $this->extractImageURL($product, 'image_variation_1');
    //             $imageVar2Url = $this->extractImageURL($product, 'image_variation_2');
    //             $imageVar3Url = $this->extractImageURL($product, 'image_variation_3');
    //             $imageVar4Url = $this->extractImageURL($product, 'image_variation_4');

    //             // Image update logic based on tile version (from `name` column)
    //             $imageUpdates = [
    //                 'real_file'         => ($tileVersion === 1) ? $realFileUrl : null,
    //                 'image_variation_1' => ($tileVersion === 2) ? $imageVar1Url : null,
    //                 'image_variation_2' => ($tileVersion === 3) ? $imageVar2Url : null,
    //                 'image_variation_3' => ($tileVersion === 4) ? $imageVar3Url : null,
    //                 'image_variation_4' => ($tileVersion === 5) ? $imageVar4Url : null,
    //             ];

    //             $imageUpdated = false;

    //             foreach ($imageUpdates as $column => $newUrl) {
    //                 if (!is_null($newUrl) && $tile->$column !== $newUrl) {
    //                     // Fetch and save the new image locally only for `file` column
    //                     if ($column === 'file') {
    //                         $newImagePath = $this->fetchAndSaveImage($newUrl);
                
    //                         if (is_string($newImagePath)) {
    //                             // Delete old image file if changed
    //                             $this->deleteOldImageIfChanged($tile->$column, $newImagePath);
                
    //                             // Update database with the new file path in `file` column
    //                             DB::table('tiles')->where('sku', $sku)->update([
    //                                 'file' => $newImagePath, // Local path stored
    //                                 'updated_at' => now(),
    //                             ]);
                
    //                             Log::info("Updated file path for SKU: {$sku} - New file path: {$newImagePath}");
    //                             $imageUpdated = true;
    //                         }
    //                     } else {
    //                         // Directly update the API URL in `real_file`, `image_variation_1`, etc.
    //                         DB::table('tiles')->where('sku', $sku)->update([
    //                             $column => $newUrl, // Store original API URL
    //                             'updated_at' => now(),
    //                         ]);
                
    //                         Log::info("Updated image URL for SKU: {$sku} - {$column} set to: {$newUrl}");
    //                         $imageUpdated = true;
    //                     }
    //                 }
    //             }

    //             if ($imageUpdated) {
    //                 $hasChanges = true;
    //             }

    //             if ($hasChanges) {
    //                 $updatedCount++;
    //             } else {
    //                 Log::info("Unchanged SKU: {$sku}");
    //                 $unchangedCount++;
    //             }
    //         }
    //     }

    //     return [
    //         'updatedCount' => $updatedCount,
    //         'unchangedCount' => $unchangedCount,
    //     ];
    // }
    protected function updateMultiple($records, $endDate, $totalCount): array
    {
        $updatedCount = 0;
        $unchangedCount = 0;

        foreach ($records as $record) {
            $sku = $record['code'];
            $product = $record['attributes'];

            if (isset($product['sku']) && in_array($product['sku'], ['12345678', '1223324324', '1234'])) {
                Log::info("Skipping SKU: {$product['sku']}");
                continue;
            }

            if (isset($product['deletion']) && !in_array($product['deletion'], ["RUNNING", "SLOW MOVING"])) {
                continue;
            }

            // Retrieve tile record by SKU
            $tile = DB::table('tiles')->where('sku', $sku)->first();

            if ($tile) {
                $hasChanges = false;
                $tileVersion = $this->determineTileVersion($tile->name); // Get version based on tile name

                // Update non-image attributes
                foreach ($product as $attribute => $value) {
                    if (in_array($attribute, ['image', 'image_variation_1', 'image_variation_2', 'image_variation_3', 'image_variation_4'])) {
                        continue;
                    }

                    if (isset($tile->$attribute) && $tile->$attribute !== $value) {
                        DB::table('tiles')->where('sku', $sku)->update([
                            $attribute => $value,
                            'updated_at' => now(),
                        ]);
                        Log::info("Updated SKU: {$sku} - Attribute: {$attribute} changed to: {$value}");
                        $hasChanges = true;
                    }
                }

                // Extract image URLs from API data
                $realFileUrl    = $this->extractImageURL($product, 'image');
                $imageVar1Url   = $this->extractImageURL($product, 'image_variation_1');
                $imageVar2Url   = $this->extractImageURL($product, 'image_variation_2');
                $imageVar3Url   = $this->extractImageURL($product, 'image_variation_3');
                $imageVar4Url   = $this->extractImageURL($product, 'image_variation_4');

                // Set image updates based on tile version
                $imageUpdates = [];

                switch ($tileVersion) {
                    case 1:
                        if ($tile->real_file !== $realFileUrl) {
                            $imageUpdates['real_file'] = $realFileUrl;
                        }
                        // Clear all variations
                        $imageUpdates['image_variation_1'] = null;
                        $imageUpdates['image_variation_2'] = null;
                        $imageUpdates['image_variation_3'] = null;
                        $imageUpdates['image_variation_4'] = null;
                        break;
                    case 2:
                        if ($tile->image_variation_1 !== $imageVar1Url) {
                            $imageUpdates['image_variation_1'] = $imageVar1Url;
                        }
                        // Clear other columns
                        $imageUpdates['real_file'] = null;
                        $imageUpdates['image_variation_2'] = null;
                        $imageUpdates['image_variation_3'] = null;
                        $imageUpdates['image_variation_4'] = null;
                        break;
                    case 3:
                        if ($tile->image_variation_2 !== $imageVar2Url) {
                            $imageUpdates['image_variation_2'] = $imageVar2Url;
                        }
                        $imageUpdates['real_file'] = null;
                        $imageUpdates['image_variation_1'] = null;
                        $imageUpdates['image_variation_3'] = null;
                        $imageUpdates['image_variation_4'] = null;
                        break;
                    case 4:
                        if ($tile->image_variation_3 !== $imageVar3Url) {
                            $imageUpdates['image_variation_3'] = $imageVar3Url;
                        }
                        $imageUpdates['real_file'] = null;
                        $imageUpdates['image_variation_1'] = null;
                        $imageUpdates['image_variation_2'] = null;
                        $imageUpdates['image_variation_4'] = null;
                        break;
                    case 5:
                        if ($tile->image_variation_4 !== $imageVar4Url) {
                            $imageUpdates['image_variation_4'] = $imageVar4Url;
                        }
                        $imageUpdates['real_file'] = null;
                        $imageUpdates['image_variation_1'] = null;
                        $imageUpdates['image_variation_2'] = null;
                        $imageUpdates['image_variation_3'] = null;
                        break;
                }

                // Remove columns that don’t need updates
                $imageUpdates = array_filter($imageUpdates, fn($value) => !is_null($value));

                // Update only if changes exist
                if (!empty($imageUpdates)) {
                    $imageUpdates['updated_at'] = now();
                    DB::table('tiles')->where('sku', $sku)->update($imageUpdates);
                    Log::info("Updated image URLs for SKU: {$sku}", $imageUpdates);
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
     * Deletes old image from storage (both tiles and icons).
     */
    private function deleteOldImage($imagePath)
    {
        if (!$imagePath) {
            return;
        }

        $storagePath = 'public/' . $imagePath;
        $iconPath = str_replace('tiles/', 'tiles/icons/', $storagePath);

        if (Storage::exists($storagePath)) {
            Storage::delete($storagePath);
            Log::info("Deleted old image: {$storagePath}");
        }

        if (Storage::exists($iconPath)) {
            Storage::delete($iconPath);
            Log::info("Deleted old icon image: {$iconPath}");
        }
    }

    /**
     * Extracts the URL from the product attributes.
     */
    private function extractImageURL($product, $key)
    {
        return $product[$key] ?? null;
    }

    /**
     * Determines the tile version from SKU.
    */
    private function determineTileVersion($sku)
    {
        if (preg_match('/V3\s*(\d*)$/', $sku, $matches)) {
            return isset($matches[1]) && $matches[1] !== '' ? intval($matches[1]) + 1 : 1;
        }
        return 1;
    }
}