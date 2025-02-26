<?php

namespace App\Http\Controllers;

use App\Category;
use App\Models\RoomAI;
use App\Room;
use App\Room2d;
use App\RoomType;
use App\Savedroom;
use App\SurfaceType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerRoomAI extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function index(): Factory|View|Application|\Illuminate\Contracts\Foundation\Application
    {
        return view('roomAI.index');
    }

    /**
     * @param $room_type
     * @return Factory|Application|View|\Illuminate\Contracts\Foundation\Application
     */
    public function roomListing($room_type): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $rooms = RoomAI::where('type', $room_type)->where('enabled', 1)->get();
        return view('room_ai.listing',compact('rooms'));
    }

    public function room(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('roomAI.room');
    }
}
