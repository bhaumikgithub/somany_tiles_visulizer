<?php

namespace App\Jobs;

use App\Traits\ApiHelper;
use Carbon\Carbon;
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
     * @throws \Exception
     */
    public function handle(): void
    {
        $insertedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $processedCount = 0;
        $skippedRecords = [];
        $uniqueInserted = [];
        $uniqueUpdated = [];
        $uniqueSkipped = [];

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


        foreach ($this->records as $aTile) {
            $product = $aTile['attributes'];
            $sku = $product['sku'] ?? 'Unknown';
            $processedCount++;
            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

            if (in_array($sku, ['12345678', '1223324324']) || (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING']))) {
                $skippedRecords[] = [
                    'name' => $product['product_name'] ?? 'Unknown',
                    'sku' => $sku,
                    'date' => now()->format('Y-m-d H:i:s'),
                    'reason' => "Excluded SKU or deletion flag",
                ];
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
                $skippedRecords[] = [
                    'name' => $product['product_name'] ?? 'Unknown',
                    'sku' => $sku,
                    'date' => now()->format('Y-m-d H:i:s'),
                    'reason' => "No valid images found",
                ];
                $skippedCount++;
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

                    $variationName = ($variationIndex === 1) ? $baseName : "{$baseName} " . str_pad($variationIndex, 2, '0', STR_PAD_LEFT);

                    // Clear all image fields
                    $product[$column] = $imageURL;
                    $product['product_name'] = $variationName;
                    $product['rotoPrintSetName'] = $rotoPrintSetName;
                    $data = $this->prepareTileData($product, $creation_time, $imageFileName);

                    $existing = DB::table('tiles')->where([['sku', $sku], ['surface', $surface], ['file', $imageFileName]])->first();

                    if ($existing) {
                        $isDifferent = false;
                        foreach ($data as $key => $value) {
                            if ($existing->$key != $value) {
                                $isDifferent = true;
                                break;
                            }
                        }

                        if ($isDifferent) {
                            $data['updated_at'] = now();
                            DB::table('tiles')->where([
                                ['sku', $sku],
                                ['surface', $surface],
                                ['file', $imageFileName]
                            ])->update($data);
                            $updatedCount++;
                            Log::info("Updated SKU: {$sku} - Surface: {$surface} - Name: {$variationName}");
                        }
                    } else {
                        $data['created_at'] = now();
                        $data['updated_at'] = now();
                        DB::table('tiles')->insert($data);
                        $insertedCount++;
                        Log::info("Inserted SKU: {$sku} - Surface: {$surface} - Name: {$variationName}");
                    }

                    $processedCount++;
                }
                $variationIndex++;
            }
        }

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
