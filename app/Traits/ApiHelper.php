<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
    public function makeGetRequest($url, $queryParams,$headers = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$url?$queryParams",
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
     * Prepare headers for API requests.
     */
    private function prepareHeaders($extraHeaders = []): array
    {
        // Ensure $extraHeaders is always an array
        if (!is_array($extraHeaders)) {
            $extraHeaders = [$extraHeaders];
        }

        $defaultHeaders = [
            "Content-Type: application/json",
        ];

        return array_merge($defaultHeaders, $extraHeaders);
    }


    /**
     * @throws Exception
     */
    protected function prepareTileData(array $product, $creation_time , $imageFileName): ?array
    {
        // Track missing fields
        $missingFields = [];

        // Check if 'design_finish' key is missing
        if (!isset($product['design_finish'])) {
            \Log::warning("Missing key 'design_finish' for SKU: " . ($product['sku'] ?? 'Unknown'));
            $missingFields[] = "design_finish";
            $product['design_finish'] = "GLOSSY"; // Set default value
        }

        if (!isset($product['brand_name'])) {
            \Log::warning("Missing key 'brand_name' for SKU: " . ($product['sku'] ?? 'Unknown'));
            $missingFields[] = "brand_name";
            $product['brand_name'] = "";
        }

        // If inserting a new tile and required fields are missing, skip the insert
        if (!empty($missingFields)) {
            return [
                'skip' => true, // Special flag to signal skipping insert
                'reason' => "Missing required field(s): " . implode(" & ", $missingFields)
            ];
        }

        $surface = strtolower($product['surface'] ?? '');

        // Prepare an array but remove null values
        $expPropsArray = $this->extraProps($product);

        return [
            'name' => $product['product_name'] ?? null,
            'shape' => $this->getShapeFromWidthHeight($product['size_wt'], $product['size_ht']),
            'width' => intval($product['size_wt'] ?? 0),
            'height' => intval($product['size_ht'] ?? 0),
            'size' => $product['size'] ?? null,
            'surface' => $surface,
            'finish' => $this->mapFinishType($product['design_finish']),
            'design_finish' => $product['design_finish'] ?? null,
            'file' => $imageFileName,
            'real_file' => $product['image'] ?? null,
            'image_variation_1' => $product['image_variation_1'] ?? null,
            'image_variation_2' => $product['image_variation_2'] ?? null,
            'image_variation_3' => $product['image_variation_3'] ?? null,
            'grout' => (in_array($surface, ["wall", "floor"])) ? 1 : null,
            'url' => $product['url'] ?? null,
            'price' => $product['price'] ?? null,
            'expProps' => json_encode($expPropsArray),
            'rotoPrintSetName' => $product['rotoPrintSetName'] ?? null,
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
            'from_api' => '1'
        ];
    }


    protected function prepareTileUpdateData(array $product, $creation_time,$excludeName = "false"){
        $surface = strtolower($product['surface'] ?? '');
        // Prepare an array but remove null values
        $expPropsArray = $this->extraProps($product);
        $data = [
            'shape' => $this->getShapeFromWidthHeight($product['size_wt'], $product['size_ht']),
            'width' => intval($product['size_wt'] ?? 0),
            'height' => intval($product['size_ht'] ?? 0),
            'size' => $product['size'] ?? null,
            'finish' => $this->mapFinishType($product['design_finish']),
            'design_finish' => $product['design_finish'] ?? null,
            'image_variation_1' => $product['image_variation_1'] ?? null,
            'image_variation_2' => $product['image_variation_2'] ?? null,
            'image_variation_3' => $product['image_variation_3'] ?? null,
            'image_variation_4' => $product['image_variation_4'] ?? null,
            'url' => $product['url'] ?? null,
            'price' => $product['price'] ?? null,
            'expProps' => json_encode($expPropsArray),
            'rotoPrintSetName' => $product['rotoPrintSetName'] ?? null,
            'access_level' => $product['access_level'] ?? null,
            'sku' => $product['sku'] ?? null,
            'application_room_area' => $product['application_room_area'] ?? null,
            'brand' => $product['brand_name'] ?? null,
            'sub_brand_1' => $product['sub_brand_1'] ?? null,
            'innovation' => $product['innovation'] ?? null,
            'sub_brand_2' => $product['sub_brand_2'] ?? null,
            'color' => $product['color'] ?? null,
            'poc' => $product['poc'] ?? null,
            'thickness' => number_format((float) $product['thickness'], 2, '.', '') ?? null,
            'tiles_per_carton' => (int) $product['tiles_per_carton'] ?? null,
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
            'from_api' => '1'
        ];
        if ($excludeName !== "true") {
            $data['name'] = $product['product_name'] ?? null;
        }
    
        return $data;

    }

    /**
     * @param $width
     * @param $height
     * @return string
     */
    private function getShapeFromWidthHeight($width, $height): string
    {
        // Ensure numeric comparison
        if ((int)$width === (int)$height) {
            return "square";
        }
        
        return "rectangle";
    }

    /**
     * @param $product
     * @return mixed
     */
    private function extraProps($product): mixed
    {
        return array_filter([
            'thickness' => $product['thickness'] ?? null,
            'product code' => $this->mapFinishType(trim($product['design_finish'])) ?? null,
            'colour' => $product['color'] ?? null,
            'finishes' => $product['design_finish'] ?? null,
            'category' => $this->mapCategoryType(strtolower($product['brand_name'])) ?? null,
            'innovation' => $product['innovation'] ?? null,
        ], function ($value) {
            return $value !== null; //Remove keys with null values
        });
    }

    /**
     * @param $designFinish
     * @return string
     */
    private function mapFinishType($designFinish): string
    {
        if (!$designFinish) {
            return '';
        }
    
        // Normalize input: trim spaces, convert to uppercase, and replace multiple spaces with a single space
        $normalizedFinish = preg_replace('/\s+/', ' ', strtoupper(trim($designFinish)));
    
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
            'LAPATO' => 'matt',
            'POLISHED CARVING' => 'glossy',
            'MATT CARVING' => 'matt'
        ];

        // Return the mapped value or the normalized input if not found
        return $mapping[$normalizedFinish] ?? strtolower($normalizedFinish);
    }

    private function mapCategoryType($brand_name): string
    {
        $mapping = [
            'coverstone' => 'Large Format Slab - Coverstone',
            'regalia collection' => 'Large Format Tiles - Regalia Collection',
            'porto collection' => 'Large Format Tiles - Porto Collection',
            'sedimento collection' => 'Large Format Tiles - Sedimento Collection',
            'colorato collection' => 'Large Format Tiles - Colorato Collection',
            'ceramica' => 'Ceramic - Ceramica',
            'duragres' => 'Glazed Vitrified Tiles - Duragres',
            'vitro' => 'Polished Vitrified Tiles - Vitro',
            'durastone' => 'Heavy Duty Vitrified Tiles - Durastone',
            'italmarmi' => 'Subway Tiles - Italmarmi',
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->getSSLVerifier()); // Ensure SSL verification is enabled

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

    /**
     * Get SSL Verifier (can be modified based on environment needs).
     */
    protected function getSSLVerifier(): bool
    {
        // Get the value of MY_CUSTOM_VAR from the .env file
        $customVar = config('app.curl'); // 'default_value' is the fallback in case MY_CUSTOM_VAR is not set
        return !(($customVar === "localhost"));
    }

    protected function determineSurfaceValues($product): array
    {
        $surfaces = [];

        // Extract surfaces from "application"
        if (isset($product['application'])) {
            $applications = explode(' & ', strtolower($product['application']));
            foreach ($applications as $app) {
                $app = trim($app);
                // Split by '/' if needed (e.g., "Counter Top/Wall/Floor")
                $subApplications = explode('/', $app);
                foreach ($subApplications as $subApp) {
                    $subApp = trim($subApp);
                    // Specifically treat "Counter Top" as counter
                    if ($subApp === 'counter top') {
                        $surfaces[] = 'counter'; // Treat "Counter Top" as "counter"
                    } elseif (in_array($subApp, ['wall', 'floor', 'counter'])) {
                        $surfaces[] = $subApp;
                    }
                }
            }
        }

        // ✅ Check for Counter (Vanity, Kitchen Cabinet, Tabletop)
        $keywords = ['vanity', 'kitchen cabinet', 'tabletop','Bathroom Platform', 'Kitchen Platform'];
        if (isset($product['application_room_area'])) {
            $applicationRoomArea = strtolower($product['application_room_area']);

            foreach ($keywords as $keyword) {
                if (str_contains($applicationRoomArea, $keyword)) {
                    $surfaces[] = "counter"; //Add counter if keyword is found
                    break;
                }
            }
        }

        // ✅ Ensure unique values & default to "wall" if no surfaces found
        return !empty($surfaces) ? array_unique($surfaces) : ["wall"];
    }

    /**
     * @param $url
     * @return bool
     */
    private function isInvalidFormat($url): bool
    {
        $invalidFormats = ['tiff', 'tif', 'psd'];
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $invalidFormats);
    }



}
