<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Traits\ApiHelper;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessTilesJob;
use Illuminate\Support\Facades\Mail;

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
        try {
            \Log::info('Nightly task executed at 11:59 PM!');

            $getToken = $this->loginAPI();
            set_time_limit(0);
            ini_set('memory_limit', '2048M'); // Adjust the limit as needed

            $endDate = now()->format('Y-m-d');

            $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

            $queryParams = http_build_query([
                's' => '2000-01-01',
                'e' => $endDate, // Dynamically get today's date
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
        } catch (\Throwable $e) {
            \Log::error('Nightly cron job failed: ' . $e->getMessage());
            $this->sendCronFailureEmail($e);
        }
    }

    private function sendCronFailureEmail(\Throwable $e): void
    {
        $to = ['chandan.parihar@somanyceramics.com','Sonu@somanyceramics.com']; // Change to actual recipients    
        
        $subject = '❌ CRON JOB FAILED: Tile Sync Nightly Task';

        $body = "The nightly cron job failed.\n\n";
        $body .= "Error Message:\n" . $e->getMessage() . "\n\n";

        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $logLines = array_slice(file($logPath), -30);
            $body .= "\nLast 30 Log Lines:\n" . implode("", $logLines);
        }

        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)->bcc('tracingidea@gmail.com')->subject($subject);
        });
    }
}
