<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
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
        $category = $request->category;
        $roomData = [
            'room_id' => $request->room_id,
            'room_name' => $request->room
        ];

        // Check if an analytics record exists for the session
        $analytics = Analytics::where('session_id', $sessionId)->first();
        if ($analytics) {
            // Decode existing category JSON
            $existingCategories = json_decode($analytics->category, true) ?? [];
            $existingRooms = json_decode($analytics->room, true) ?? [];
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
                'user_logged_in' => "Guest",
            ]);
        }
        return response()->json(['success' => true]);
    }
}
