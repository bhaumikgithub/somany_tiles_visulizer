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
        $engine_2d_enabled = config('app.engine_2d_enabled');
        $engine_panorama_enabled = config('app.engine_panorama_enabled');

//        Log::info('Redirect check - Current Path: ' . $request->path());
//        Log::info('Pincode session: ' . (session()->has('pincode') ? 'Present' : 'Not Present'));

        // List of routes that should NOT be redirected
        $excludedRoutes = ['generate-pdf', 'fetch-progress','fetch_tiles']; // ✅ Added fetch-progress
        $isPdfSummaryRoute = str_contains($request->path(), 'pdf-summary');

        // Exclude direct room URLs from redirection
        if (preg_match('/^room\/url\/[a-f0-9]{32}$/', $request->path())) {
            return $next($request);
        }

        // Check if redirection is required
        if (!session()->has('pincode') &&
            !session()->has('redirected_to_room') &&
            !in_array($request->path(), $excludedRoutes) &&
            !$isPdfSummaryRoute) {

            // Determine a redirect path based on user's requested route
            if (str_contains($request->path(), 'panorama') && $engine_panorama_enabled) {
                $redirectPath = '/panorama';
            } elseif (str_contains($request->path(), 'room2d') && $engine_2d_enabled) {
                $redirectPath = '/room2d';
            } else {
                // Default redirection based on available engines
                $redirectPath = $engine_2d_enabled ? '/room2d' : ($engine_panorama_enabled ? '/panorama' : '/');
            }

            // Mark as redirected
            session(['redirected_to_room' => true]);
            Session::save(); // Ensure session persistence

            // Redirect with a modal flag
            return redirect($redirectPath)->with('show_pincode_modal', true);
        }

        return $next($request);
    }

}
