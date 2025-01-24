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

    public function showUserPdf($uniqueId, Request $request){

        $getCartId = Cart::where('random_key',$uniqueId)->first();
        $allProduct = CartItem::where('cart_id',$getCartId->id)->get();

        $userfilledData = UserPdfData::where('unique_id',$uniqueId)->orderBy('id','desc')->first();
        // dd($userfilledData->name);
        dd("update");
        // $isPdf = false;
        // return view('pdf.template',compact('allProduct','isPdf','userfilledData')); // Get HTML content for the PDF

    }

}