<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Tile;

class Helper
{
    // A simple helper function
    public static function getTilePrice($tile_id,$cart_item_id)
    {
        $cart_item = CartItem::findOrFail($cart_item_id);
        $tilesData = json_decode($cart_item->tiles_json,true);
        foreach ($tilesData as $tile) {
            if ($tile['id'] == $tile_id) {
                return $tile['price'];
                break;
            }
        }
        return 'Price Not given';
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
