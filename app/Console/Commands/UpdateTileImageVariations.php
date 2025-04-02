<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Traits\ApiHelper;
use App\Tile;

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

    protected function updateImageURLs($records)
    {
        $updatedCount = 0;
        $unchangedCount = 0;

        foreach ($records as $apiTile) {
            $sku = $apiTile['code'];
            $product = $apiTile['attributes'];
            $productName = $product['product_name'] ?? 'Unknown Product';

            // Fetch matching tiles from DB based on SKU
            $tiles = DB::table('tiles')->where('sku', $sku)->get();

            if ($tiles->isEmpty()) {
                Log::info("Skipping tile (No DB Match): {$productName}");
                continue;
            }

            foreach ($tiles as $tile) {
                // Extract version number from DB tile name
                $tileVersion = $this->extractTileVariationNumber($tile->name, $productName);

                if ($tileVersion === null) {
                    Log::info("Skipping base tile without variation: {$tile->name}");
                    continue;
                }

                // Determine the correct image column to update
                $imageUpdates = $this->getImageUpdates($product, $tileVersion);

                if (!empty($imageUpdates)) {
                    DB::table('tiles')->where('id', $tile->id)->update($imageUpdates);
                    Log::info("Updated Tile ID: {$tile->id}, Name: {$tile->name}, Version: $tileVersion", $imageUpdates);
                    $updatedCount++;
                } else {
                    $unchangedCount++;
                }
            }
        }

        Log::info("Tiles Updated: $updatedCount, Unchanged: $unchangedCount");
    }


    private function extractTileVariationNumber($dbTileName, $apiProductName)
    {
        // Remove API product name from DB tile name
        $variationPart = trim(str_replace($apiProductName, '', $dbTileName));

        // Extract version number (expecting 02, 03, 04, etc.)
        preg_match('/\d+$/', $variationPart, $matches);

        if (!isset($matches[0])) {
            Log::info("No version found for: {$dbTileName}");
            return null;
        }

        return (int) $matches[0]; // Convert to integer (02 → 2, 03 → 3)
    }


    /**
     * Determines the correct image column for updates.
     */
    private function getImageUpdates($product, $tileVersion)
    {
        $updates = [
            'image_variation_1' => null,
            'image_variation_2' => null,
            'image_variation_3' => null,
            'image_variation_4' => null,
            'real_file' => null, // Set real_file to null only for the updated version
        ];

        // Get corresponding image URL from API
        $imageVariations = [
            2 => $product['image_variation_1'] ?? null,
            3 => $product['image_variation_2'] ?? null,
            4 => $product['image_variation_3'] ?? null,
            5 => $product['image_variation_4'] ?? null,
        ];

        // Ensure the extracted version is within the valid range
        if (!isset($imageVariations[$tileVersion])) {
            Log::info("Skipping version $tileVersion (out of range) for: {$product['product_name']}");
            return [];
        }

        // Update only the correct image_variation_X column
        $columnToUpdate = 'image_variation_' . ($tileVersion - 1);
        $updates[$columnToUpdate] = $imageVariations[$tileVersion];
        $updates['real_file'] = null; // Set real_file to null

        Log::info("Updating product: {$product['product_name']} → $columnToUpdate");

        return array_filter($updates, fn($value) => !is_null($value)); // Remove null values
    }

}
