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

//        $analytics = Analytics::select(
//            'zone',
//            DB::raw('COUNT(*) as visits')
//        )
//            ->whereBetween('visited_at', [$startDate, $endDate])
//            ->groupBy('zone')
//            ->get();
//
//        // Calculate total visits for percentage calculation
//        $totalVisits = $analytics->sum('visits');
//
//        // Prepare data for Chart.js
//        $chartData = $analytics->map(function ($data) use ($totalVisits) {
//            $decodedZone = json_decode($data->zone, true); // Convert JSON string to array
//            $zoneName = is_array($decodedZone) ? $decodedZone[0] : $decodedZone;
//            return [
//                'zone' => $zoneName, // Convert JSON string to array,
//                'visits' => $data->visits,
//                'percentage' => round(($data->visits / $totalVisits) * 100, 2) . '%'
//            ];
//        });
//
//        return response()->json([
//            'chartData' => $chartData,
//            'totalVisits' => $totalVisits
//        ]);

        $analytics = Analytics::select(
            DB::raw("CASE 
                    WHEN JSON_VALID(zone) THEN JSON_UNQUOTE(JSON_EXTRACT(zone, '$[0]')) 
                    ELSE zone 
                 END as zone"),
            DB::raw("COUNT(*) as visits")
        )
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy('zone')
            ->get();

        dd($analytics->toArray());
        // Convert a collection to array
        $analyticsArray = $analytics->toArray();

        // Calculate total visits
        $totalVisits = array_sum(array_column($analyticsArray, 'visits'));

        // Format data for chart
        $chartData = [
            'labels' => [],
            'values' => [],
            'percentages' => []
        ];

        foreach ($analyticsArray as $data) {
            $chartData['labels'][] = $data['zone'];
            $chartData['values'][] = $data['visits'];
            $percentage = round(($data['visits'] / $totalVisits) * 100, 2);
            $chartData['percentages'][] = "{$percentage}%";
        }

        // Include total visits
        $chartData['total'] = $totalVisits;

        // Return response (for API)
        return response()->json(['data' => $analytics, 'chartData' => $chartData]);
    }
}
