<?php

namespace App;

class Panorama extends RoomModel
{

    public static function roomsByType() {
        $rooms = Panorama::where('enabled', 1)->get();
        $by_type = [];
        foreach ($rooms as $room) {
            if (!isset($by_type[$room->type])) {
                $by_type[$room->type] = [$room];
            } else {
                array_push($by_type[$room->type], $room);
            }
        }
        return $by_type;
    }

}
