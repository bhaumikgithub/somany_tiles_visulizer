<?php

namespace App\Http\Controllers;
use App\Panorama;
use App\Room2d;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use MongoDB\Driver\Session;
use Validator;
use Illuminate\Support\Facades\Auth;

use App\Tile;
use App\Filter;
use App\SurfaceType;
use App\RoomType;
use App\Models\User;
use App\Savedroom;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('ajax');
    // }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return Response|JsonResponse
     */


    public function getTiles(Request $request): Response | JsonResponse
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M'); // Adjust the limit as needed

        if (config('app.tiles_access_level')) {
            $roomID = $request->input('room_id');
            $currentRoomType = $request->input('room_type');
            if( $currentRoomType === 'ai-studio'){
                $tiles = Tile::where('enabled', 1)->get();
            } else {
                if( $currentRoomType === "2d-studio" ) {
                    $findRoom = Room2d::select('type')->findOrFail($roomID);
                } else {
                    $findRoom = Panorama::select('type')->findOrFail($roomID);
                }
                $roomType = $findRoom->type;
                $commercialRooms = ['Lobby', 'Mall', 'Hotels','Restaurant','Reception']; // Define commercial room types
                $outDoorsRooms = ['Balcony','Veranda'];
                if ($roomType === 'outdoor') {
                    $roomType = $outDoorsRooms;
                } elseif ($roomType === 'commercial') {
                    // Check for multiple values in application_room_area
                    $roomType = $commercialRooms;
                }
                if ($request->input('ids')) {
                    $ids = explode(",", $request->ids);

                    $tiles = Tile::where('enabled', 1)
                        ->whereIn('id', $ids)
                        ->where(function ($query) {
                            $user = Auth::user();
                            $access_level = isset($user) ? $user->getAccessLevel() : 0;

                            $query->where('access_level', '<=', $access_level)
                                ->orWhere('access_level', null);
                        })
                        ->get();
                        return response()->json($tiles);
                }
                $tiles = $this->getTilesByRoomType($roomType);
            }
            return response()->json($tiles);
        }
        $tiles = Tile::where('enabled', 1)->get();
        return response()->json($tiles);
    }

    public function getTilesForBackedPanorama(Request $request): JsonResponse
    {
        $ids = explode(",", $request->ids);

        $tiles = Tile::where('enabled', 1)
            ->whereIn('id', $ids)
            ->where(function ($query) {
                $user = Auth::user();
                $access_level = isset($user) ? $user->getAccessLevel() : 0;

                $query->where('access_level', '<=', $access_level)
                    ->orWhere('access_level', null);
            })
            ->get();
        return response()->json($tiles);
    }

    protected function getTilesByRoomType($roomType)
    {
        if (session()->has('pincode')) {
            $getZone = $this->getZoneByPincode(session('pincode'));
            $query = Tile::filterByZone($getZone)->filterByRoomType($roomType);
        } else {
            $user = Auth::user();
            $accessLevel = isset($user) ? $user->getAccessLevel() : 0;
            $query = Tile::filterByAccessLevel($accessLevel)->filterByRoomType($roomType);
        }

        // If no matching tiles found, return an empty collection
        return $query->exists() ? $query->get() : collect([]);
    }



    public function getTile($id) {
        $tile = Tile::findOrFail($id);
        return response()->json($tile);
    }

    public function getFilters() {
        $filters = Filter::where('enabled', 1)->get();
        return response()->json($filters);
    }

    public function getFilter($id) {
        $filter = Filter::findOrFail($id);
        return response()->json($filter);
    }

    public function getSurfaceType($id) {
        $types = SurfaceType::findOrFail($id);
        return response()->json($types);
    }

    public function getSurfaceTypes() {
        $types = SurfaceType::get();
        return response()->json($types);
    }

    public function getRoomType($id) {
        $types = RoomType::findOrFail($id);
        return response()->json($types);
    }

    public function getUser($id) {
        $user = User::findOrFail($id);
        // Decode the JSON field to get the array of showroom IDs
        $user->selectedShowroomIds = json_decode($user->show_room_ids, true);
        return response()->json($user);
    }

    public function getSavedRoomByUrl($url) {
        $savedroom = Savedroom::where('url', $url)->first();
        $room = Room2d::where('id', $savedroom->roomid)->first();
        if( $room != null ) {
            $savedroom->name = $room->name;
            $savedroom->type = $room->type;
        }
        return response()->json($savedroom);
    }

    public static function saveImage($image, $url, $ext = 'png') {
        if (isset($image)) {
            list($type, $image) = explode(';base64,', $image, 2);
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);
            if (isset($image)) {
                $fileName = 'savedrooms/' . $url . '.' . $ext;
                Storage::disk('public')->put($fileName, $image);

                return $fileName;
            }
        }
    }

    public static function saveNewUserRoom($request) {
        $savedroom = new Savedroom;

        $user = Auth::user();
        if (isset($user)) {
            $savedroom->userid = $user->id;
        } else {
            $savedroom->session_token = session('_token');
        }

        $savedroom->roomid = $request->roomId;
        $savedroom->engine = $request->engine;
        $savedroom->roomsettings = $request->roomSettings;
        $savedroom->note = $request->note;
        $savedroom->enabled = 1;
        $savedroom->url = md5(uniqid());

        $savedroom->image = AjaxController::saveImage($request->image, $savedroom->url);

        if ($savedroom->note == 'backed' && isset($request->sides)) {
            for ($i = 0; $i < 6; $i++) {
                AjaxController::saveImage($request->sides[$i], $savedroom->url . '/' . $i, 'jpg');
            }
        }

        $savedroom->save();

        $user = Auth::user();
        $loggedIn = 0;
        if (isset($user)) {
            $loggedIn = 1;
        }
        return response()->json([
            'state' => 'success',
            'url' => $savedroom->url,
            'fullUrl' => '/room/url/' . $savedroom->url,
            'loggedIn' => $loggedIn
        ]);
    }

    public static function updateUserRoom($request, &$savedroom) {
        $savedroom->roomsettings = $request->roomSettings;
        $savedroom->note = $request->note;
        AjaxController::saveImage($request->image, $request->url);

        $savedroom->save();

        $user = Auth::user();
        $loggedIn = 0;
        if (isset($user)) {
            $loggedIn = 1;
        }
        return response()->json([
            'state' => 'success',
            'url' => $savedroom->url,
            'fullUrl' => '/room/url/' . $savedroom->url,
            'loggedIn' => $loggedIn
        ]);
    }

    public function saveUserRoom(Request $request) {
        $rules = [
            'image' => 'nullable|string',
            'engine' => 'nullable|string|max:16',
            'note' => 'nullable|string',
            'url' => 'nullable|max:1000|alpha_num|exists:savedrooms,url',
            'roomSettings' => 'required|json',
            'sides' => 'nullable|array|size:6',
        ];
        
        // Conditionally apply the `roomId` rule
        if ($request->engine !== "ai") {
            $rules['roomId'] = 'required|integer';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'state' => 'Validation error',
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        $token = session('_token');

        $user = Auth::user();

        if (isset($request->url)) {
            $savedroom = Savedroom::where('url', $request->url)->first();
            if (isset($user)) {
                // Savedroom::where('session_token', $token)->update(['userid' => $user->id, 'session_token' => null]);

                if (isset($savedroom->userid)) {
                    if ($savedroom->userid == $user->id) {
                        return AjaxController::updateUserRoom($request, $savedroom);
                    } else {
                        return AjaxController::saveNewUserRoom($request);
                    }
                } else {
                    if (isset($savedroom->session_token) && $savedroom->session_token == $token) {
                        $savedroom->userid = $user->id;
                        return AjaxController::updateUserRoom($request, $savedroom);
                    } else {
                        return AjaxController::saveNewUserRoom($request);
                    }
                }
            } else {
                if (isset($savedroom->session_token) && $savedroom->session_token == $token) {
                    return AjaxController::updateUserRoom($request, $savedroom);
                } else {
                    return AjaxController::saveNewUserRoom($request);
                }
            }
        } else {
            return AjaxController::saveNewUserRoom($request);
        }

        return response()->json(['state' => 'error'], 500); // Unreachable response
    }

    public static function updateUserRoomAddSpecularLights($request, &$savedroom) {
        $roomsettings = json_decode($savedroom->roomsettings, true);
        $roomsettings['specularLights'] = json_decode($request->specularLights, true);
        $savedroom->roomsettings = json_encode($roomsettings);

        $savedroom->save();

        $user = Auth::user();
        $loggedIn = 0;
        if (isset($user)) {
            $loggedIn = 1;
        }
        return response()->json([
            'state' => 'success',
            'url' => $savedroom->url,
            'fullUrl' => '/room/url/' . $savedroom->url,
            'loggedIn' => $loggedIn
        ]);
    }

    public function saveUserRoomSpecularLights(Request $request) {
        $validator = Validator::make($request->all(), [
            'url' => 'nullable|max:1000|alpha_num|exists:savedrooms,url',
            'specularLights' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'state' => 'Validation error',
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        $token = session('_token');

        $user = Auth::user();

        if (isset($request->url)) {
            $savedroom = Savedroom::where('url', $request->url)->first();
            if (isset($user)) {
                if (isset($savedroom->userid)) {
                    if ($savedroom->userid == $user->id) {
                        return AjaxController::updateUserRoomAddSpecularLights($request, $savedroom);
                    }
                } else {
                    if (isset($savedroom->session_token) && $savedroom->session_token == $token) {
                        $savedroom->userid = $user->id;
                        return AjaxController::updateUserRoomAddSpecularLights($request, $savedroom);
                    }
                }
            } else {
                if (isset($savedroom->session_token) && $savedroom->session_token == $token) {
                    return AjaxController::updateUserRoomAddSpecularLights($request, $savedroom);
                }
            }
        }

        return response()->json(['state' => 'error'], 401); // Unauthorized
    }


    private function getZoneByPincode($pincode): string | JsonResponse
    {
        try {
            // Create a Guzzle client
            $client = new Client();

            // Call the external pincode API
            $response = $client->request('GET', "http://www.postalpincode.in/api/pincode/{$pincode}", [
                'timeout' => 5, // Set timeout to avoid long delays
                'connect_timeout' => 3, // Connection timeout
            ]);

            // Decode the response
            $data = json_decode($response->getBody()->getContents(), true);

            // Check if the API call was successful
            if (!isset($data['Status']) || $data['Status'] !== 'Success' || empty($data['PostOffice'])) {
                throw new \Exception("Invalid response from API.");
            }

            // Extract area and state
            $postOffice = $data['PostOffice'][0];
            $state = $postOffice['State'] ?? 'Gujarat'; // Use Gujarat if state is missing

        } catch (\Exception $e) {
            \Log::error("Pincode API Failed: " . $e->getMessage());

            // Default to Gujarat when API fails
            $state = 'Gujarat';
        }

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
