<?php

namespace App\Console\Commands;

use App\Traits\ApiHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MapSkuAndUpdateImage extends Command
{
    use ApiHelper;
    protected $signature = 'sku:map-update';
    protected $description = 'Maps API SKUs with database SKUs and updates the tiles table with image names';

    public function handle()
    {
        $this->info("Fetching data from API...");

        // Increase execution time and memory limit
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');

        // Get API token
        $getToken = $this->loginAPI();
        if (!$getToken) {
            Log::error("Failed to retrieve API token.");
            return;
        }

        // API endpoint
        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

        // Query parameters
        $queryParams = http_build_query([
            's' => '2000-01-01',
            'e' => '2025-02-13',
        ]);

        // Initialize cURL request
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$apiUrl?$queryParams",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerifier(),
            CURLOPT_CONNECTTIMEOUT => 10, // Timeout after 10 seconds
            CURLOPT_TIMEOUT => 30, // Total timeout limit
            CURLOPT_HTTPHEADER => [
                'JWTAuthorization: Bearer ' . $getToken
            ],
        ]);

        // Execute API request
        $result = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        // Check for cURL errors
        if ($error) {
            Log::error("cURL Error: " . $error);
            return;
        }

        // Handle non-200 HTTP responses
        if ($httpCode !== 200) {
            Log::error("API responded with HTTP code: $httpCode");
            return;
        }

        // Decode API response
        $data = json_decode($result, true);
        if (!is_array($data)) {
            Log::error("Failed to decode JSON response: " . $result);
            return;
        }

        // Set end date
        $this->endDate = "2025-02-13";

        // Process and update database
        $updated = $this->updateOrInsertMultiple($data, $this->endDate, count($data));

        // Log Success
        Log::info("Update completed! Total updated records: " . $updated['updatedCount']);
    }

    protected function updateOrInsertMultiple($records, $endDate, $totalCount): array
    {
        $updatedCount = 0;
        $unchangedCount = 0;

        // Fetch all existing SKUs from the database to avoid multiple queries
        $existingSkus = \DB::table('tiles')->pluck('id', 'sku');

        foreach ($records as $aTile) {
            $product = $aTile['attributes'];

            // Skip records without SKU
            if (!isset($product['sku'])) {
                continue;
            }

            // Check if SKU exists in a database
            if (!isset($existingSkus[$product['sku']])) {
                \Log::info("SKU: {$product['sku']} not found in database. Skipping record.");
                continue; // Ignore this record
            }

            $imageURL = $product['image'] ?? $product['image_variation_1'];

            if ($imageURL) {
                $parsedUrl = parse_url($imageURL);
                $filePath = $parsedUrl['path']; // Get the file path without query parameters
                $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                // Check if the file type is not allowed (TIFF or PSD)
                if (in_array($fileType, ['tif', 'psd', 'tiff'])) {
                    $fileName = null; // Ignore these file types
                } else {
                    $fileName = $imageURL; // ✅ Store image URL directly
                }

                // Only update the database if $fileName is not null
                if ($fileName) {
                    \DB::table('tiles')->where('sku', $product['sku'])->update([
                        'real_file' => $fileName, // ✅ Save image URL instead of downloading
                        'updated_at' => now(),
                    ]);

                    \Log::info("Updated SKU: {$product['sku']} | Image URL: {$fileName}");
                } else {
                    \Log::warning("Skipped SKU: {$product['sku']} due to unsupported image format.");
                }
            }
            $updatedCount++;
            \Log::info("Updated SKU: {$product['sku']} | Image URL: {$imageURL}");
        }

        return ['updatedCount' => $updatedCount, 'unchangedCount' => $unchangedCount];
    }



}
