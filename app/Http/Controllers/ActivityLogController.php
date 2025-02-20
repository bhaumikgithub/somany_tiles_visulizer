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
        $sessionId = session()->getId();
        $category = $request->input('category');

        // Check if an analytics record exists for the session
        $analytics = Analytics::where('session_id', $sessionId)->first();
        if ($analytics) {
            // Decode existing category JSON
            $existingCategories = json_decode($analytics->category, true) ?? [];

            // Append new category if not already present
            if (!in_array($category, $existingCategories)) {
                $existingCategories[] = $category;
                $analytics->update([
                    'category' => json_encode($existingCategories),
                ]);
            }
        } else {
            // New session, create a new analytics entry
            Analytics::create([
                'pincode' => $pincode,
                'zone' => $zone,
                'session_id' => $sessionId,
                'category' => json_encode([$category]), // Store as JSON array
            ]);
        }

        Analytics::create([
            'pincode' => session('pincode'),
            'zone' => Helper::getZoneByPincode(session('pincode')),
            'session_id' => $request->get('session_id'),
            'category' => $request->category,

        ]);
        return response()->json(['success' => true]);
    }

    private function getZoneByPincode($pincode): string | JsonResponse
    {
        // Create a Guzzle client
        $client = new Client();

        // Call the external pincode API
        $response = $client->request('GET', "http://www.postalpincode.in/api/pincode/{$pincode}");

        // Decode the response
        $data = json_decode($response->getBody()->getContents(), true);

        // Check if the API call was successful
        if ($data['Status'] !== 'Success' || empty($data['PostOffice'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid pincode or data not found.'
            ], 404);
        }

        // Extract area and state
        $postOffice = $data['PostOffice'][0];
        $area = $postOffice['Name'];
        $state = $postOffice['State'];

        return $this->getZoneFromState($state);
    }

    private function getZoneFromState($state): string
    {
        $zones = [
            'Central' => ['Madhya Pradesh','Chhattisgarh'],
            'West' => ['Maharashtra', 'Gujarat','Goa','Daman & Diu','Dadra & Nagar Haveli'],
            'North' => ['Delhi', 'NCR','Rajasthan','Punjab', 'Haryana','Chandigarh','Himachal Pradesh','Jammu & Kashmir','Uttarakhand','Uttar Pradesh'],
            'South' => ['Lakshadweep','Pondicherry','Tamil Nadu', 'Kerala', 'Karnataka','Andhra Pradesh','Telangana'],
            'East' => ['Andaman & Nicobar', 'West Bengal','Bihar','Jharkhand','Odisha','Assam','Manipur','Arunachal Pradesh','Nagaland','Mizoram','Tripura'],
        ];

        foreach ($zones as $zone => $states) {
            if (in_array($state, $states)) {
                return $zone;
            }
        }

        return 'Unknown';
    }

}
