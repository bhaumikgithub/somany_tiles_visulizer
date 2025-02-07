<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Showroom;
use App\Tile;
use Illuminate\Support\Facades\DB;

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
        if ($tile) {
            // Access properties only if $tile is not null
            $tilesPerCarton = $tile->tiles_per_carton;
        } else {
            // Handle case where tile is not found
            $tilesPerCarton = null;  // Or provide some default value
        }
        return $tilesPerCarton;
    }

    public static function getBoxCoverageAreaSqFt($tile_id)
    {
        $tile = Tile::find($tile_id);
        if ($tile) {
            // Access properties only if $tile is not null
            $boxCoverageArea = $tile->coverage_sq_ft;
        } else {
            // Handle a case where tile is not found
            $boxCoverageArea = null;  // Or provide some default value
        }
        return $boxCoverageArea;
    }

    public static function getShowRoomNames($showroom_id): string
    {
        if( $showroom_id ) {
            // Fetch the skill names from the database based on the passed IDs
            $show_room_names = Showroom::whereIn('id', $showroom_id)->pluck('name')->toArray();
            // Return the skills as a comma-separated string
            return implode(', ', $show_room_names);
        } else
            return true;
    }

    public static function getSAPCode($tile_id)
    {
        $tile = Tile::find($tile_id);
        return $tile->sku;
    }
}
