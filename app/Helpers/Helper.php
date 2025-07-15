<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Showroom;
use App\Room2d;
use App\Tile;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Panorama;

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

    public static function getZoneFromState($state): string
    {
        $zones = [
            'Central' => ['Madhya Pradesh', 'Chhattisgarh'],
            'West' => ['Maharashtra', 'Gujarat', 'Goa', 'Daman & Diu', 'Dadra & Nagar Haveli'],
            'North' => ['Delhi', 'NCR', 'Rajasthan', 'Punjab', 'Haryana', 'Chandigarh', 'Himachal Pradesh', 'Jammu & Kashmir', 'Uttarakhand', 'Uttar Pradesh'],
            'South' => ['Lakshadweep', 'Pondicherry', 'Tamil Nadu', 'Kerala', 'Karnataka', 'Andhra Pradesh', 'Telangana'],
            'East' => ['Andaman & Nicobar', 'West Bengal', 'Bihar', 'Jharkhand', 'Odisha', 'Assam', 'Manipur', 'Arunachal Pradesh', 'Nagaland', 'Mizoram', 'Tripura'],
        ];

        foreach ($zones as $zone => $states) {
            if (in_array($state, $states)) {
                return $zone;
            }
        }

        return 'Unknown';
    }

    // Fetch Zone Based on Pincode
    public static function getZoneByPincode($pincode): string | JsonResponse
    {
        try {
            $client = new Client([
                'headers' => [
                    'User-Agent' => 'LaravelApp', // Important
                ],
                'curl' => [
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                ],
                'verify' => false,
            ]);

            $response = $client->request('GET', "http://www.postalpincode.in/api/pincode/{$pincode}", [
                'timeout' => 5,
                'connect_timeout' => 3,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            \Log::info('PINCODE API response', $data);

            if (!isset($data['Status']) || $data['Status'] !== 'Success' || empty($data['PostOffice'])) {
                throw new \Exception("Invalid response from API.");
            }

            $postOffice = $data['PostOffice'][0];
            $state = $postOffice['State'] ?? 'Delhi';

        } catch (\Exception $e) {
            \Log::error("Pincode API Failed: " . $e->getMessage());
            $state = 'Delhi';
        }

        return self::getZoneFromState($state);
    }

    public static function getTileNameAndSurface($tileId): array
    {
        $tile = Tile::find($tileId);
        if ($tile) {
            return [
                'tile_name' => $tile->name,
                'surface' => $tile->surface ?? 'Unknown Surface', // Assuming `surface` is a column in the Tile table
                'photo' => $tile->icon,
                'size' => $tile->size,
                'finish' => $tile->finish,
                'category' => $tile->brand,
                'innovation' => $tile->innovation,
                'color' => $tile->color,
            ];
        }

        return [
            'tile_name' => 'Unknown Tile',
            'surface' => 'Unknown Surface'
        ];
    }

    public static function getRoomCatgory($room_id,$room_type): string
    {
        if( $room_type === "2d")
            $room = Room2d::find($room_id);
        else
            $room = Panorama::find($room_id);

        return ucwords($room->type);
    }

    public static function getShowroomDetails($showRoomId) 
    {
        $showroom = Showroom::find($showRoomId);
        if ($showroom) {
            return [
                'name' => $showroom->name,
                'city' => $showroom->city ?? 'Unknown city', // Assuming `surface` is a column in the Tile table
            ];
        }

        return [
            'name' => 'Unknown Tile',
            'city' => 'Unknown Surface'
        ];
    }
}
