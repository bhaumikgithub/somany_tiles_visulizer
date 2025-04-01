<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateTilesFromAPI;

class DispatchUpdateTilesJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:update-tiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to update tiles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = '2000-01-01'; // Set your start date
        $endDate = now()->toDateString(); // Set end date to today's date

        //InsertTilesFromAPI::dispatch($startDate, $endDate);
        dispatch_sync(new UpdateTilesFromAPI($startDate, $endDate));

        $this->info('Job dispatched successfully!');
    }
}
