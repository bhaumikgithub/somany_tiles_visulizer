<?php

namespace App\Http\Controllers;

use App\RoomType;
// use App\RoomPlanner;
use App\Category;

class ControllerBlueprint3d extends Controller
{

    public function roomDefault() {
        // $room = RoomPlanner::where('enabled', 1)->first();

        // if (!$room) abort(404);

        // return $this->room($room->id);


        $room = [
            'id' => '1',
            'edges' => [[-1, 1], [1, 1], [1, -1], [-1, -1]],
        ];

        return view('blueprint3d.room', [
            'roomId' => 1,
            'savedRoomUrl' => '',
            'rooms' => [],
            'saved_rooms' => [],
            'userId' => '',
            'room_types' => RoomType::optionsAsArray(),
            'room_icon' => '',
            'product_categories' => Category::getByType(1),
        ]);
    }

}
