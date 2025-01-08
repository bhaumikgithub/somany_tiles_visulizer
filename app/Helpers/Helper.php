<?php

namespace App\Helpers;

use App\Tile;

class Helper
{
    // A simple helper function
    public static function getTilePrice($tile_id)
    {
        $tile = Tile::find($tile_id);
        return $tile->price;
    }

    public static function mmToFeet($mm): string
    {
        return number_format($mm/ 304.8, 2);
    }

    public static function getTilesParCarton($tile_id)
    {
        $tile = Tile::find($tile_id);
        return $tile->tiles_per_carton;
    }
}
