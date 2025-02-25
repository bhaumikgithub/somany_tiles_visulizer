<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateTileShape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tiles:update-shape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tile shape as square or rectangle based on width and height';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Update tiles where width = height to 'square'
        $squareCount = DB::table('tiles')
            ->whereColumn('width', '=', 'height')
            ->where('shape', '!=', 'square') // Avoid unnecessary updates
            ->update(['shape' => 'square']);

        // Update tiles where width != height to 'rectangle'
        $rectangleCount = DB::table('tiles')
            ->whereColumn('width', '!=', 'height')
            ->where('shape', '!=', 'rectangle') // Avoid unnecessary updates
            ->update(['shape' => 'rectangle']);

        // Output results
        $this->info("Updated $squareCount tiles to 'square'");
        $this->info("Updated $rectangleCount tiles to 'rectangle'");
    }
}
