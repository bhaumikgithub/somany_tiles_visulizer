<?php

namespace App\Jobs;

use App\Mail\TileProcessingReport;
use App\Mail\TileProcessingReportSummary;
use App\Traits\ApiHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProcessTilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels , ApiHelper;

    public $records, $totalCount , $endDate;

    /**
     * Create a new job instance.
     */
    public function __construct($records, $endDate, $totalCount)
    {
        $this->records = $records;
        $this->endDate = $endDate;
        $this->totalCount = $totalCount;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        $insertedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $processedCount = 0;
        $skippedRecords = [];

        $insertedRecords = [];
        $updatedRecords = [];
        $deletedRecords = [];


        Log::info('Starting tile processing. Total records: ' . $this->totalCount);

        $this->updateProcessingCache(0, 0, 0, 0, "Processing started...", []);

        // $existingTiles = DB::table('tiles')->get()->groupBy('sku');
        $imageCache = [];

        foreach ($this->records as $aTile) {
            $product = $aTile['attributes'];
            $sku = $product['sku'] ?? 'Unknown';
            $processedCount++;
            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

            if ($this->shouldSkipTile($aTile['code'],$product, $sku, $skippedRecords)) {
                $skippedCount++;
                continue;
            }

            $surfaces = $this->determineSurfaceValues($product);
            $imageVariations = $this->getValidImages($product);

            if (empty($imageVariations)) {
                $this->logSkippedRecord($sku, "No valid images found", $skippedRecords);
                $skippedCount++;
                continue;
            }

            $baseName = trim($product['product_name']);
            $rotoPrintSetName = str_replace(" FP", "", $baseName);
            $variantIndex = 1;

            foreach ($imageVariations as $column => $imageURL) {
                if (!$imageURL) continue; // Skip missing image variations

                // Define variant name (Base name + number for variations)
                $variantName = ($column == 'real_file') ? $baseName : "$baseName " . str_pad($variantIndex, 2, '0', STR_PAD_LEFT);

                // Clear all image fields
                $product['real_file'] = $product['image_variation_1'] = $product['image_variation_2'] = $product['image_variation_3'] = $product['image_variation_4'] = null;
                
                // Assign the image to the correct column
                $product[$column] = $imageURL;

                $variantIndex++;

                // Fetch image (ensuring unique images for each variation)
                if (!isset($imageCache[$imageURL])) {
                    $imageFileName = $this->getOrFetchImage($sku, $imageURL, $imageCache);
                    $imageCache[$imageURL] = $imageFileName; // Store separately for variations
                } else {
                    $imageFileName = $imageCache[$imageURL];
                }

                foreach ($surfaces as $surface) {
                    $product['rotoPrintSetName'] = $rotoPrintSetName;
                    $product['surface'] = trim($surface);
                    // Try to find existing tile
                    $existingTile = DB::table('tiles')
                        ->where('sku', $sku)
                        ->where('surface', $surface)
                        ->where('name', $variantName)
                        ->first();

                    $updateData = [];
                    $changedColumns = [];

                    if ($existingTile) {
                        // If exists, update if something changed (you're already doing this part well)
                        Log::info("Tile Found | ID: {$existingTile->id}, SKU: {$existingTile->sku}, Name: {$existingTile->name}");
                        // If tile exists, check if image URL matches
                        if( $column === "real_file" ){
                            if ($existingTile->real_file === $imageURL) {
                                $product['image'] = $existingTile->real_file;
                                $imageFileName = $existingTile->file;
                            } else {
                                // If the URL differs, fetch and update the image
                                $product['image'] = $imageURL;
                                $imageFileName = $this->getOrFetchImage($sku, $imageURL, $imageCache);
                            }
                        } 

                        if (Str::startsWith($column, 'image_variation_')) {
                            $existingImageUrl = $existingTile->{$column} ?? null;
                        
                            if ($existingImageUrl === $imageURL) {
                                // Same URL exists, no need to fetch
                                $product[$column] = $existingImageUrl;
                                $imageFileName = $existingTile->file;
                            } else {
                                $product[$column] = $imageURL;
                                // New URL found, fetch and update the image
                                $imageFileName = $this->getOrFetchImage($sku, $imageURL, $imageCache);
                            }
                        }
                        

                        $skipNameUpdate = (trim($product['product_name']) !== $variantName) ? "true" : "false";
                        $otherFields = $this->prepareTileUpdateData($product, $creation_time, $imageFileName , $skipNameUpdate);

                        // Merge other changes if they differ
                        // foreach ($otherFields as $column => $newValue) {
                        //     if ($existingTile->$column !== $newValue) {
                        //         $updateData[$column] = $newValue;
                        //         $changedColumns[] = $column;
                        //     }
                        // }

                        $beforeAfterChanges = [];

                        foreach ($otherFields as $column => $newValue) {
                            $oldValue = $existingTile->$column;

                            // Use loose comparison (==) if needed to avoid false positives on null/empty string issues
                            if ($oldValue !== $newValue) {
                                $updateData[$column] = $newValue;
                                $changedColumns[] = $column;

                                $beforeAfterChanges[$column] = [
                                    'before' => $oldValue,
                                    'after' => $newValue,
                                ];
                            }
                        }
                
                        if (!empty($changedColumns)) {
                            $updateData['updated_at'] = now();
                            $updateData['api_json'] = json_encode($aTile);
                
                            DB::table('tiles')
                                ->where('sku', $sku)
                                ->where('surface', $surface)
                                ->where('name', $variantName)
                                ->update($updateData);
                
                            $changeKey = $variantName . '|' . $sku . '|' . implode(',', $changedColumns);
                
                            if (isset($updatedRecords[$changeKey])) {
                                $updatedRecords[$changeKey]['surfaces'][] = $surface;
                            } else {
                                // $updatedRecords[$changeKey] = [
                                //     'name' => $variantName,
                                //     'sku' => $sku,
                                //     'surfaces' => [$surface],
                                //     'changedColumns' => $changedColumns,
                                // ];

                                $updatedRecords[$changeKey] = [
                                    'name' => $variantName,
                                    'sku' => $sku,
                                    'surfaces' => [$surface],
                                    'changedColumns' => $changedColumns,
                                    'changes' => $beforeAfterChanges,
                                ];
                            }
                            
                            // 🔽 Log before/after values
                            Log::info("Updated Tile => Tile ID : {$existingTile->id} | SKU: {$sku} | Surface: {$surface} | Variant: {$variantName}");
                            foreach ($beforeAfterChanges as $field => $values) {
                                Log::info("  - Field: {$field} | Before: {$values['before']} | After: {$values['after']}");
                            }

                            $updatedCount++;
                        } else {
                            Log::info("No changes detected for Tile SKU: {$existingTile->sku}");
                        }
                    } else {
                        Log::info("Inserting new surface {$surface} for SKU: {$sku} with variant: {$variantName}");
                
                        $product['product_name'] = $variantName;
                        $product['file'] = $imageFileName;
                
                        $data = $this->prepareTileData($product, $creation_time, $imageFileName);
                        $data['created_at'] = now();
                        $data['updated_at'] = now();
                        $data['api_json'] = json_encode($aTile);
                
                        DB::table('tiles')->insert($data);
                
                        $insertedRecords[] = [
                            'sku' => $sku,
                            'name' => $variantName,
                            'surface' => $surface,
                        ];
                
                        $insertedCount++;
                    }
                }
                
            }
            unset($imageCache[$imageURL]); // Free memory
            unset($imageVariations);
            gc_collect_cycles();
        }
        
        $this->softDeleteMissingTiles($skippedRecords , $deletedRecords);

        DB::table('companies')->update([
            'last_fetch_date_from_api' => $this->endDate,
            'fetch_products_count' => $this->totalCount,
            'updated_at' => now(),
        ]);

        $this->updateProcessingCache($processedCount, $insertedCount, $updatedCount, $skippedCount, "{$processedCount} / {$this->totalCount} records processed", $skippedRecords);

        /** Send mail to the admin */
        $sendMail = $this->sendMailTileReport($insertedRecords, $updatedRecords, $deletedRecords,$skippedRecords);

        gc_collect_cycles();
        Log::info('Tile processing completed successfully.');
    }

    /**
     * Updates processing cache.
     */
    private function updateProcessingCache(int $processed, int $inserted, int $updated, int $skipped, string $status, array $skippedRecords): void
    {
        Cache::put('tile_processing_progress', [
            'total' => $this->totalCount,
            'processed' => $processed,
            'percentage' => min(($processed / $this->totalCount) * 100, 100),
            'inserted' => $inserted,
            'updated' => $updated,
            'skipped' => $skipped,
            'status' => $status,
            'skipped_records' => $skippedRecords,
        ], now()->addMinutes(10));
    }

    /**
     * Determines whether a tile should be skipped.
     */
    private function shouldSkipTile(string $code , array $product, string $sku, array &$skippedRecords): bool
    {
        if (in_array($code, ['12345678', '1223324324','1234','TESTSKU'])) {
            $this->logSkippedRecord($code, "Excluded SKU", $skippedRecords);
            return true;
        }

        if (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING'])) {
            $this->logSkippedRecord($sku, "Deletion flag: {$product['deletion']}", $skippedRecords);
            return true;
        }

        return false;
    }

    /**
     * Logs a skipped record.
     */
    private function logSkippedRecord(string $sku, string $reason, array &$skippedRecords, string $name = 'Unknown'): void
    {
        $skippedRecords[] = [
            'name' => $name,
            'sku' => $sku,
            'date' => now()->format('Y-m-d H:i:s'),
            'reason' => $reason
        ];
    }

    /**
     * Extracts valid images from the product.
     */
    private function getValidImages(array $product): array
    {
        // return array_filter([
        //     'real_file' => $product['image'] ?? null,
        //     'image_variation_1' => $product['image_variation_1'] ?? null,
        //     'image_variation_2' => $product['image_variation_2'] ?? null,
        //     'image_variation_3' => $product['image_variation_3'] ?? null,
        //     'image_variation_4' => $product['image_variation_4'] ?? null,
        // ], fn($url) => !empty($url) && !$this->isInvalidFormat($url));


        return array(
            'real_file' => $product['image'] ?? null,
            'image_variation_1' => $product['image_variation_1'] ?? null,
            'image_variation_2' => $product['image_variation_2'] ?? null,
            'image_variation_3' => $product['image_variation_3'] ?? null,
            'image_variation_4' => $product['image_variation_4'] ?? null,
        );
    }

    /**
     * Fetches or reuses an image filename.
     * @throws Exception
     */
    private function getOrFetchImage(string $sku, string $imageURL, array &$imageCache): ?string
    {
        // First, check if the image is already cached to avoid duplicate DB queries
        if (isset($imageCache[$sku][$imageURL])) {
            return $imageCache[$sku][$imageURL];
        }

        // Check the database for an existing file
        $existingFile = DB::table('tiles')
            ->where('sku', $sku)
            ->where(function ($query) use ($imageURL) {
                $query->where('real_file', $imageURL)
                    ->orWhere('image_variation_1', $imageURL)
                    ->orWhere('image_variation_2', $imageURL)
                    ->orWhere('image_variation_3', $imageURL)
                    ->orWhere('image_variation_4', $imageURL);
            })
            ->value('file');
        
        if ($existingFile) {
            //Log::info("Found Existing File for SKU: {$sku} => {$existingFile}");
            $imageCache[$sku][$imageURL] = $existingFile;
            return $existingFile;
        }

        // If not found, fetch and store it
        $newFile = $this->fetchAndSaveImage($imageURL);
        if ($newFile) {
            $imageCache[$sku][$imageURL] = $newFile;
        }
        return $newFile;
    }
    /**
     * Softly deletes tiles that are no longer in the API response.
     */
    private function softDeleteMissingTiles(array &$skippedRecords ,array &$deletedRecords): void
    {
        $apiSkus = collect($this->records)->pluck('attributes.sku')->filter()->unique()->toArray();
        $existingTiles = DB::table('tiles')->where('from_api', "1")->get()->keyBy('sku');

        // Get SKUs to soft delete (those that exist in DB but not in API)
        $skusToDelete = array_diff($existingTiles->keys()->toArray(), $apiSkus);
    
        if (!empty($skusToDelete)) {
            DB::table('tiles')->whereIn('sku', $skusToDelete)->update(['enabled' => 0, 'deleted_at' => now()]);

            foreach ($skusToDelete as $sku) {
                $tile = $existingTiles[$sku] ?? null;

                if (!$tile) {
                    Log::warning("Tile not found in existingTiles for SKU: $sku");
                    continue;
                }
                // $this->logSkippedRecord($sku, "Product not exists in API", $skippedRecords, $tile->name ?? '-');

                // Store deleted records for email reporting
                $deletedRecords[] = [
                    'id' => $tile->id,
                    'name' => $tile->name,
                    'sku' => $tile->sku,
                    'reason' => 'Product not exists in API'
                ];
            }

            Log::info("Soft deleted missing SKUs: " . implode(', ', $skusToDelete));
        }
    }

    /**
     * @param $existingTile
     * @param $newImageURL
     * @return bool
     */
    private function isImageDifferent($existingTile, $newImageURL): bool
    {
        return !in_array($newImageURL, [
            $existingTile->real_file,
            $existingTile->image_variation_1,
            $existingTile->image_variation_2,
            $existingTile->image_variation_3,
            $existingTile->image_variation_4,
        ]);
    }

    private function sendMailTileReport($insertedRecords , $updatedRecords , $deletedRecords , $skippedRecords){
        // Step 1: Group by SKU + Name and combine surfaces
        $groupedRecords = [];

        foreach ($insertedRecords as $record) {
            $key = $record['sku'] . '|' . $record['name'];

            if (!isset($groupedRecords[$key])) {
                $groupedRecords[$key] = [
                    'sku' => $record['sku'],
                    'name' => $record['name'],
                    'surface' => [],
                ];
            }

            if (!in_array($record['surface'], $groupedRecords[$key]['surface'])) {
                $groupedRecords[$key]['surface'][] = $record['surface'];
            }
        }

        // Step 2: Convert surfaces to comma-separated string
        $finalRecords = [];

        foreach ($groupedRecords as $group) {
            $finalRecords[] = [
                'sku' => $group['sku'],
                'name' => $group['name'],
                'surface' => implode(',', $group['surface']),
            ];
        }

        /** Updated records data */
        $updatedRecordsList = [];
        // Log grouped update info after the loop
        if (!empty($updatedRecords)) {
            foreach ($updatedRecords as $record) {
                $surfaceList = implode(', ', $record['surfaces']);
                $columnList = implode(', ', $record['changedColumns']);
                $updatedRecordsList[] = [
                    'name' => $record['name'],
                    'sku' =>$record['sku'],
                    'surfaces' => $surfaceList,
                    'changedColumns' => $columnList,
                ];
            }
        } else {
            Log::info("No changes detected");
        }
    
        Mail::to('tracingidea@gmail.com')
            ->bcc('kinjalupadhyay.tps@gmail.com')
            ->send(new TileProcessingReport($finalRecords, $updatedRecordsList, $deletedRecords,$skippedRecords));


        // Collect unique SKUs from each list
        $insertedSkus = collect($finalRecords)->pluck('sku')->unique()->values()->implode(',');
        $updatedSkus = collect($updatedRecordsList)->pluck('sku')->unique()->values()->implode(',');
        $deletedSkus = collect($deletedRecords)->pluck('sku')->unique()->values()->implode(',');
        $skippedSkus = collect($skippedRecords)->pluck('sku')->unique()->values()->implode(',');


        // Mail::to('tracingidea@gmail.com')
        //     ->bcc('kinjalupadhyay.tps@gmail.com')
        //     ->send(new TileProcessingReportSummary(
        //         count($insertedRecords),
        //         count($updatedRecordsList),
        //         count($deletedRecords),
        //         count($skippedRecords),
        //         $insertedSkus,
        //         $updatedSkus,
        //         $deletedSkus,
        //         $skippedSkus,
        //         $this->totalCount
        //     ));

        unset($insertedRecords, $updatedRecords, $deletedRecords,$skippedRecords);
    }
}
