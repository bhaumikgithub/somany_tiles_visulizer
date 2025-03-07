<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreApiRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:api-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch records from an API and store them in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Fetching data from API...");

        $getToken = $this->loginAPI();

        // Call API and get data
        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

        $queryParams = http_build_query([
            's' => '2000-01-01',
            'e' => now()->format('Y-m-d'), // Dynamically get today's date
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$apiUrl?$queryParams",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerfier(),
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
        // Decode API response
        $data = json_decode($result, true);

        if (!$data || !is_array($data)) {
            Log::warning('Invalid API response structure.');
            $this->warn("No valid products found in API response.");
            return;
        }

        // Process each product
        foreach ($data as $product) {
            if (!isset($product['attributes']['sku'])) {
                Log::info('Skipping product - Missing SKU');
                continue;
            }

            $sku = $product['attributes']['sku'];
            $jsonData = json_encode($product, JSON_PRETTY_PRINT);

            // Check if the SKU exists before updating
            $exists = DB::table('tiles')->where('sku', $sku)->exists();

            if ($exists) {
                DB::table('tiles')
                    ->where('sku', $sku)
                    ->update([
                        'api_json' => $jsonData
                    ]);
                Log::info("Updated record with SKU: {$sku}");
            } else {
                Log::info("Skipping SKU: {$sku} (does not exist in DB)");
            }
        }
        $this->info('All API records have been successfully stored.');
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
