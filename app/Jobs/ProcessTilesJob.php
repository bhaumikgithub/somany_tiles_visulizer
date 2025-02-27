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
        $unchangedCount = 0;
        $skippedCount = 0;
        $processedCount = 0;
        $skippedRecords = [];

        // Track unique SKUs for counting
        $uniqueInserted = [];
        $uniqueUpdated = [];
        $uniqueSkipped = [];

        Log::info('Starting record-by-record processing. Total records: ' . $this->totalCount);

        // Initialize Progress Cache
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

            // Skip specific SKUs
            if (in_array($sku, ['12345678', '1223324324'])) {
                Log::info("Skipping SKU: {$sku} due to exclusion.");
                if (!isset($uniqueSkipped[$sku])) {
                    $uniqueSkipped[$sku] = true;
                    $skippedCount++;
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $sku,
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => "Excluded SKU"
                    ];
                }
                continue;
            }

            // Skip records based on deletion flag
            if (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING'])) {
                Log::info("Skipping SKU: {$sku} due to deletion flag: {$product['deletion']}");
                $skippedCount++;
                $skippedRecords[] = [
                    'name' => $product['product_name'] ?? 'Unknown',
                    'sku' => $sku,
                    'date' => now()->format('Y-m-d H:i:s'),
                    'reason' => "Deletion flag: {$product['deletion']}"
                ];
                continue;
            }

            // Define image variations
            $imageVariations = [
                'image' => $product['image'] ?? null,
                'image_variation_1' => $product['image_variation_1'] ?? null,
                'image_variation_2' => $product['image_variation_2'] ?? null,
                'image_variation_3' => $product['image_variation_3'] ?? null,
                'image_variation_4' => $product['image_variation_4'] ?? null,
            ];

            // Determine surfaces (Wall, Floor, etc.)
            $surfaces = $this->determineSurfaceValues($product);

            foreach ($this->records as $aTile) {
                $product = $aTile['attributes'];
                $sku = $product['sku'] ?? 'Unknown';
                $processedCount++;
                $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

                // Skip specific SKUs
                if (in_array($sku, ['12345678', '1223324324'])) {
                    \Log::info("Skipping SKU: {$sku} due to exclusion.");
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $sku,
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => "Excluded SKU"
                    ];
                    continue;
                }

                // Skip records based on a deletion flag
                if (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING'])) {
                    Log::info("Skipping SKU: {$sku} due to deletion flag: {$product['deletion']}");
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $sku,
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => "Deletion flag: {$product['deletion']}"
                    ];
                    continue;
                }

                // Determine surfaces (Wall, Floor, Counter)
                $surfaces = $this->determineSurfaceValues($product);
                if (empty($surfaces)) {
                    Log::warning("No surfaces found for SKU: {$sku}");
                    continue;
                }

                // Image variations
                $imageVariations = [
                    'image' => $product['image'] ?? null,
                    'image_variation_1' => $product['image_variation_1'] ?? null,
                    'image_variation_2' => $product['image_variation_2'] ?? null,
                    'image_variation_3' => $product['image_variation_3'] ?? null,
                    'image_variation_4' => $product['image_variation_4'] ?? null,
                ];

                // Store the first image filename for each surface
                $surfaceImageFiles = [];

                foreach ($surfaces as $surface) {
                    $product['surface'] = trim($surface);

                    $variationIndex = 0;

                    foreach ($imageVariations as $key => $imageURL) {
                        if (!$imageURL) continue; // Skip missing images

                        $variationIndex++;
                        $formattedIndex = $variationIndex > 1 ? " " . str_pad($variationIndex, 2, '0', STR_PAD_LEFT) : "";
                        $newProductName = trim($product['product_name']) . $formattedIndex;

                        // Check if an exact record already exists
                        $existingTile = DB::table('tiles')
                            ->where('sku', $sku)
                            ->where('surface', $surface)
                            ->where('real_file', $imageURL)
                            ->first();

                        if ($existingTile) {
                            // If record exists with the same image, update instead of inserting
                            DB::table('tiles')->where('id', $existingTile->id)->update([
                                'updated_at' => now()
                            ]);
                            Log::info("Updated existing record for SKU: {$sku}, Surface: {$surface}, Image: {$imageURL}");
                            continue; // Skip to the next variation
                        }

                        // Fetch and save image only for the first surface, then reuse for other surfaces
                        if (!isset($surfaceImageFiles[$surface])) {
                            $surfaceImageFiles[$surface] = $this->fetchAndSaveImage($imageURL);
                            if ($surfaceImageFiles[$surface] === null) {
                                Log::error("Failed to fetch image for SKU: {$sku}");
                                $skippedRecords[] = [
                                    'name' => $newProductName,
                                    'sku' => $sku,
                                    'date' => now()->format('Y-m-d H:i:s'),
                                    'reason' => "Failed to fetch image"
                                ];
                                continue;
                            }
                        }

                        // Reuse the first saved image file for all variations of this surface
                        $imageFileName = $surfaceImageFiles[$surface];

                        // Prepare data for insertion
                        $data = $this->prepareTileData($product, $creation_time, $imageFileName);
                        $data['name'] = $newProductName;
                        $data['created_at'] = now();
                        $data['updated_at'] = now();

                        // Insert new record
                        DB::table('tiles')->insert($data);
                        Log::info("Inserted new SKU: {$sku}, Surface: {$surface}, Name: {$newProductName}, Image: $imageFileName");
                        $insertedCount++;

                        // Store the first saved image filename for the next surfaces
                        if (!isset($surfaceImageFiles[$surface])) {
                            $surfaceImageFiles[$surface] = $imageFileName;
                        }
                    }
                }
            }
        }


        //update companies table
        DB::table('companies')->update([
            'last_fetch_date_from_api' => $this->endDate,
            'fetch_products_count' => $this->totalCount,
            'updated_at' => now(),
        ]);
        Log::info('Updated last fetch date in companies table.');
        Log::info("Tile processing completed successfully.");
    }
}
