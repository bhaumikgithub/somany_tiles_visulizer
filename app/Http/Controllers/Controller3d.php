<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\RoomType;
use App\Room;
use App\Savedroom;
use App\Category;

class Controller3d extends Controller
{

    /**
     *  AJAX
     */

    public function getRoom($id) {
        $room = Room::findOrFail($id);
        return response()->json($room);
    }


    ///////////////////////////////////////////////////////////


    public function room($id, $url = null, $icon = null) {
        $roomById = false;
        if ($id) {
            $roomById = Room::find($id);
            $icon = Room::find($id)->icon;
        }

        if (!$roomById && !$url) { abort(404); }

        $userId = Auth::id();

        return view('3d.room', [
            'roomId' => $id,
            'savedRoomUrl' => $url,
            'rooms' => Room::roomsByType(),
            'saved_rooms' => Savedroom::getUserSavedRooms($userId),
            'userId' => $userId,
            'room_types' => RoomType::optionsAsArray(),
            'room_icon' => $icon,
            'product_categories' => Category::getByType(1),
        ]);
    }

    public function roomDefault() {
        $room = Room::where('enabled', 1)->first();

        if (!$room) { abort(404); }

        return $this->room($room->id);
    }


    ///////////////////////////////////////////////////////////


    public function rooms() {
        $rooms = Room::orderBy('id', 'desc')->paginate(10);
        $roomTypes = RoomType::optionsAsArray();

        return view('3d.rooms', ['rooms' => $rooms, 'roomTypes' => $roomTypes]);
    }

    public function roomAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|string',
            'type' => 'nullable|max:32|string',
            'sourcesPath' => 'required|max:1000|string',
            'mapSize' => 'required|in:128,256,512,1024,2048,4096,8192,16384',
            'cameraFov' => 'nullable|numeric',
            'iconfile' => 'nullable|image|max:1024|dimensions:max_width=1024,max_height=1024',
            'size' => 'required|json',
            'firstPersonViewHeight' => 'required|numeric',
            'endPoints' => 'required|json',
            'parts' => 'required|json',
            'tiledSurfaces' => 'required|json',
            'mirrors' => 'nullable|json',
            'useMirrors' => 'nullable|boolean',
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/rooms')->withInput()->withErrors($validator);
        }

        $room = new Room;
        $room->name = $request->name;
        $room->type = $request->type;
        $room->sourcesPath = $request->sourcesPath;
        $room->mapSize = $request->mapSize;
        if (isset($request->cameraFov)) { $room->cameraFov = $request->cameraFov; } else { $room->cameraFov = 60; }
        $room->size = $request->size;
        if ($request->hasFile('iconfile')) {
            $room->iconfile = $request->file('iconfile')->store('rooms', 'public');
        }
        $room->firstPersonViewHeight = $request->firstPersonViewHeight;
        $room->endPoints = $request->endPoints;
        $room->parts = $request->parts;
        $room->tiledSurfaces = $request->tiledSurfaces;
        $room->mirrors = $request->mirrors;
        if (isset($request->useMirrors)) { $room->useMirrors = 1; } else { $room->useMirrors = 0; }
        $room->enabled = 1;
        $room->save();

        return redirect('/rooms');
    }

    public function roomUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:rooms,id',
            'name' => 'required|max:255|string',
            'type' => 'nullable|max:32|string',
            'sourcesPath' => 'required|max:1000|string',
            'mapSize' => 'required|in:128,256,512,1024,2048,4096,8192,16384',
            'cameraFov' => 'nullable|numeric',
            'iconfile' => 'nullable|image|max:1024|dimensions:max_width=1024,max_height=1024',
            'useMirrors' => 'nullable|boolean',
            'enabled' => 'nullable|boolean',

            'extraOptions' => 'nullable|boolean',
            'firstPersonViewHeight' => 'required_if:extraOptions,1|numeric',
            'size' => 'required_if:extraOptions,1|json',
            'endPoints' => 'required_if:extraOptions,1|json',
            'parts' => 'required_if:extraOptions,1|json',
            'tiledSurfaces' => 'required_if:extraOptions,1|json',
            'mirrors' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return redirect('/rooms')->withInput()->withErrors($validator);
        }

        $room = Room::findOrFail($request->id);
        $room->name = $request->name;
        $room->type = $request->type;
        $room->sourcesPath = $request->sourcesPath;
        $room->mapSize = $request->mapSize;
        $room->cameraFov = $request->cameraFov;
        if ($request->hasFile('iconfile')) {
            Storage::disk('public')->delete($room->getOriginal('iconfile'));
            $room->iconfile = $request->file('iconfile')->store('rooms', 'public');
        }
        if (isset($request->useMirrors)) { $room->useMirrors = 1; } else { $room->useMirrors = 0; }
        if (isset($request->enabled)) { $room->enabled = 1; } else { $room->enabled = 0; }
        if (isset($request->extraOptions) && $request->extraOptions) {
            $room->firstPersonViewHeight = $request->firstPersonViewHeight;
            $room->size = $request->size;
            $room->endPoints = $request->endPoints;
            $room->parts = $request->parts;
            $room->tiledSurfaces = $request->tiledSurfaces;
            $room->mirrors = $request->mirrors;
        }

        $room->save();

        return redirect('/rooms');
    }

    public function roomsDelete(Request $request) {
        $rooms = Room::find(json_decode($request->selectedRooms));
        foreach ($rooms as $room) {
            Savedroom::deleteRelated($room->id, '3d');

            Storage::disk('public')->delete($room->getOriginal('iconfile'));
            $room->delete();
        }

        return redirect('/rooms');
    }

    public function roomsEnable(Request $request) {
        Room::whereIn('id', json_decode($request->selectedRooms))->update(['enabled' => 1]);
        return redirect('/rooms');
    }

    public function roomsDisable(Request $request) {
        Room::whereIn('id', json_decode($request->selectedRooms))->update(['enabled' => 0]);
        return redirect('/rooms');
    }

}
