<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImageSizeRefactor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image-size-refactor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This commands change orientation based on image size';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Updating tile table based on incorrect landscape orientations...");

        // Fetch incorrect landscape records from product_images
        $incorrectTiles = \DB::table('product_images')
            ->where('status', 'INCORRECT')
            ->where('orientation', 'landscape')
            ->get();

        foreach ($incorrectTiles as $tile) {
            $sku = $tile->sku;
            $newWidth = $tile->api_height;  // Swap width & height
            $newHeight = $tile->api_width;
            $newSize = "{$newWidth} x {$newHeight} MM";

            Log::info("Existing SKU: {$sku} | Width: {$tile->api_width} | Height: {$tile->api_height}");

//            // Update tile table
            \DB::table('tiles')
                ->where('sku', $sku)
                ->update([
                    'width' => $newWidth,
                    'height' => $newHeight,
                    'size' => $newSize,
                    'updated_at' => now(),
                ]);

            Log::info("Updated SKU: {$sku}  | Width: {$newWidth} | Height: {$newHeight} | Size: {$newSize}");
        }
    }
}
