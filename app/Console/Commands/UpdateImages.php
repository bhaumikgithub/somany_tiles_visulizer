<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class UpdateImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command update the images from api as well delete the unnecessary images from folder and if same image then store same name in db';

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
            'e' => '2025-02-26',
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
        $this->endDate = "2025-02-26";
        // Parse the response
        $data = json_decode($result, true);
        $updated = $this->updateOrInsertMultiple($data, $this->endDate, count($data));

        // Log Success
        Log::info('Update completed! Total updated records:'.$updated['updatedCount']);
    }

    protected function updateOrInsertMultiple($records, $endDate, $totalCount): array
    {
        $updatedCount = 0;
        $processedCount = 0;

        Log::info('Starting tile processing. Total records: ' . $totalCount);

        foreach ($records as $aTile) {
            $product = $aTile['attributes'];
            $sku = $product['sku'] ?? 'Unknown';

            if (in_array($product['sku'], ['12345678', '1223324324'])) {
                continue; // Skip this record
            }

            $imageURL = $product['image'] ?? $product['image_variation_1'];
            $processedCount++;

            try {
                if (!$imageURL) {
                    Log::info("Skipping SKU {$sku} - No image URL found.");
                    continue; // Skip records without an image URL
                }

                // Extract and validate file extension
                $extension = strtolower(pathinfo(strtok($imageURL, '?'), PATHINFO_EXTENSION));

                // Skip PSD and TIFF files
                if (in_array($extension, ['tiff', 'tif', 'psd'])) {
                    Log::info("Skipping SKU {$sku} - Unsupported file type: {$extension}");
                    continue;
                }

                // Fetch existing tile from DB
                $existingTile = DB::table('tiles')->where('sku', $sku)->first();
                $imageFileName = null;

                if ($existingTile && $existingTile->real_file === $imageURL) {
                    // If the image URL is the same, reuse the existing file
                    $imageFileName = $existingTile->file;
                } else {
                    // Fetch and save the new image
                    $imageFileName = $this->fetchAndSaveImage($imageURL);

                    // If image download failed, skip the record
                    if (!$imageFileName) {
                        Log::warning("Failed to fetch image for SKU: {$sku}, URL: {$imageURL}");
                        continue;
                    }
                }

                // Update ONLY `file` and `real_file` columns in DB
                DB::table('tiles')->where('sku', $sku)->update([
                    'file' => $imageFileName,
                    'real_file' => $imageURL,
                    'updated_at' => now(),
                ]);

                $updatedCount++;
                Log::info("Updated image for SKU: {$sku}");

            } catch (\Exception $e) {
                Log::error("Error processing SKU: {$sku} - " . $e->getMessage());
            }
        }

        Log::info("Tile processing completed successfully. Images updated: {$updatedCount}");

        return [
            'total_processed' => $processedCount,
            'updatedCount' => $updatedCount
        ];
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

    protected function fetchAndSaveImage($imageURL): JsonResponse|string
    {
        // Get tiles data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->getSSLVerfier()); // Ensure SSL verification is enabled

        $imageContent = curl_exec($ch);

        // Get the content type of the image (optional: you can validate the type)
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        curl_close($ch);

        if (!$imageContent || !str_starts_with($contentType, 'image/')) {
            return response()->json([
                'error' => 'Invalid image content',
                'message' => 'Fetched content is not a valid image.',
            ], 406);
        }
        if (stristr($contentType, 'tif')) {
            $tempTiffPath = storage_path('temp_image.tiff');
            file_put_contents($tempTiffPath, $imageContent);

            // Convert TIFF to JPG using ImageMagick
            $tempJpgPath = storage_path('temp_image.jpg');
            exec("convert $tempTiffPath $tempJpgPath");

            if (file_exists($tempJpgPath)) {
                $imageContent = file_get_contents($tempJpgPath);
                unlink($tempTiffPath);
                unlink($tempJpgPath);
            } else {
                return response()->json([
                    'error' => 'Failed to convert TIFF to JPG.',
                ], 406);
            }
        }

        $image_name = uniqid() . '.jpg';// Unique ID to avoid overwrite

        // Generate a unique filename for the image
        $fileName = 'tiles/' . $image_name;// Unique ID to avoid overwrite
        $iconFilePath = 'tiles/icons/' . $image_name;

        // Resize the image to 100x100 and store it in the 'icons' folder
        $image = InterventionImage::make($imageContent)->resize(100, 100);  // Resize to 100x100

        // Store the resized image in the 'icons' folder inside the 'public' storage folder
        Storage::disk('public')->put($iconFilePath, $image->encode());

        // Store the image content in the public disk (public/tiles folder in storage)
        Storage::disk('public')->put($fileName, $imageContent);
        return $fileName;
    }

}
