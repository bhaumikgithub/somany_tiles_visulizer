<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateSurfaceCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:surface-counter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update surface to counter for matching application_room_area and disable duplicate SKUs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Updating surfaces based on application_room_area...");

        $updatedCount = 0;
        $disabledCount = 0;

        // Fetch all tiles from the database
        $tiles = DB::table('tiles')->select('id', 'sku', 'application_room_area', 'surface', 'enabled')->get();

        // Define keywords to check in application_room_area
        $keywords = ['vanity', 'kitchen cabinet', 'tabletop'];

        // Group tiles by SKU to find duplicates
        $tilesBySku = $tiles->groupBy('sku');

        foreach ($tilesBySku as $sku => $skuTiles) {
            $matchedTiles = collect(); // Collection for tiles that match the keywords

            foreach ($skuTiles as $tile) {
                if (!$tile->application_room_area) {
                    continue; // Skip if application_room_area is NULL
                }

                // Convert application_room_area to lowercase for case-insensitive match
                $applicationRoomArea = strtolower($tile->application_room_area);

                foreach ($keywords as $keyword) {
                    if (str_contains($applicationRoomArea, $keyword)) {
                        $matchedTiles->push($tile);
                        break;
                    }
                }
            }

            if ($matchedTiles->count() > 0) {
                // Select the first tile to be updated to "counter" and enabled
                $tileToUpdate = $matchedTiles->first();

                DB::table('tiles')
                    ->where('id', $tileToUpdate->id)
                    ->update([
                        'surface' => 'counter',
                        'enabled' => 1,
                        'updated_at' => now(),
                    ]);

                $updatedCount++;
                Log::info("Updated SKU: $sku | ID: {$tileToUpdate->id} | Surface changed to 'counter'");
                $this->info("Updated SKU: $sku | ID: {$tileToUpdate->id} | Surface changed to 'counter'");

                // Disable all other entries for this SKU
                $tilesToDisable = $matchedTiles->where('id', '!=', $tileToUpdate->id);

                foreach ($tilesToDisable as $tileToDisable) {
                    DB::table('tiles')
                        ->where('id', $tileToDisable->id)
                        ->update([
                            'enabled' => 0,
                            'deleted_at' => now(), // Soft delete the disabled entry
                            'updated_at' => now(),
                        ]);

                    $disabledCount++;
                    Log::info("Disabled duplicate SKU: $sku | ID: {$tileToDisable->id}");
                    $this->info("Disabled duplicate SKU: $sku | ID: {$tileToDisable->id}");
                }
            }
        }

        $this->info("Update completed! Updated: $updatedCount | Disabled: $disabledCount");
        Log::info("Update completed! Updated: $updatedCount | Disabled: $disabledCount");
    }
}
