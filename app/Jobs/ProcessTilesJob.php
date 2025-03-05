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

        Cache::forever('tile_processing_progress', [
            'total' => $this->totalCount,
            'processed' => 0,
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
            'percentage' => 0,
            'status' => "Processing started...",
            'skipped_records' => [],
        ]);

        // Fetch existing tiles (indexed by SKU + Surface + Image)
        $existingTiles = DB::table('tiles')->select('id', 'sku', 'surface', 'file', 'real_file', 'name')->get();
        $existingTilesMap = [];

        foreach ($existingTiles as $tile) {
            $existingTilesMap[$tile->sku][$tile->surface][$tile->real_file] = [
                'id' => $tile->id,
                'file' => $tile->file,
                'product_name' => $tile->name,
            ];
        }

        $imageCache = []; // Store fetched image filenames for reusability

        foreach ($this->records as $aTile) {
            $product = $aTile['attributes'];
            $sku = $product['sku'] ?? 'Unknown';
            $processedCount++;
            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

            // Skip excluded SKUs and deleted products
            if (in_array($sku, ['12345678', '1223324324']) || (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING']))) {
                $skippedRecords[] = ['sku' => $sku, 'reason' => "Excluded SKU or deletion flag"];
                $skippedCount++;
                continue;
            }

            $surfaces = $this->determineSurfaceValues($product);
            $imageVariations = array_filter([
                'real_file' => $product['image'] ?? null,
                'image_variation_1' => $product['image_variation_1'] ?? null,
                'image_variation_2' => $product['image_variation_2'] ?? null,
                'image_variation_3' => $product['image_variation_3'] ?? null,
                'image_variation_4' => $product['image_variation_4'] ?? null,
            ], fn($url) => !empty($url) && !$this->isInvalidFormat($url));

            if (empty($imageVariations)) {
                $skippedRecords[] = ['sku' => $sku, 'reason' => "No valid images found"];
                $skippedCount++;
                continue;
            }

            $baseName = trim($product['product_name']);
            $rotoPrintSetName = str_replace(" FP", "", $baseName);
            $variationIndex = 1;

            foreach ($imageVariations as $column => $imageURL) {
                if (isset($imageCache[$sku][$imageURL])) {
                    $imageFileName = $imageCache[$sku][$imageURL]; // Reuse cached image filename
                } else {
                    $imageFileName = $this->fetchAndSaveImage($imageURL);
                    $imageCache[$sku][$imageURL] = $imageFileName; // Cache it for future surfaces
                }

                if (!$imageFileName) continue;

                foreach ($surfaces as $surface) {
                    $product['surface'] = trim($surface);
                    $existingTile = $existingTilesMap[$sku][$surface][$imageURL] ?? null;
                    $variantName = ($variationIndex === 1) ? $baseName : "{$baseName} " . str_pad($variationIndex, 2, '0', STR_PAD_LEFT);

                    // Clear all image fields before assigning
                    $product['real_file'] = $product['image_variation_1'] = $product['image_variation_2'] = $product['image_variation_3'] = $product['image_variation_4'] = null;
                    $product[$column] = $imageURL;

                    // Prepare product data
                    $product['product_name'] = $variantName;
                    $product['rotoPrintSetName'] = $rotoPrintSetName;
                    $data = $this->prepareTileData($product, $creation_time, $imageFileName);

                    if ($existingTile) {
                        $existingDBTile = DB::table('tiles')->where('id', $existingTile['id'])->first();

                        $isDifferent = false;
                        foreach ($data as $key => $value) {
                            if ($existingDBTile->$key != $value) {
                                $isDifferent = true;
                                break;
                            }
                        }

                        if ($isDifferent) {
                            $data['updated_at'] = now();
                            DB::table('tiles')->where('id', $existingTile['id'])->update($data);
                            $updatedCount++;
                            Log::info("Updated Tile ID: {$existingTile['id']}");
                        } else {
                            $skippedRecords[] = ['sku' => $sku, 'reason' => "No changes detected"];
                            $skippedCount++;
                        }
                    } else {
                        // Insert only if SKU + Surface + Image combination does NOT exist
                        $data['created_at'] = now();
                        $data['updated_at'] = now();
                        DB::table('tiles')->insert($data);
                        $insertedCount++;
                        Log::info("Inserted SKU: {$sku} - Surface: {$surface} - Name: {$variantName}");
                    }

                    $processedCount++;
                }
                $variationIndex++;
            }
        }

        // Update processing status
        DB::table('companies')->update([
            'last_fetch_date_from_api' => $this->endDate,
            'fetch_products_count' => $this->totalCount,
            'updated_at' => now(),
        ]);

        Cache::put('tile_processing_progress', [
            'total' => $this->totalCount,
            'processed' => $processedCount,
            'percentage' => min(($processedCount / $this->totalCount) * 100, 100),
            'inserted' => $insertedCount,
            'updated' => $updatedCount,
            'skipped' => $skippedCount,
            'status' => "$processedCount / $this->totalCount records processed",
            'skipped_records' => $skippedRecords,
        ], now()->addMinutes(10));

        Log::info('Tile processing completed successfully.');
    }
}
