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
    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function index(): Factory|View|Application|\Illuminate\Contracts\Foundation\Application
    {
        return view('roomAI.index');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_own_room' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('user_own_room')) {
            $file = $request->file('user_own_room');

            // Generate a unique file name using uniqid()
            $uniqueId = uniqid() . '_' . time();
            $fileExtension = $file->getClientOriginalExtension();

            $fileName = $uniqueId . '.' . $fileExtension;
            $thumbnailName = $uniqueId . '_thumb.' . $fileExtension;

            // Define storage paths
            $mainFolder = 'roomAi';
            $iconFolder = 'roomAi/icons';

            // Ensure directories exist
            Storage::makeDirectory($mainFolder);
            Storage::makeDirectory($iconFolder);

            // Store main file
            $filePath = $mainFolder . '/' . $fileName;
            $file->storeAs('public/' . $mainFolder, $fileName); // Store in public disk

            // Generate and store thumbnail
            $thumbnailPath = $iconFolder . '/' . $thumbnailName;
            $thumbnail = Image::make($file)->resize(100, 100);
            Storage::put('public/' . $thumbnailPath, (string) $thumbnail->encode());


            $roomAi = RoomAI::create([
                'thumbnailUrl' => $thumbnailPath,
                'file' => $filePath,
                'visitorId' => $request->session()->getId()
            ]);

            // Return JSON response
            return response()->json([
                'status' => 'success',
                'message' => 'File uploaded successfully!',
                'room_id' => $roomAi->id
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'File upload failed.',
        ], 400);
    }

    public function getRoom($id): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $room = RoomAI::findOrFail($id);
        return view('roomAI.room');
    }
}
