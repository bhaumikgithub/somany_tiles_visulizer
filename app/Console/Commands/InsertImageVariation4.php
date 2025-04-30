<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Traits\ApiHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessTilesJob;

class InsertImageVariation4 extends Command
{
    use ApiHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tiles:insert-image-variation4';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command update the image_variation_4 column in database under tiles table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Fetching data from API...");

        $getToken = $this->loginAPI();

        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

        $queryParams = http_build_query([
            's' => '2000-01-01',
            'e' => now()->format('Y-m-d'), // Dynamically get today's date
        ]);

        $headers = [
            'JWTAuthorization: Bearer ' . $getToken,
        ];
        // Use the trait function for GET request
        $apiTiles = $this->makeGetRequest($apiUrl, $queryParams, $headers);
        // Check for cURL errors
        if (isset($apiTiles['error'])) {
            return response()->json([
                'error' => 'Unable to fetch total records: ' . $data['error'],
            ], 500);
        }

        $totalUpdated = 0;

        foreach ($apiTiles as $tile) {
            $product = $tile['attributes'];
            if (isset($tile['code']) && in_array($tile['code'], ['12345678', '1223324324', '1234','TESTSKU'])) {
                Log::info("Key: {$tile['code']}");
                continue;
            }

            if (isset($product['deletion']) && !in_array($product['deletion'], ["RUNNING", "SLOW MOVING"])) {
                continue;
            }


            if (!empty($product['image_variation_4'])) {
                $expectedName = $product['product_name'] . ' 05';
                $sku = $product['sku'];
                $url = $product['image_variation_4'];

                // Fetch matching record
                $dbTiles = DB::table('tiles')->where('sku', $sku)->where('name', $expectedName)->get();

                foreach ($dbTiles as $dbTile) {
                    DB::table('tiles')
                        ->where('id', $dbTile->id)
                        ->update(['image_variation_4' => $url]);
    
                    Log::info("Updated image_variation_4 for tile ID: {$dbTile->id} | SKU : {$sku}");
                    $totalUpdated++;
                }
            }
        }

        // Final log message
        Log::info("✅ Process completed. Total tiles updated: {$totalUpdated}");
    }
}
