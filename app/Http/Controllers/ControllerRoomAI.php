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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ControllerRoomAI extends Controller
{
    public function index() {
        return view('roomAI.room');
    }
}
