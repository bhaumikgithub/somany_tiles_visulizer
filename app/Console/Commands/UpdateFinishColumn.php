<?php

namespace App\Console\Commands;

use App\Traits\ApiHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateFinishColumn extends Command
{
    use ApiHelper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update only the finish column using design_finish from API while keeping other columns unchanged';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Fetching data from API...");

        ini_set('max_execution_time', 0);

        // Increase memory limit if needed
        ini_set('memory_limit', '1024M');

        $getToken = $this->loginAPI();


        // Call API and get data
        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

        $queryParams = http_build_query([
            's' => '2000-01-01',
            'e' => now()->toDateString(),
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$apiUrl?$queryParams",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerifier(),
            CURLOPT_HTTPHEADER => [
                'JWTAuthorization: Bearer ' . $getToken
            ],
        ]);

        $result = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        // Check for cURL errors
        if ($error) {
            Log::error('Unable to fetch records: ' . $error);
            return;
        }
        $this->endDate = "2025-02-18";
        // Parse the response
        $data = json_decode($result, true);
        $updated = $this->updateOrInsertMultiple($data, $this->endDate, count($data));

        // Log Success
        Log::info('Update completed! Total updated records:'.$updated['updatedCount']);
    }

    protected function updateOrInsertMultiple($records, $endDate, $totalCount): array
    {
        $updatedCount = 0;
        $unchangedCount = 0;

        foreach ($records as $aTile) {
            $product = $aTile['attributes'];

            // Skip records without SKU or design_finish
            if (!isset($product['sku'])) {
                continue;
            }

            // Check if 'design_finish' key is missing and log a warning
            if (!isset($product['design_finish'])) {
                \Log::warning("Missing key 'design_finish' for SKU: {$product['sku']}, assigning default value 'GLOSSY'.");
                $product['design_finish'] = "GLOSSY"; // Assign default value
            }

            // Check if 'brand_name' key is missing and log a warning
            if (!isset($product['brand_name'])) {
                \Log::warning("Missing key 'brand_name' for SKU: {$product['sku']}.");
                $product['brand_name'] = ""; // Assign default value
            }

            // Fetch the existing record by SKU
            $existing = \DB::table('tiles')->where('sku', $product['sku'])->first();

            if ($existing) {
                // Extract the new design_finish value
                $newFinish = $product['design_finish'];

                $expProps = json_decode($existing->expProps, true) ?? [];

                // Remove 'finish' key if it exists
                unset($expProps['finish']);

                // Add new properties
                $expProps['product code'] = $this->mapFinishType(trim($product['design_finish']));
                $expProps['finishes'] = $newFinish;
                $expProps['category'] = $this->mapCategoryType(strtolower($product['brand_name'])) ?? null;

                // Ensure 'colour' is stored only if it's not null
                if (isset($product['color'])) {
                    $expProps['colour'] = $product['color'];
                } else {
                    unset($expProps['colour']);
                }

                // Ensure 'innovation' is stored only if it's not null
                if (isset($product['innovation'])) {
                    $expProps['innovation'] = $product['innovation'];
                } else {
                    unset($expProps['innovation']);
                }

                // Check if an update is needed
                if (
                    $existing->finish !== $newFinish ||
                    ($expProps['finishes'] ?? null) !== $newFinish
                ) {
                    $finish = $this->mapFinishType(trim($product['design_finish']));
                    // Update record
                    \DB::table('tiles')->where('sku', $product['sku'])->update([
                        'finish' => $finish, // ✅ Apply Mapping
                        'design_finish' => $newFinish,
                        'expProps' => json_encode($expProps),
                        'brand' => $product['brand_name'] ?? null,
                    ]);

                    $updatedCount++;
                    \Log::info("Updated SKU: {$product['sku']} | API Finish: $newFinish | Finish: $finish");
                } else {
                    $unchangedCount++;
                }
            }
        }

        return ['updatedCount' => $updatedCount, 'unchangedCount' => $unchangedCount];
    }

    protected function loginAPI()
    {
        // JSON payload - Login cURL
        $data = [
            "username" => "admin@brndaddo.com",
            "password" => "abcd1234"
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://somany-backend.brndaddo.ai/api/v1/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
            ],
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerfier(),
            CURLOPT_POSTFIELDS => json_encode($data), // Attach the JSON-encoded data
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);

        // Check for cURL errors
        if ($response === false) {
            echo 'Error:' . curl_error($curl);
            curl_close($curl);
            return null;
        }

        // Close cURL session
        curl_close($curl);

        // Decode the JSON response
        $responseData = json_decode($response, true);

        return $responseData['token'];
    }

    protected function getSSLVerfier(): bool
    {
        // Get the value of MY_CUSTOM_VAR from the .env file
        $customVar = config('app.curl'); // 'default_value' is the fallback in case MY_CUSTOM_VAR is not set
        return !(($customVar === "localhost"));
    }
}
