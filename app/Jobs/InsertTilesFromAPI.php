<?php

namespace App\Jobs;

use App\Traits\ApiHelper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
     */
    public function handle()
    {

        \Log::info("Inserting tiles from API between {$this->startDate} and {$this->endDate}");

        ini_set('max_execution_time', 0);

        // Increase memory limit if needed
        ini_set('memory_limit', '1024M');

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
        \Log::info("Tiles data inserted successfully from API. Total Records: {$totalRecords}");
    }

    protected function updateOrInsertMultiple($records, $endDate, $totalCount): array
    {
        $count = 0;  // Variable to track the number of processed records
        $insertedCount = 0; // Track new insertions
        $updatedCount = 0; // Track updates
        $unchangedCount = 0; // Track unchanged records

        // Step 1: Fetch all existing SKUs from the database
        //$existingSkus = \DB::table('tiles')->pluck('sku')->toArray();

        foreach ($records as $aTile) {
            $product = $aTile['attributes'];

            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');

            // Step 2: Skip processing if SKU already exists in the database
//            if (in_array($product['sku'], $existingSkus)) {
//                \Log::info("Skipping SKU: {$product['sku']} - Already exists in DB.");
//                continue;
//            }

            if (in_array($product['sku'], ['12345678', '1223324324'])) {
                continue; // Skip this record
            }


            // If a deletion flag is set, remove the record
            if (isset($product['deletion'])) {
                if ($product['deletion'] !== "RUNNING" && $product['deletion'] !== "SLOW MOVING")
                    continue;
            }

            // Check if the application is "Wall & Floor"
            $applications = explode(' & ', $product['application']);
            foreach ($applications as $surface) {
                $product['surface'] = trim($surface);
                $data = $this->prepareTileData($product, $creation_time);

                // Debugging log to ensure both entries are processed
                \Log::info('Processing SKU: ' . $product['sku'] . ' for Surface: ' . $surface);

                $existing = \DB::table('tiles')->where('sku', $product['sku'])->where('surface', $surface)->first();
                if ($existing) {
                    $isDifferent = false;
                    foreach ($data as $key => $value) {
                        if ($existing->$key != $value) {
                            $isDifferent = true;
                            break;
                        }
                    }

                    if ($isDifferent) {
                        \DB::table('tiles')->where('sku', $product['sku'])->where('surface', $surface)->update($data);
                        $updatedCount++;
                    } else {
                        $unchangedCount++;
                    }
                } else {
                    \DB::table('tiles')->insert($data);
                    $insertedCount++;
                }
                $count++;
            }
        }

        // Update the last-fetched date
        \DB::table('companies')->update(
            ['last_fetch_date_from_api' => $endDate, 'fetch_products_count' => $totalCount, 'updated_at' => now()]
        );

        return ['insertedCount' => $insertedCount, 'updatedCount' => $updatedCount, 'count' => $count, 'unchangedCount' => $unchangedCount];
    }

}
