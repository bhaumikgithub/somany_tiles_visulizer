<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use App\Models\Showroom;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class ActivityLogController extends Controller
{
    public function trackCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $sessionId = session()->getId(); // Get session ID
        $pincode = session('pincode');
        $zone = Helper::getZoneByPincode($pincode);
        $pinCodeZoneData  = [
            'pincode' => $pincode,
            'zone' => $zone
        ];
        $category = [
            'category_name' => $request->category,
            'category_type' => $request->type,
        ];
        $roomData = [
            'room_id' => $request->room_id,
            'room_name' => $request->room,
            'room_type' => $request->type,
        ];

        $user = "guest";

        //Check if user has logged in backend or not
        if (auth()->check()) {
            $loged_user = auth()->user();
            $user = $loged_user->id;
        }

        // Check if an analytics record exists for the session
        $analytics = Analytics::where('session_id', $sessionId)->first();
        if ($analytics) {
            // Decode existing category JSON
            $existingCategories = json_decode($analytics->category, true) ?? [];
            $existingRooms = json_decode($analytics->room, true) ?? [];
            $existingPincodeZones = json_decode($analytics->pincode_zone, true) ?? [];

            // **Update Pincode & Zone**
            if (!in_array($pinCodeZoneData , $existingPincodeZones)) {
                $existingPincodeZones[] = $pinCodeZoneData;
            }

            // **Filter out null categories before appending**
            if (!empty($category) && !in_array($category, $existingCategories)) {
                $existingCategories[] = $category;
                $analytics->update([
                    'category' => json_encode(array_values(array_filter($existingCategories))), // Remove null values
                ]);
            }

            // **Filter out null room data before appending**
            if (!empty($roomData['room_id']) && !empty($roomData['room_name']) && !in_array($roomData, $existingRooms)) {
                $existingRooms[] = $roomData;
                $analytics->update([
                    'room' => json_encode(array_values(array_filter($existingRooms, function ($room) {
                        return !empty($room['room_id']) && !empty($room['room_name']);
                    }))), // Remove null room entries
                ]);
            }

        } else {
            // **Filter null values for the new session entry**
            $categoryData = !empty($category) ? [$category] : [];
            $roomDataArray = (!empty($roomData['room_id']) && !empty($roomData['room_name'])) ? [$roomData] : [];

            // New session, create a new analytics entry
            Analytics::create([
                'session_id' => $sessionId,
                'pincode' => json_encode([$pincode]),
                'zone' => json_encode([$zone]),
                'category' => json_encode($categoryData), // Store non-null categories
                'room' => json_encode($roomDataArray), // Store non-null room data
                'user_logged_in' => $user,
                'showroom' => $loged_user->showroom_id ?? null,
            ]);
        }
        return response()->json(['success' => true]);
    }

    public function storeToAnalyticsForAI(Request $request)
    {
        try {
            $sessionId = session()->getId(); // Get session ID
            $pincode = session('pincode');
            $zone = Helper::getZoneByPincode($pincode);

            $pinCodeZoneData  = [
                'pincode' => $pincode,
                'zone' => $zone
            ];

            $category = [
                'category_name' => "users_room",
                'category_type' => $request->room,
            ];

            $roomData = [
                'room_name' => "users_room",
                'room_type' => $request->room,
            ];

            $user = "guest";
            $loged_user = null;

            if (auth()->check()) {
                $loged_user = auth()->user();
                $user = $loged_user->id;
            }

            $analytics = Analytics::where('session_id', $sessionId)->first();

            if ($analytics) {
                $existingCategories = json_decode($analytics->category, true) ?? [];
                $existingRooms = json_decode($analytics->room, true) ?? [];
                $existingPincodeZones = json_decode($analytics->pincode_zone, true) ?? [];

                if (!in_array($pinCodeZoneData, $existingPincodeZones)) {
                    $existingPincodeZones[] = $pinCodeZoneData;
                    $analytics->update(['pincode_zone' => json_encode($existingPincodeZones)]);
                }

                if (!in_array($category, $existingCategories)) {
                    $existingCategories[] = $category;
                    $analytics->update(['category' => json_encode($existingCategories)]);
                }

                if (!in_array($roomData, $existingRooms)) {
                    $existingRooms[] = $roomData;
                    $analytics->update(['room' => json_encode($existingRooms)]);
                }
            } else {
                Analytics::create([
                    'session_id' => $sessionId,
                    'pincode' => json_encode([$pincode]),
                    'zone' => json_encode([$zone]),
                    'pincode_zone' => json_encode([$pinCodeZoneData]),
                    'category' => json_encode([$category]),
                    'room' => json_encode([$roomData]),
                    'user_logged_in' => $user,
                    'showroom' => $loged_user->showroom_id ?? null,
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Analytics data stored successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to store analytics data.', 'error' => $e->getMessage()], 500);
        }
    }


    public function storeViewdTilesByUser(Request $request)
    {
        $sessionId = session()->getId(); // Get session ID
        $pincode = session('pincode');
        $zone = Helper::getZoneByPincode($pincode);
        // Check if an analytics record exists for the session
        $analytics = Analytics::where('session_id', $sessionId)->first();
        if($analytics)
        {   
            $tileData = Helper::getTileNameAndSurface($request->userSelectedTileId);
            // Get existing viewed_tiles and decode
            $viewedTiles = json_decode($analytics->viewed_tiles, true) ?? [];
            // New tile view entry
            $newTile = [
                "surface" => $request->surface,
                "tile_id" => $request->userSelectedTileId,
                "room_name" => $request->current_room_name ?? "",
                "room_type" => $request->current_room_type ?? "",
                "tile_name" => $tileData['tile_name'],
                "pincode" => $pincode,
                "zone" => $zone,
            ];

            // Append new entry
            $viewedTiles[] = $newTile;
            
            $analytics->update([
                'viewed_tiles' => json_encode(array_values($viewedTiles)), // Store updated JSON
            ]);
        }
        return response()->json(['success' => true]);
    }
}
