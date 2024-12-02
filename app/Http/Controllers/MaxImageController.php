<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaxImageController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $get_max_tiles = Company::first();
        return view('max_image.index',compact('get_max_tiles'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'maximum_tiles' => 'required|min:1|max:10000',
        ]);
        if ($validator->fails()) {
            return redirect('/maximum_images')->withInput()->withErrors($validator);
        }

        Company::where('id', $request->company_id)->update(['maximum_tiles' => $request->maximum_tiles]);

        $get_max_tiles = Company::first();
        return redirect('/maximum_images');
    }
}
