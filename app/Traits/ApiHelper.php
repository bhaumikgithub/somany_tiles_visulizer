<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

trait ApiHelper
{
    /**
     * Perform login API request and return the token.
     */
    public function loginAPI()
    {
        $data = [
            "username" => "admin@brndaddo.com",
            "password" => "abcd1234"
        ];

        $response = $this->makePostRequest("https://somany-backend.brndaddo.ai/api/v1/login", $data);

        return $response['token'] ?? null;
    }

    /**
     * Perform a GET request.
     */
    public function makeGetRequest($url, $headers = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->prepareHeaders($headers),
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerifier(),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return $error ? ['error' => $error] : json_decode($response, true);
    }

    /**
     * Perform a POST request.
     */
    public function makePostRequest($url, $data, $headers = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $this->prepareHeaders($headers),
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerifier(),
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return $error ? ['error' => $error] : json_decode($response, true);
    }

    /**
     * Get SSL Verifier (can be modified based on environment needs).
     */
    private function getSSLVerifier(): bool
    {
        // Get the value of MY_CUSTOM_VAR from the .env file
        $customVar = config('app.curl'); // 'default_value' is the fallback in case MY_CUSTOM_VAR is not set
        return !(($customVar === "localhost"));
    }

    /**
     * Prepare headers for API requests.
     */
    private function prepareHeaders($extraHeaders = []): array
    {
        $defaultHeaders = [
            "Content-Type: application/json",
        ];

        return array_merge($defaultHeaders, $extraHeaders);
    }


    protected function prepareTileData(array $product,$creation_time): array
    {
        // Check if 'design_finish' key is missing and log a warning
        if (!isset($product['design_finish'])) {
            \Log::warning("Missing key 'design_finish' for SKU: " . ($product['sku'] ?? 'Unknown'));
            $product['design_finish'] = "GLOSSY";
        }

        if( !isset($product['brand_name'])){
            \Log::warning("Missing key 'brand_name' for SKU: " . ($product['sku'] ?? 'Unknown'));
            $product['brand_name'] = "Duragres";
        }

        $surface = strtolower($product['surface']);
        $imageURL = ($product['image'] ) ?? $product['image_variation_1'];

        $parsedUrl = parse_url($imageURL);
        $filePath = $parsedUrl['path']; // This gets the file path without query parameters
        // Get the file extension (e.g., tiff, psd, jpg)
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        // Check if the file is TIFF or PSD and set the image to null
        if (in_array($fileType, ['tif', 'psd' ,'tiff'])) {
            $fileName = null;
        } else {
            $fileName = $this->fetchAndSaveImage($imageURL);
        }

        return [
            'name' => $product['product_name'] ?? null,
            'shape' => $product['shape'] ?? 'square',
            'width' => intval($product['size_wt']) ?? 0,
            'height' => intval($product['size_ht']) ?? 0,
            'size' => $product['size'] ?? null,
            'surface' => $surface ?? null,
            'finish' => $product['design_finish'] ?? null,
            'file' => $this->fetchAndSaveImage($imageURL),
            'image_variation_1' => $product['image_variation_1'] ?? null,
            'image_variation_2' => $product['image_variation_2'] ?? null,
            'grout' => ( $surface === "wall" || $surface === "floor" ) ? 1 : null,
            'url' => $product['url'] ?? null,
            'price' => $product['price'] ?? null,
            'expProps' => json_encode([
                'thickness' => $product['thickness'] ?? null,
                'product code' => $product['design_finish'] ?? null,
                'colour' => $product['color'] ?? null,
                'category' => $this->mapCategoryType(strtolower($product['brand_name'])) ?? null,
            ]), // Combined JSON field
            'rotoPrintSetName' => str_replace(" FP", "", $product['product_name']) ?? null,
            'access_level' => $product['access_level'] ?? null,
            'sku' => $product['sku'] ?? null,
            'application_room_area' => $product['application_room_area'] ?? null,
            'brand' => $product['brand_name'] ?? null,
            'sub_brand_1' => $product['sub_brand_1'] ?? null,
            'innovation' => $product['innovation'] ?? null,
            'sub_brand_2' => $product['sub_brand_2'] ?? null,
            'color' => $product['color'] ?? null,
            'poc' => $product['poc'] ?? null,
            'thickness' => $product['thickness'] ?? null,
            'tiles_per_carton' => $product['tiles_per_carton'] ?? null,
            'avg_wt_per_carton' => $product['avg_wt_per_carton'] ?? null,
            'coverage_sq_ft' => $product['coverage_sq_ft'] ?? null,
            'coverage_sq_mt' => $product['coverage_sq_mt'] ?? null,
            'pattern' => $product['pattern'] ?? null,
            'plant' => $product['plant'] ?? null,
            'service_geography' => $product['service_geography'] ?? null,
            'unit_of_production' => $product['unit_of_production'] ?? null,
            'yes_no' => $product['yes_no'] ?? null,
            'record_creation_time' => $creation_time,
            'deletion' => $product['deletion'] ?? null,
            'from_api' => '1',
            'updated_at' => now(),
        ];
    }


    private function mapFinishType($designFinish): string
    {
        $mapping = [
            'LUCIDO' => 'glossy',
            'FULL POLISHED' => 'glossy',
            'HIGH GLOSS FP' => 'glossy',
            'NANO' => 'glossy',
            'NANO FP' => 'glossy',
            'RUSTIC' => 'matt',
            'RUSTIC CARVING' => 'matt',
            'STONE' => 'matt',
            'WOOD' => 'glossy',
            'MATT' => 'matt',
            'GLOSSY' => 'glossy',
            'DAZZLE' => 'matt',
            'Metallic'=>'matt',
            'SUGAR HOME' => 'matt',
            'SATIN MATT' => 'matt',
            'SEMI GLOSSY' => 'matt',
            'MATT ENGRAVE' => 'matt',
            'PRM FULL POLISHED' => 'glossy',
            'ROTTO' => 'matt',
            'Lapato' => 'matt',
        ];
        return $mapping[$designFinish] ?? $designFinish; // Default to original value if not in mapping
    }

    private function mapCategoryType($brand_name): string
    {
        $mapping = [
            'Coverstone' => 'Large Format Slab',
            'Regalia Collection' => 'Large Format Tiles',
            'Porto Collection' => 'Large Format Tiles',
            'Sedimento Collection' => 'Large Format Tiles',
            'Colorato Collection' => 'Large Format Tiles',
            'Ceramica' => 'Ceramic',
            'Duragres' => ' Glazed Vitrified Tiles',
            'Vitro' => 'Polished Vitrified Tiles',
            'Durastone' => 'Heavy Duty Vitrified Tiles',
            'Italmarmi' => 'Subway Tiles',
        ];

        return $mapping[$brand_name] ?? $brand_name; // Default to original value if not in mapping
    }


    /**
     * @throws Exception
     */

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
