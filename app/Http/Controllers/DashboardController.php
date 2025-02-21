<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function getAnalyticsResult(Request $request)
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';

        $analytics = DB::table('analytics')
            ->select(
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(pincode_zone, '$[0].zone')) as zone"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(pincode_zone, '$[0].pincode')) as pincode"),
                DB::raw('COUNT(*) as visits')
            )
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(pincode_zone, '$[0].zone'))"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(pincode_zone, '$[0].pincode'))"))
            ->get();

        dd($analytics->toArray());
        // Format chart data
        $chartData = [
            'labels' => $analytics->pluck('pincode'), // Display pincodes on the chart
            'values' => $analytics->pluck('visits') // Number of visits per pincode
        ];

        return response()->json(['data' => $analytics, 'chartData' => $chartData]);
    }
}
