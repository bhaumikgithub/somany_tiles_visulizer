<?php

namespace App\Console\Commands;

use App\Jobs\InsertTilesFromAPI;
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
     */
    public function handle()
    {
        $startDate = '2000-01-01'; // Set your start date
        $endDate = '2025-02-04';   // Set your end date

        // Dispatch the job
        InsertTilesFromAPI::dispatch($startDate, $endDate);

        $this->info('Job dispatched successfully!');
    }
}
