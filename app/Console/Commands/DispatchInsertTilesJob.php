<?php

namespace App\Console\Commands;

use App\Jobs\InsertTilesFromAPI;
use Exception;
use Illuminate\Console\Command;

class DispatchInsertTilesJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:insert-tiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to insert tiles';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        $startDate = '2000-01-01'; // Set your start date
        $endDate = now()->format('Y-m-d'); // Dynamically get today's date

        // Dispatch the job
        //InsertTilesFromAPI::dispatch($startDate, $endDate);
        dispatch_sync(new InsertTilesFromAPI($startDate, $endDate));

        $this->info('Job dispatched successfully!');
    }
}
