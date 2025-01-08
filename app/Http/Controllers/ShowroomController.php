<?php

namespace App\Http\Controllers;

use App\Models\Showroom;
use Illuminate\Http\Request;

class ShowroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $showrooms = Showroom::paginate(10);
        // dd($showrooms);
        return view('showrooms.index', compact('showrooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'e_code' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        Showroom::create($validated);

        return redirect()->route('fetch_showroom.index');    
    }

    /**
     * Display the specified resource.
     */
    public function show(Showroom $showroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Showroom $showroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Showroom $showroom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Showroom $showroom)
    {
        //
    }

    public function showroomsDelete(Request $request) {
        $showrooms = Showroom::find(json_decode($request->selectedRooms));
        // dd($showrooms);
        foreach($showrooms as $showroom ){
            $showroom->delete();
        }

        return redirect('/fetch_showroom');
    }


    public function showroomsEnable(Request $request) {
        // dd(json_decode($request->selectedRooms));
        Showroom::whereIn('id', json_decode($request->selectedRooms))->update(['status' => "Active"]);
        return redirect('/fetch_showroom');
    }

    public function showroomsdisable(Request $request) {
        Showroom::whereIn('id', json_decode($request->selectedRooms))->update(['status' => "Inactive"]);
        return redirect('/fetch_showroom');
    }
}
