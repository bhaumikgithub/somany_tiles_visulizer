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
            if (in_array($product['sku'], ['12345678', '1223324324'])) {
                \Log::info("Skipping SKU: {$product['sku']} due to exclusion.");
                if (!isset($uniqueSkipped[$sku])) {
                    $uniqueSkipped[$sku] = true;
                    $skippedCount++;
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $sku,
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => "Missing required fields"
                    ];
                }
                continue;
            }

            // Skip records based on a deletion flag
            if (isset($product['deletion']) && !in_array($product['deletion'], ['RUNNING', 'SLOW MOVING'])) {
                Log::info("Skipping SKU: {$product['sku']} due to deletion flag: {$product['deletion']}");
                $skippedCount++;
                $skippedRecords[] = [
                    'name' => $product['product_name'] ?? 'Unknown',
                    'sku' => $product['sku'],
                    'date' => now()->format('Y-m-d H:i:s'),
                    'reason' => "Deletion flag: {$product['deletion']}"
                ];
                continue;
            }

            // Check if the Image Already Exists in DB
            $existingTile = \DB::table('tiles')->where('sku', $product['sku'])->first();

            // Store the image filename to reuse for multiple surfaces
            $imageURL = $product['image'] ?? $product['image_variation_1'];

            if ($imageURL) {
                $extension = strtolower(pathinfo(strtok($imageURL, '?'), PATHINFO_EXTENSION));
                // Ignore TIFF and PSD files
                if (in_array($extension, ['tiff', 'tif', 'psd'])) {
                    $imageFileName = null; // Store null in the database for unsupported formats
                    $skippedCount++;
                    // Store this record in a skipped records list
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $product['sku'],
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => "Unsupported image format: {$extension}"
                    ];

                    // Skip the entire record, ensuring it is NOT inserted or updated in DB
                    continue;
                }

                // If the record already exists with the same image, do nothing
                if ($existingTile && $existingTile->real_file === $imageURL) {
                    $imageFileName = $existingTile->file; // Reuse existing image
                } else {
                    // Fetch and save image if not a PSD/TIFF
                    $imageFileName = $this->fetchAndSaveImage($imageURL);

                    // If image download fails, skip the record
                    if ($imageFileName === null) {
                        $skippedRecords[] = [
                            'name' => $product['product_name'] ?? 'Unknown',
                            'sku' => $product['sku'],
                            'date' => now()->format('Y-m-d H:i:s'),
                            'reason' => "Failed to fetch image"
                        ];
                        continue; // Skip the record safely
                    }
                }
            }

            // Determine the surfaces (Wall, Floor, Counter)
            $surfaces = $this->determineSurfaceValues($product);

            foreach ($surfaces as $surface) {
                $product['surface'] = trim($surface);
                $data = $this->prepareTileData($product, $creation_time, $imageFileName);

                // Skip record if the `skip` flag is set
                if (isset($data['skip']) && $data['skip'] === true) {
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $product['sku'] ?? 'Unknown',
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => $data['reason'] // Dynamic reason based on missing fields
                    ];
                    continue; // Skip processing this record
                }

                try {
                    $existing = DB::table('tiles')->where('sku', $product['sku'])->where('surface', $surface)->first();
                    $action = $existing ? 'Updated' : 'Inserted';
                    if ($existing) {
                        // Check for changes
                        $isDifferent = false;
                        foreach ($data as $key => $value) {
                            if ($existing->$key != $value) {
                                $isDifferent = true;
                                break;
                            }
                        }
                        if ($isDifferent) {
                            if (!isset($uniqueUpdated[$sku])) {
                                $uniqueUpdated[$sku] = true;
                                $updatedCount++;
                            }
                            DB::table('tiles')->where('sku', $product['sku'])->where('surface', $surface)->update($data);
                            Log::info("Updated SKU: {$product['sku']} for Surface: $surface");
                        }
                    } else {
                        if (!isset($uniqueInserted[$sku])) {
                            $uniqueInserted[$sku] = true;
                            $insertedCount++;
                        }
                        DB::table('tiles')->insert($data);
                        Log::info("Inserted new SKU: {$product['sku']} for Surface: $surface");
                    }

                    $processedCount++;

                    // Store progress update **AFTER EACH RECORD**
                    $progressPercentage = min(($processedCount / $this->totalCount) * 100, 100);
                    Cache::put('tile_processing_progress', [
                        'total' => $this->totalCount,
                        'processed' => $processedCount,
                        'percentage' => $progressPercentage,
                        'inserted' => $insertedCount,
                        'updated' => $updatedCount,
                        'skipped' => $skippedCount,
                        'status' => "$processedCount / $this->totalCount records processed",
                        'last_record' => [
                            'name' => $product['product_name'] ?? 'Unknown',
                            'sku' => $product['sku'],
                            'surface' => $surface,
                            'action' => $action,
                        ],
                        'skipped_records' => $skippedRecords, // Store skipped/error records
                    ], now()->addMinutes(10));
                } catch (\Exception $e) {
                    if (!isset($uniqueSkipped[$sku])) {
                        $uniqueSkipped[$sku] = true;
                        $skippedCount++;
                    }
                    $skippedRecords[] = [
                        'name' => $product['product_name'] ?? 'Unknown',
                        'sku' => $sku,
                        'date' => now()->format('Y-m-d H:i:s'),
                        'reason' => "Error: " . $e->getMessage()
                    ];
                    Log::error("Error processing SKU: {$sku} - " . $e->getMessage());
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
