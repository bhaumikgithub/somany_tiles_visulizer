<?php

namespace App\Jobs;

use App\Mail\TileProcessingReport;
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

        $existingTiles = DB::table('tiles')->get()->groupBy('sku');
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
                    $product['surface'] = trim($surface);

                    $existingTile = DB::table('tiles')
                        ->where('sku', $sku)
                        ->where('surface', $surface)
                        ->where('name', $variantName)
                        ->first();

                    if ($existingTile) {
                        $updateData = [];
                        // Determine which column to update
                        $mappedColumn = $column; // 'real_file', 'image_variation_1', etc.
                        $existingImage = $existingTile->$mappedColumn;

                        if ($existingImage !== $imageURL) {
                            // Fetch new image only if different
                            $imageFileName = $this->getOrFetchImage($sku, $imageURL, $imageCache);
                            $updateData[$mappedColumn] = $imageURL;

                            // Store the file name in the 'file' column
                            $updateData['file'] = $imageFileName;

                            // Set all other image variation columns to NULL
                            $updateData['real_file'] = ($mappedColumn === 'real_file') ? $imageURL : null;
                            $updateData['image_variation_1'] = ($mappedColumn === 'image_variation_1') ? $imageURL : null;
                            $updateData['image_variation_2'] = ($mappedColumn === 'image_variation_2') ? $imageURL : null;
                            $updateData['image_variation_3'] = ($mappedColumn === 'image_variation_3') ? $imageURL : null;
                            $updateData['image_variation_4'] = ($mappedColumn === 'image_variation_4') ? $imageURL : null;

                            // Update the record
                            DB::table('tiles')->where('id', $existingTile->id)->update($updateData);

                            $updatedRecords[] = [
                                'id' => $existingTile->id,
                                'name' => $existingTile->name,
                                'sku' => $sku,
                                'surface' => $surface
                            ];

                            Log::info("Updated Tile ID: {$existingTile->id} - Column: $mappedColumn - SKU: $sku");
                        }
                    } else {
                        $product['product_name'] = $variantName;
                        $product['rotoPrintSetName'] = $rotoPrintSetName;

                        // Store unique image for each variation
                        $product['file'] = $imageFileName;
                        $product[$column] = $imageURL;

                        $data = $this->prepareTileData($product, $creation_time, $imageFileName);
                        $data['created_at'] = now();
                        $data['updated_at'] = now();
                        $data['api_json'] = json_encode($aTile);

                        DB::table('tiles')->insert($data);
                        Log::info("Inserted: {$variantName} (SKU: {$sku}, Surface: {$surface})");

                        $insertedRecords[] = [
                            'name' => $variantName,
                            'sku' => $sku,
                            'surface' => $surface
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

        Mail::to('kinjalupadhyay.tps@gmail.com')
            ->send(new TileProcessingReport($insertedRecords, $updatedRecords, $deletedRecords,$skippedRecords));

        unset($insertedRecords, $updatedRecords, $deletedRecords);
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
        if (in_array($code, ['12345678', '1223324324','1234'])) {
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
        return array_filter([
            'real_file' => $product['image'] ?? null,
            'image_variation_1' => $product['image_variation_1'] ?? null,
            'image_variation_2' => $product['image_variation_2'] ?? null,
            'image_variation_3' => $product['image_variation_3'] ?? null,
            'image_variation_4' => $product['image_variation_4'] ?? null,
        ], fn($url) => !empty($url) && !$this->isInvalidFormat($url));
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
            Log::info("Found Existing File for SKU: {$sku} => {$existingFile}");
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



//    /**
//     * Checks if a tile needs an update.
//     */
//    private function needsUpdate($existingTile, array $data): bool
//    {
//        unset($data['created_at']);
//
//        foreach ($data as $key => $value) {
//            if (!property_exists($existingTile, $key)) {
//                continue; // Skip if the column does not exist in the DB
//            }
//
//            // Convert null to empty string to avoid type mismatches
//            if (($existingTile->$key ?? '') !== ($value ?? '')) {
//                return true;
//            }
//        }
//        return false;
//    }


    /**
     * Softly deletes tiles that are no longer in the API response.
     */
    private function softDeleteMissingTiles(array &$skippedRecords ,array &$deletedRecords): void
    {
        $apiSkus = collect($this->records)->pluck('attributes.sku')->filter()->unique()->toArray();
        $existingTiles = DB::table('tiles')->where('from_api', 1)->pluck('name', 'sku')->toArray();
        $skusToDelete = array_diff(array_keys($existingTiles), $apiSkus);

        if (!empty($skusToDelete)) {
            DB::table('tiles')->whereIn('sku', $skusToDelete)->update(['enabled' => 0, 'deleted_at' => now()]);

            foreach ($skusToDelete as $sku) {
                $this->logSkippedRecord($sku, "Product not exists in API", $skippedRecords, $existingTiles[$sku] ?? '-');

                // Store deleted records for email reporting
                $deletedRecords[] = [
                    'id' => $sku->id,
                    'name' => $sku->name,
                    'sku' => $sku->sku
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
}
