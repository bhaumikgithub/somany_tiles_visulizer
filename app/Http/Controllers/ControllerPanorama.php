<?php

namespace App\Http\Controllers;

use App\Room2d;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\RoomType;
use App\Panorama;
use App\SurfaceType;
use App\Savedroom;
use App\Category;

class ControllerPanorama extends Controller
{

    /**
     *  AJAX
     */

    public function index()
    {
        return view('panorama.index');
    }


    public function getRoom($id) {
        $room = Panorama::findOrFail($id);
        $room->surfaceTypes = SurfaceType::optionsAsArray();
        return response()->json($room);
    }


    ///////////////////////////////////////////////////////////


    public function room($id, $url = null, $icon = null) {
        $roomById = false;
        if ($id) {
            $roomById = Panorama::find($id);
            $icon = Panorama::find($id)->icon;
        }

        if (!$roomById && !$url) { abort(404); }

        if( $url ){
            $savedroom = Savedroom::where('url', $url)->first();
            $room = Panorama::where('id', $savedroom->roomid)->first();
            $id = $savedroom->roomid;
            $name = $room->name;
            $type = $room->type;
        } else {
            $id = $id;
            $name = $roomById->name;
            $type = $roomById->type;
        }

        $userId = Auth::id();

        return view('panorama.room', [
            'roomId' => $id,
            'room_name' => $name,
            'room_type'=> $type,
            'savedRoomUrl' => $url,
            'rooms' => Panorama::roomsByType(),
            'saved_rooms' => Savedroom::getUserSavedRooms($userId),
            'userId' => $userId,
            'room_types' => RoomType::optionsAsArray(),
            'room_icon' => $icon,
            'product_categories' => Category::getByType(1),
        ]);
    }

    public function roomDefault() {
        $room = Panorama::where('enabled', 1)->first();

        if (!$room) { abort(404); }

        return $this->room($room->id);
    }

    public function roomListing($room_type)
    {
        $rooms = Panorama::where('type', $room_type)->where('enabled', 1)->get();
        return view('panorama.listing',compact('rooms'));
    }


    ///////////////////////////////////////////////////////////


    public function rooms() {
        $rooms = Panorama::orderBy('id', 'desc')->paginate(10);
        $roomTypes = RoomType::optionsAsArray();

        return view('panorama.rooms', ['rooms' => $rooms, 'roomTypes' => $roomTypes]);
    }

    public function roomAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|string',
            'type' => 'nullable|max:32|string',
            'icon' => 'nullable|image|max:1024|dimensions:max_width=1024,max_height=1024',
            'image' => 'required|image', // |max:10000|dimensions:max_width=12288,max_height=1024
            'shadow' => 'nullable|image', // |max:10000|dimensions:max_width=12288,max_height=1024
            'shadow_matt' => 'nullable|image', // |max:10000|dimensions:max_width=12288,max_height=1024
            'surfaces' => 'required|json',
        ]);

        if ($validator->fails()) {
            return redirect('/panoramas')->withInput()->withErrors($validator);
        }

        $room = new Panorama;
        $room->name = $request->name;
        $room->type = $request->type;

        if ($request->hasFile('icon')) {
            $room->icon = $request->file('icon')->store('panoramas', 'public');
        }
        if ($request->hasFile('image')) {
            $room->image = $request->file('image')->store('panoramas', 'public');
            $room->theme0 = $request->file('image')->store('panoramas', 'public');
            $room->theme_thumbnail0 = $request->file('theme_thumbnail0')->store('rooms2d', 'public');
            $room->text0 = $request->text0;
        }
        if ($request->hasFile('shadow')) {
            $room->shadow = $request->file('shadow')->store('panoramas', 'public');
        }
        if ($request->hasFile('shadow_matt')) {
            $room->shadow_matt = $request->file('shadow_matt')->store('panoramas', 'public');
        }

        // Array of theme, thumbnail, and text field combinations
        $fields = [
            ['theme1', 'theme_thumbnail1', 'text1'],
            ['theme2', 'theme_thumbnail2', 'text2'],
            ['theme3', 'theme_thumbnail3', 'text3'],
            ['theme4', 'theme_thumbnail4', 'text4'],
            ['theme5', 'theme_thumbnail5', 'text5'],
        ];

        foreach ($fields as $fieldSet) {
            // $fieldSet will contain an array with [themeX, theme_thumbnailX, textX]
            $themeField = $fieldSet[0];
            $thumbnailField = $fieldSet[1];
            $textField = $fieldSet[2];

            // Handle the theme field (image)
            if ($request->hasFile($themeField)) {
                // If the theme file exists, delete the old one from storage
                if ($room->getOriginal($themeField)) {
                    Storage::disk('public')->delete($room->getOriginal($themeField));
                }

                // Store the new theme file
                $room->$themeField = $request->file($themeField)->store('panoramas', 'public');
            }

            // Handle the thumbnail field (image)
            if ($request->hasFile($thumbnailField)) {
                // If the thumbnail file exists, delete the old one from storage
                if ($room->getOriginal($thumbnailField)) {
                    Storage::disk('public')->delete($room->getOriginal($thumbnailField));
                }

                // Store the new thumbnail file
                $room->$thumbnailField = $request->file($thumbnailField)->store('panoramas', 'public');
            }

            // Handle the text field (text input)
            if ($request->has($textField)) {
                $room->$textField = $request->$textField;
            }
        }


        $room->surfaces = $request->surfaces;
        $room->enabled = 1;
        $room->save();

        return redirect('/panoramas');
    }

    public function roomUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:panoramas,id',
            'name' => 'required|max:255|string',
            'type' => 'nullable|max:32|string',
            'icon' => 'nullable|image|max:1024|dimensions:max_width=1024,max_height=1024',
            'image' => 'nullable|image', // |max:10000|dimensions:max_width=12288,max_height=1024
            'shadow' => 'nullable|image', // |max:10000|dimensions:max_width=12288,max_height=1024
            'shadow_matt' => 'nullable|image', // |max:10000|dimensions:max_width=12288,max_height=1024
            'surfaces' => 'required|json',
            'enabled' => 'nullable|boolean',
            'theme1' => 'nullable|image',
            'theme2' => 'nullable|image' ,
            'theme3' => 'nullable|image',
            'theme4' => 'nullable|image',
            'theme5' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return redirect('/panoramas')->withInput()->withErrors($validator);
        }

        $room = Panorama::findOrFail($request->id);
        $room->name = $request->name;
        $room->type = $request->type;

        if ($request->hasFile('icon')) {
            Storage::disk('public')->delete($room->getOriginal('icon'));
            $room->icon = $request->file('icon')->store('panoramas', 'public');
        }
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($room->getOriginal('image'));
            $room->image = $request->file('image')->store('panoramas', 'public');
        }
        if ($request->hasFile('shadow')) {
            Storage::disk('public')->delete($room->getOriginal('shadow'));
            $room->shadow = $request->file('shadow')->store('panoramas', 'public');
        }
        if ($request->hasFile('shadow_matt')) {
            Storage::disk('public')->delete($room->getOriginal('shadow_matt'));
            $room->shadow_matt = $request->file('shadow_matt')->store('panoramas', 'public');
        }

        if ($request->hasFile('theme_thumbnail0')) {
            $room->theme_thumbnail0 = $request->file('theme_thumbnail0')->store('panoramas', 'public');
        }
        $room->text0 = $request->text0;

        // Array of theme, thumbnail, and text field combinations
        $fields = [
            ['theme1', 'theme_thumbnail1', 'text1'],
            ['theme2', 'theme_thumbnail2', 'text2'],
            ['theme3', 'theme_thumbnail3', 'text3'],
            ['theme4', 'theme_thumbnail4', 'text4'],
            ['theme5', 'theme_thumbnail5', 'text5'],
        ];

        foreach ($fields as $fieldSet) {
            // $fieldSet will contain an array with [themeX, theme_thumbnailX, textX]
            $themeField = $fieldSet[0];
            $thumbnailField = $fieldSet[1];
            $textField = $fieldSet[2];

            // Handle the theme field (image)
            if ($request->hasFile($themeField)) {
                // If the theme file exists, delete the old one from storage
                if ($room->getOriginal($themeField)) {
                    Storage::disk('public')->delete($room->getOriginal($themeField));
                }

                // Store the new theme file
                $room->$themeField = $request->file($themeField)->store('panoramas', 'public');
            }

            // Handle the thumbnail field (image)
            if ($request->hasFile($thumbnailField)) {
                // If the thumbnail file exists, delete the old one from storage
                if ($room->getOriginal($thumbnailField)) {
                    Storage::disk('public')->delete($room->getOriginal($thumbnailField));
                }

                // Store the new thumbnail file
                $room->$thumbnailField = $request->file($thumbnailField)->store('panoramas', 'public');
            }

            // Handle the text field (text input)
            if ($request->has($textField)) {
                $room->$textField = $request->$textField;
            }
        }

        $room->surfaces = $request->surfaces;
        if (isset($request->enabled)) { $room->enabled = 1; } else { $room->enabled = 0; }

        $room->save();

        return redirect('/panoramas');
    }

    public function roomsDelete(Request $request) {
        $rooms = Panorama::find(json_decode($request->selectedRooms));
        foreach ($rooms as $room) {
            Savedroom::deleteRelated($room->id, '2d');

            Storage::disk('public')->delete($room->getOriginal('icon'));
            Storage::disk('public')->delete($room->getOriginal('image'));
            Storage::disk('public')->delete($room->getOriginal('shadow'));
            Storage::disk('public')->delete($room->getOriginal('shadow_matt'));
            $room->delete();
        }

        return redirect('/panoramas');
    }

    public function roomsEnable(Request $request) {
        Panorama::whereIn('id', json_decode($request->selectedRooms))->update(['enabled' => 1]);
        return redirect('/panoramas');
    }

    public function roomsDisable(Request $request) {
        Panorama::whereIn('id', json_decode($request->selectedRooms))->update(['enabled' => 0]);
        return redirect('/panoramas');
    }


    ///////////////////////////////////////////////////////////


    public function backedRoom($id, $url, $icon) {
        return view('panorama.backedRoom', [
            'room_url' => $url,
            'room_icon' => $icon,
        ]);
    }

//    public function roomSurfaces($id) {
//        return view('panorama.roomSurfaces', ['roomId' => $id]);
//    }
//
//    public function roomSurfacesUpdate(Request $request) {
//        $validator = Validator::make($request->all(), [
//            'roomId' => 'required|integer|exists:panoramas,id',
//            'surfaces' => 'required|json',
//        ]);
//
//        if ($validator->fails()) {
//            return redirect('/panoramas')->withInput()->withErrors($validator);
//        }
//
//        $room = Panorama::findOrFail($request->roomId);
//        $room->surfaces = $request->surfaces;
//        $room->save();
//
//        return redirect('/panoramas');
//    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getRoomSurfacePanorama(Request $request): JsonResponse
    {
        $room = Panorama::findOrFail($request->room_id);
        $surface = json_decode($room->surfaces,true);
        $forRoom = "panorama";
        return response()->json([
            'body' => view('common.exists_surface_area',compact('surface','forRoom'))->render(),
            'data' => ['surface'=> $surface,'forRoom' => $forRoom],
            'success' => 'success'
        ]);
    }

}
