<?php

namespace App\Http\Controllers;
use App\Room2d;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
     * @return \Illuminate\Http\Response
     */


    public function getTiles(Request $request) {
        if (config('app.tiles_access_level')) {
            if ($request->ids) {
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

            $tiles = Tile::where('enabled', 1)
                ->where(function ($query) {
                    $user = Auth::user();
                    $access_level = isset($user) ? $user->getAccessLevel() : 0;

                    $query->where('access_level', '<=', $access_level)
                        ->orWhere('access_level', null);
                })
                ->get();
                return response()->json($tiles);
        }

        $tiles = Tile::where('enabled', 1)->get();
        return response()->json($tiles);
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
        $savedroom->name = $room->name;
        $savedroom->type = $room->type;
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
        $validator = Validator::make($request->all(), [
            'roomId' => 'required|integer',
            'image' => 'nullable|string',
            'engine' => 'nullable|string|max:16',
            'note' => 'nullable|string',
            'url' => 'nullable|max:1000|alpha_num|exists:savedrooms,url',
            'roomSettings' => 'required|json',
            'sides' => 'nullable|array|size:6',
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
}
