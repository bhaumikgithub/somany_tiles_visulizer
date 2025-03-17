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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        Log::info('Starting tile processing. Total records: ' . $this->totalCount);

        $this->updateProcessingCache(0, 0, 0, 0, "Processing started...", []);

        $existingTiles = DB::table('tiles')->get()->groupBy('sku');
        $imageCache = [];

        foreach ($this->records as $aTile) {
            $product = $aTile['attributes'];
            $sku = $product['sku'] ?? 'Unknown';
            $processedCount++;
            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

            if ($this->shouldSkipTile($product, $sku, $skippedRecords)) {
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
                $imageFileName = $this->getOrFetchImage($sku, $imageURL, $imageCache);
                if ($imageFileName) {
                    $data[$column] = $imageURL;
                }

                // Clear all image fields
                $product['real_file'] = $product['image_variation_1'] = $product['image_variation_2'] = $product['image_variation_3'] = null;

                // Assign the image to the correct column
                $product[$column] = $imageURL;


                foreach ($surfaces as $surface) {
                    $product['surface'] = trim($surface);
                    $variantName = $variantIndex === 1 ? $baseName : "$baseName " . str_pad($variantIndex, 2, '0', STR_PAD_LEFT);
                    $variantIndex++;

                    $existingTile = DB::table('tiles')
                        ->where('sku', $sku)
                        ->where('surface', $surface)
                        ->where('name', $variantName)
                        ->first();

                    if ($existingTile) {
                        if ($this->needsUpdate($existingTile, $data)) {
                            if ($this->isImageDifferent($existingTile, $imageURL)) {
                                $imageFileName = $this->getOrFetchImage($sku, $imageURL, $imageCache);
                            }
                            DB::table('tiles')->where('id', $existingTile->id)->update(array_merge($data, ['updated_at' => now()]));
                            $updatedCount++;
                            Log::info("Updated Tile ID: {$existingTile->id} - {$sku} - {$surface}");
                        } else {
                            Log::info("Skipped - No changes detected for SKU: {$sku}, Surface: {$surface}");
                            $skippedCount++;
                        }
                        continue;
                    }

                    $product['product_name'] = $variantName;
                    $product['rotoPrintSetName'] = $rotoPrintSetName;
                    $data = $this->prepareTileData($product, $creation_time, $imageFileName);

                    DB::table('tiles')->insert($data);
                    Log::info("Inserted: {$variantName} (SKU: {$sku}, Surface: {$surface})");
                    $insertedCount++;
                }
            }
        }

        $this->softDeleteMissingTiles($skippedRecords);
        DB::table('companies')->update([
            'last_fetch_date_from_api' => $this->endDate,
            'fetch_products_count' => $this->totalCount,
            'updated_at' => now(),
        ]);

        $this->updateProcessingCache($processedCount, $insertedCount, $updatedCount, $skippedCount, "{$processedCount} / {$this->totalCount} records processed", $skippedRecords);
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
    private function shouldSkipTile(array $product, string $sku, array &$skippedRecords): bool
    {
        if (in_array($sku, ['12345678', '1223324324'])) {
            $this->logSkippedRecord($sku, "Excluded SKU", $skippedRecords);
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
            Log::info("Using Cached File for SKU: {$sku} => {$imageCache[$sku][$imageURL]}");
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
            Log::info("Fetched New Image for SKU: {$sku} => {$newFile}");
            $imageCache[$sku][$imageURL] = $newFile;
        }

        return $newFile;
    }



    /**
     * Checks if a tile needs an update.
     */
    private function needsUpdate($existingTile, array $data): bool
    {
        unset($data['created_at']);
        foreach ($data as $key => $value) {
            if ($existingTile->$key !== $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Soft deletes tiles that are no longer in the API response.
     */
    private function softDeleteMissingTiles(array &$skippedRecords): void
    {
        $apiSkus = collect($this->records)->pluck('attributes.sku')->filter()->unique()->toArray();
        $existingTiles = DB::table('tiles')->where('from_api', 1)->pluck('name', 'sku')->toArray();
        $skusToDelete = array_diff(array_keys($existingTiles), $apiSkus);

        if (!empty($skusToDelete)) {
            DB::table('tiles')->whereIn('sku', $skusToDelete)->update(['enabled' => 0, 'deleted_at' => now()]);

            foreach ($skusToDelete as $sku) {
                $this->logSkippedRecord($sku, "Product not exists in API", $skippedRecords, $existingTiles[$sku] ?? '-');
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
