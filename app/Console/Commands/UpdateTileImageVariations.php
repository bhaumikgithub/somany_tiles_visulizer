<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Traits\ApiHelper;

class UpdateTileImageVariations extends Command
{
    use ApiHelper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tiles:update-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update image variation URLs based on tile name conventions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = '2000-01-01'; // Set your start date
        $endDate = now()->toDateString(); // Set end date to today's date

        Log::info("Updating tiles from API between {$startDate} and {$endDate}");

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2024M');

        $getToken = $this->loginAPI();

        // Get tiles data from API
        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";
        $queryParams = http_build_query([
            'limit' => 1000, // Increase limit to fetch more records
            's' => $startDate,
            'e' => $endDate,
        ]);

        $headers = [
            'JWTAuthorization: Bearer ' . $getToken,
        ];

        // Use the trait function for GET request
        $data = $this->makeGetRequest($apiUrl, $queryParams, $headers);

        if (isset($data['error'])) {
            Log::error("Unable to fetch records from API: " . $data['error']);
            return;
        }

        if (empty($data)) {
            Log::info("No new records found in API.");
            return;
        }

        $totalRecords = count($data);
        Log::info("Total API Records: {$totalRecords}");

        // Update images using API data
        $this->updateImageURLs($data);
    }

    /**
     * Update tile images based on API response.
     */
    protected function updateImageURLs($records)
    {
        $updatedCount = 0;
        $unchangedCount = 0;

        foreach ($records as $record) {
            $sku = $record['code'];
            $product = $record['attributes'];

            // Retrieve tile record by SKU
            $tile = DB::table('tiles')->where('sku', $sku)->first();

            if (!$tile) {
                Log::warning("No tile found for SKU: {$sku}");
                continue;
            }

            $tileVersion = $this->determineTileVersion($tile->name);
            $imageUpdates = $this->getImageUpdates($tile, $product, $tileVersion);

            if (!empty($imageUpdates)) {
                $imageUpdates['updated_at'] = now();
                DB::table('tiles')->where('sku', $sku)->update($imageUpdates);
                Log::info("Updated images for SKU: {$sku}", $imageUpdates);
                $updatedCount++;
            } else {
                $unchangedCount++;
            }
        }

        Log::info("Tiles Updated: {$updatedCount}, Unchanged: {$unchangedCount}");
    }

    /**
     * Determines the tile version from its name.
     */
    private function determineTileVersion($tileName)
    {
        // Extract the numeric suffix, if any (V1, V2, etc.)
        if (preg_match('/\bV?(\d+)\b/', $tileName, $matches)) {
            return (int) $matches[1];
        }
        return 1; // Default version if no number is found
    }

    /**
     * Determines which image should be updated based on tile version.
     */
    private function getImageUpdates($tile, $product, $tileVersion)
    {
        $updates = [];

        // Extract image URLs from API data
        $imageVar1Url = $this->extractImageURL($product, 'image_variation_1');
        $imageVar2Url = $this->extractImageURL($product, 'image_variation_2');
        $imageVar3Url = $this->extractImageURL($product, 'image_variation_3');
        $imageVar4Url = $this->extractImageURL($product, 'image_variation_4');

        switch ($tileVersion) {
            case 1:
                if ($tile->image_variation_1 !== $imageVar1Url) {
                    $updates['image_variation_1'] = $imageVar1Url;
                }
                $updates['real_file'] = null;
                $updates['image_variation_2'] = null;
                $updates['image_variation_3'] = null;
                $updates['image_variation_4'] = null;
                break;

            case 2:
                if ($tile->image_variation_2 !== $imageVar2Url) {
                    $updates['image_variation_2'] = $imageVar2Url;
                }
                $updates['real_file'] = null;
                $updates['image_variation_1'] = null;
                $updates['image_variation_3'] = null;
                $updates['image_variation_4'] = null;
                break;

            case 3:
                if ($tile->image_variation_3 !== $imageVar3Url) {
                    $updates['image_variation_3'] = $imageVar3Url;
                }
                $updates['real_file'] = null;
                $updates['image_variation_1'] = null;
                $updates['image_variation_2'] = null;
                $updates['image_variation_4'] = null;
                break;

            case 4:
                if ($tile->image_variation_4 !== $imageVar4Url) {
                    $updates['image_variation_4'] = $imageVar4Url;
                }
                $updates['real_file'] = null;
                $updates['image_variation_1'] = null;
                $updates['image_variation_2'] = null;
                $updates['image_variation_3'] = null;
                break;
        }

        return array_filter($updates, fn($value) => !is_null($value));
    }

    /**
     * Extracts image URL safely from API data.
     */
    private function extractImageURL($product, $key)
    {
        return $product[$key] ?? null;
    }
}
