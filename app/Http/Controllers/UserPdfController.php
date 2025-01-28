<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\UserPdfData;
use Illuminate\Http\Request;

class UserPdfController extends Controller
{
    
    public function viewUserPdfList(Request $request){
        $usersData = UserPdfData::paginate(10);
        return view('userPdfList' , compact('usersData'));
    }

    public function filteredPdfList(Request $request){

        // dd($request->all());
        $query = UserPdfData::query();

        // Handle search filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('mobile')) {
            $query->where('mobile', 'like', '%' . $request->mobile . '%');
        }
        if ($request->filled('pincode')) {
            $query->where('pincode', 'like', '%' . $request->pincode . '%');
        }
        if ($request->filled('user_account')) {
            $query->where('user_account', 'like', '%' . $request->user_account . '%');
        }
        if ($request->filled('unique_id')) {
            $query->where('unique_id', 'like', '%' . $request->unique_id . '%');
        }

        // Handle sorting by date
        if ($request->filled('sort_by_date')) {
            $query->orderBy('date', $request->sort_by_date);
        }

        $usersData = $query->paginate(10);

        // return view('filteredPdfList', compact('usersData'))->render();
        return response()->json([
            'html' => view('filteredPdfList', compact('usersData'))->render(),
        ]);

    }


}