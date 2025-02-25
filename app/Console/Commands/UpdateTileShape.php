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
        $tiles = DB::table('tiles')->get();
        foreach ($tiles as $tile) {
            $shape = ($tile->width == $tile->height) ? 'square' : 'rectangle';

            if ($tile->shape !== $shape) { // Update only if different
                DB::table('tiles')->update(['shape' => $shape]);
                //$this->info("Updated Tile ID {$tile->id} to {$shape}");
            }
        }

        $this->info('Tile shapes updated successfully.');
    }
}
