<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;

class ControllerSystem extends BaseController
{

    public function storageLink() {
        $exitCode = Artisan::call('storage:link');

        if ($exitCode == 0) {
            return redirect('/appsettings')->with('info', 'Storage link command executed.');
        } else {
            return redirect('/appsettings')->with('error', 'Storage link error. Exit code = ' . $exitCode);
        }

    }

}
