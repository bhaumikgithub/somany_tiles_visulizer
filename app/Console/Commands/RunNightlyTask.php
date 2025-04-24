<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Traits\ApiHelper;
use Illuminate\Support\Facades\Log;

class RunNightlyTask extends Command
{
    use ApiHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:nightly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run API task daily at 11:59 PM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info('Nightly task executed at 11:59 PM!');

        $getToken = $this->loginAPI();
        set_time_limit(0);
        ini_set('memory_limit', '2048M'); // Adjust the limit as needed

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

        $queryParams = http_build_query([
            'limit' => 5,
            's' => '2000-01-01',
            'e' => now()->format('Y-m-d'), // Dynamically get today's date
        ]);

        $headers = [
            'JWTAuthorization: Bearer ' . $getToken,
        ];
        // Use the trait function for GET request
        $data = $this->makeGetRequest($apiUrl, $queryParams, $headers);

        if (isset($data['error'])) {
            return response()->json([
                'error' => 'Unable to fetch total records: ' . $data['error'],
            ], 500);
        }

        if (empty($data)) {
            exit(); // Stop if there are no more records
        }

        $totalRecords = count($data);
    
        // Dispatch Job to Process Records in Background
        dispatch_sync(new ProcessTilesJob($data, $endDate, $totalRecords));
    
        \Log::info('ProcessTiles Job executed successfully.');
    }
}
