<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use App\Traits\ApiHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetImageSize extends Command
{
    use ApiHelper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:size';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the width and height of an image from a URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Fetching data from API...");

        set_time_limit(0);
        ini_set('memory_limit', '2048M'); // Adjust the limit as needed

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
        // Decode API response
        $data = json_decode($result, true);

        if (!$data || !is_array($data)) {
            Log::warning('Invalid API response structure.');
            $this->warn("No valid products found in API response.");
            return;
        }

        // Get stored SKUs from the database to avoid duplicate processing
        $existingSkus = ProductImage::pluck('sku')->toArray();

        foreach ($data as $product) {
            $record = $product['attributes'];
            if (!isset($record['sku'])) {
                Log::info('Skipping product - Missing SKU');
                continue;
            }

            $productName = $record['product_name'] ?? 'Unknown Product';
            $sku = $record['sku'];
            if (in_array($sku, $existingSkus)) {
                Log::info("Skipping already stored SKU: {$sku}");
                continue;
            }

            if (in_array($sku, ['12345678', '1223324324'])) {
                Log::info( "Excluded SKU: {$sku}");
               continue;
            }

            // Extract expected height & width from API response
            $api_width = $record['size_wt'] ?? null;
            $api_height = $record['size_ht'] ?? null;

            // Extract images
            $images = [];

            if (!empty($record['image'])) {
                $images[] = $record['image'];
            }

            if (empty($images)) {
                Log::info("No images found for product: {$productName}");
                continue;
            }

            foreach ($images as $imageUrl) {
                // Extract file extension
                $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);

                // Ignore .psd and .tif images
                if (in_array(strtolower($extension), ['psd', 'tif', 'tiff'])) {
                    Log::info("Skipping unsupported image format: {$sku}");
                    continue;
                }

                Log::info("Fetching image size for: {$sku}");

                // Get actual image size
                $imageSize = $this->getImageSizeFromUrl($imageUrl);

                if (!$imageSize) {
                    Log::info("Skipping image due to error: $imageUrl");
                    continue;
                }

                $actualWidth = $imageSize['width'];
                $actualHeight = $imageSize['height'];

                // Compare sizes
                $status = ($api_width == $actualWidth && $api_height == $actualHeight) ? 'Match' : 'Unmatch';

                Log::info("Processed SKU: {$sku}");

                // Store in a database
                DB::table('product_images')->insert([
                    'product_name' => $productName,
                    'sku' => $sku,
                    'image_url' => $imageUrl,
                    'api_width' => (int)$api_width,
                    'api_height' => (int)$api_height,
                    'actual_width' => $actualWidth,
                    'actual_height' => $actualHeight,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }


    }

    /**
     * @param $url
     * @return array|null
     */
    private function getImageSizeFromUrl($url): ?array
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $data = curl_exec($ch);
            if ($data === false) {
                Log::error("cURL Error: " . curl_error($ch));
                return null;
            }

            curl_close($ch);

            $image = @imagecreatefromstring($data);
            if (!$image) {
                return null;
            }

            return [
                'width' => imagesx($image),
                'height' => imagesy($image)
            ];
        } catch (\Exception $e) {
            Log::error("Error fetching image size: " . $e->getMessage());
        }

        return null;
    }

}
