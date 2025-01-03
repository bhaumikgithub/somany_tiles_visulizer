<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckPincode
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('Redirect check - Current Path: ' . $request->path());
        Log::info('Pincode session: ' . session()->has('pincode') ? 'Present' : 'Not Present');

        // Check if we are on the summary page
        if (!session()->has('pincode') &&
            !session()->has('redirected_to_room2d') &&
            !in_array($request->path(), ['pdf-summary'])) {

            // Mark the user as redirected
            session(['redirected_to_room2d' => true]);

            // Redirect to the homepage and show the pincode modal
            return redirect('/room2d')->with('show_pincode_modal', true);
        }

        return $next($request);
    }
}
