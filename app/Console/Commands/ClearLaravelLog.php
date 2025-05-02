<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLaravelLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the laravel.log file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logFile = storage_path('logs/laravel.log');

        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
            $this->info('laravel.log has been cleared.');
        } else {
            $this->warn('laravel.log does not exist.');
        }
    }
}
