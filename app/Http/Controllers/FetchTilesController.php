<?php

namespace App\Http\Controllers;

use App\Company;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage; // Import Intervention Image

class FetchTilesController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $api_details = Company::select('last_fetch_date_from_api','fetch_products_count')->first();
        return view('fetch_tiles_index',compact('api_details'));
    }

    public function fetchData(Request $request): JsonResponse
    {
        $getToken = $this->loginAPI();

        // login cURL ends here

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get tiles data
        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete?limit=20";
        $queryParams = http_build_query([
            's' => $startDate,
            'e' => $endDate,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$apiUrl?$queryParams",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerfier(),
            CURLOPT_HTTPHEADER => [
                'JWTAuthorization: Bearer '.$getToken // Replace with actual API key if required
            ],
        ]);

        $result = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        // Check for cURL errors
        if ($error) {
            return response()->json([
                'error' => 'Unable to fetch total records: ' . $error,
            ], 500);
        }

        // Parse the response
        $data = json_decode($result, true);
        // Now you can process the data as needed (e.g., inserting or updating the database)
        $total_records = $this->updateOrInsertMultiple($data,$endDate,count($data));
        $updated_message = Carbon::parse($endDate)->format('d M Y') ." " ."( ". count($data).' records has been fetch '.")";
        return response()->json([
            'success' => true,
            'message' => 'Data processed successfully.',
            'total_records' => $total_records['count'],
            'new_date' => $endDate,
            'updated_message' => $updated_message,
            'insertedCount' => count($data),
            'updatedCount' => $total_records['updatedCount'],
            'unchangedCount' => $total_records['unchangedCount'],
        ]);
    }

    private function loginAPI()
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

    public function updateOrInsertMultiple($records,$endDate,$totalCount): array
    {
        $count = 0;  // Variable to track the number of processed records
        $insertedCount = 0; // Track new insertions
        $updatedCount = 0; // Track updates
        $unchangedCount = 0; // Track unchanged records

        foreach ($records as $aTile) {
            $product = $aTile['attributes'];
            $creation_time = Carbon::parse($aTile['creation_time'])->format('Y-m-d H:i:s');
            // Check if SKU is '12345', and skip this iteration if true
            if ($product['sku'] == '12345678') {
                continue;
            }

            // If a deletion flag is set, remove the record
            if (isset($product['deletion']) && $product['deletion'] !== "RUNNING") {
                \DB::table('companies')->update(['enabled' => 0]);
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

    protected function prepareTileData(array $product,$creation_time): array
    {
        $imageURL = ($product['image'] ) ?? $product['image_variation_1'];
        return [
            'name' => $product['product_name'] ?? null,
            'shape' => $product['shape'] ?? 'square',
            'width' => intval($product['size_wt']) ?? 0,
            'height' => intval($product['size_ht']) ?? 0,
            'size' => $product['size'] ?? null,
            'surface' => strtolower($product['surface']) ?? null,
            'finish' => $product['design_finish'] ?? null,
            'file' => $this->fetchAndSaveImage($imageURL),
            'image_variation_1' => $product['image_variation_1'] ?? null,
            'image_variation_2' => $product['image_variation_2'] ?? null,
            'grout' => $product['grout'] ?? null,
            'url' => $product['url'] ?? null,
            'price' => $product['price'] ?? null,
            'expProps' => json_encode([
                'thickness' => $product['thickness'] ?? null,
                'product code' => $product['design_finish'] ?? null,
            ]), // Combined JSON field
            'rotoPrintSetName' => $product['vertical'] ?? null,
            'access_level' => $product['access_level'] ?? null,
            'sku' => $product['sku'] ?? null,
            'application_room_area' => $product['application_room_area'] ?? null,
            'brand' => $product['brand'] ?? null,
            'sub_brand_1' => $product['sub_brand'] ?? null,
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

    protected function fetchAndSaveImage($imageURL): JsonResponse|string
    {
        // Get tiles data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->getSSLVerfier()); // Ensure SSL verification is enabled

        $imageContent = curl_exec($ch);
        // Check if cURL execution was successful
        if ($imageContent === false) {
            return response()->json([
                'error' => 'Failed to fetch image using cURL',
                'message' => curl_error($ch),
            ], 406);
        }
        // Get the content type of the image (optional: you can validate the type)
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        curl_close($ch);

        $image_name = uniqid() . '.jpg';// Unique ID to avoid overwrite

        // Generate a unique filename for the image
        $fileName = 'tiles/' . $image_name;// Unique ID to avoid overwrite
        $iconFilePath = 'tiles/icons/' . $image_name;

        // Call the model method with parameters
        // Resize the image to 100x100 and store it in the 'icons' folder
        $image = InterventionImage::make($imageContent)->resize(100, 100);  // Resize to 100x100

        // Store the resized image in the 'icons' folder inside the 'public' storage folder
        Storage::disk('public')->put($iconFilePath, $image->encode());  // Save the image to storage

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
}
