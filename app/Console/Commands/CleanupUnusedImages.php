<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupUnusedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused images into the storage folder';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Starting image cleanup...");

        // Fetch all filenames from DB (Adjust column name if different)
        $dbImages = DB::table('tiles')->pluck('file')->toArray();


        // Define folders
        $folders = ['tiles/icons'];

        foreach ($folders as $folder) {
            $folderPath = storage_path("app/public/$folder");

            if (!is_dir($folderPath)) {
                $this->error("Folder not found: $folderPath");
                continue;
            }

            // Scan directory for files
            $folderFiles = array_diff(scandir($folderPath), ['.', '..']);

            foreach ($folderFiles as $file) {
                $filePath = "$folderPath/$file";

                // Ensure it's a file and not found in DB
                if (is_file($filePath) && !in_array("$folder/$file", $dbImages)) {
                    unlink($filePath);
                    Log::info("Deleted unused image: $file from $folderPath");
                }
            }
        }

        $this->info("Image cleanup completed!");
    }
}
