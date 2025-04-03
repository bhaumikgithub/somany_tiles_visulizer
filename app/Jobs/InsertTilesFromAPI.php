<?php

namespace App\Jobs;

use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class InsertTilesFromAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        // Parse the response
        $data = json_decode($result, true);
        $this->updateOrInsertMultiple($data, $this->endDate, count($data));

        // Log Success
        Log::info('Tiles data inserted successfully from API.');
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

    protected function updateOrInsertMultiple($records,$endDate,$totalCount): array
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
            if (isset($product['deletion']) ){
                if( $product['deletion'] !== "RUNNING" && $product['deletion'] !== "SLOW MOVING")
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

    /**
     * @throws Exception
     */
    protected function prepareTileData(array $product, $creation_time): array
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
            'finish' => $this->mapFinishType($product['design_finish']), // ✅ Apply Mapping
            'design_finish' => $product['design_finish'] ?? null,
            'file' => $fileName,
            'image_variation_1' => $product['image_variation_1'] ?? null,
            'image_variation_2' => $product['image_variation_2'] ?? null,
            'image_variation_3' => $product['image_variation_3'] ?? null,
            'grout' => ( $surface === "wall" || $surface === "floor" ) ? 1 : null,
            'url' => $product['url'] ?? null,
            'price' => $product['price'] ?? null,
            'expProps' => json_encode([
                'thickness' => $product['thickness'] ?? null,
                'product code' => $this->mapFinishType($product['design_finish']) ?? null,
                'colour' => $product['color'] ?? null,
                'category' => $this->mapCategoryType($product['brand_name']) ?? null,
                'innovation' => $product['innovation'] ?? null,
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
            '360_url' => $product['360_url'] ?? null,
            'from_api' => '1',
            'updated_at' => now(),
        ];
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

    protected function getSSLVerfier(): bool
    {
        // Get the value of MY_CUSTOM_VAR from the .env file
        $customVar = config('app.curl'); // 'default_value' is the fallback in case MY_CUSTOM_VAR is not set
        return !(($customVar === "localhost"));
    }

    protected function mapFinishType($designFinish): string
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

    protected function mapCategoryType($brand_name): string
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
}
