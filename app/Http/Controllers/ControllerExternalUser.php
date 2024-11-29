<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\RegisterController;

use App\ExternalToken;
use App\Savedroom;

class ControllerExternalUser
{
    public function fromLink(Request $request)
    {
        $token = ExternalToken::where('token', $request->extt)->first();

        if (!isset($token)) {
            $register_controller = new RegisterController();
            $user = $register_controller->createWithRandomData($request->extname);
            $token = $register_controller->createToken($request->extt, $user->id);
        }

        Auth::login($token->user, true);

        if (Savedroom::existsByUrl($request->room)) {
            return redirect('/room/url/' . $request->room);
        }

        return redirect('/');
    }
}
