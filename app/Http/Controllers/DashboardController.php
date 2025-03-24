<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Analytics;
use App\Models\Showroom;
use App\Models\UserPdfData;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSummaryPdfChart(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';
        $chartType = $request->input('chartType');

        $queryAnalyticsBuilder = $this->queryAnalyticsBuilder($startDate, $endDate);

        $response = [];

        if ($chartType === 'summaryPdfDownloadChart') {
            //TotalUsers
            $totalGuestUsers = $queryAnalyticsBuilder->where('user_logged_in', 'guest')->count();
            $totalLoggedInUsers = $queryAnalyticsBuilder->where('user_logged_in', '!=', 'guest')->count();
            $totalUsers = $totalGuestUsers + $totalLoggedInUsers;

            /** Summary PDF Analytics */
            $totalSession = Analytics::whereBetween('visited_at', [$startDate, $endDate])->get();
            $summaryPDFData = $this->summaryPdfChart($startDate, $endDate);

            // Construct response
            $response = [
                'total_users' => $totalUsers,
                'total_logged_in_users' => $totalLoggedInUsers,
                'total_guest_users' => $totalGuestUsers,
                'total_session' => $totalSession->count(),
                'session_reach_summary_page' => $summaryPDFData['sessionReachSummaryPage'],
                'download_pdf' => $summaryPDFData['downloadPdf'],
                'summary_pdf_chart_data' => $summaryPDFData['summaryPdfChartData'],
            ];
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPinCodeChart(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';

        /*** Pin code Analytics **/
        $pinCodeChartData = $this->pincodeChartData($startDate, $endDate);
        return response()->json([
            'pincode_chartData' => $pinCodeChartData,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTilesAppliedChart(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';

        /*** Tiles Analytics **/
        $finalTiles = $this->tilesTabularData($startDate,$endDate);

        return response()->json([
            'wall_count' => $finalTiles['wall_count'],
            'floor_count' => $finalTiles['floor_count'],
            'counter_count' => $finalTiles['counter_count'],
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getRoomCategory(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';

        $appliedTilesChartData = $this->categoryChartData($startDate,$endDate);

        return response()->json([
            'applied_tiles_chart_data' => $appliedTilesChartData,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTopTiles(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';

        /** Most used five tiles */
        $topFiveTiles = $this->topFiveTiles($startDate,$endDate);

        return response()->json([
            'body' => view('dashboard.top_tiles', compact('topFiveTiles'))->render(),
            'top_five_tiles' => $topFiveTiles,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTopRooms(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';

        /** Most used five rooms */
         $topFiveUsedRooms = $this->topFiveUsedRooms($startDate,$endDate);

        return response()->json([
            'body' => view('dashboard.top_rooms', compact('topFiveUsedRooms'))->render(),
            'top_five_rooms' => $topFiveUsedRooms,
        ]);
    }

    public function getTopShowRooms(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';

        /** Most top showrooms */
        $topShowRooms = $this->topShowrooms($startDate,$endDate);

        return response()->json([
            'body' => view('dashboard.top_show_rooms', compact('topShowRooms'))->render(),
            'top_five_show_rooms' => $topShowRooms,
        ]);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function pincodeChartData($startDate , $endDate): array
    {
        /*** Pincode Analytics **/
        $pincodeChart = Analytics::select(
            DB::raw("CONCAT(JSON_UNQUOTE(JSON_EXTRACT(zone, '$[0]')), ' Zone') as zone"),
            DB::raw('COUNT(*) as visits')
        )
            ->whereBetween(DB::raw('DATE(visited_at)'), [$startDate, $endDate])
            ->groupBy('zone')
            ->get();

        // Calculate total visits
        $totalVisitsPinCode = $pincodeChart->sum('visits');

        // Format data for Chart.js
        return [
            'labels' => $pincodeChart->pluck('zone')->toArray(),
            'values' => $pincodeChart->pluck('visits')->toArray(),
            'percentages' => $pincodeChart->map(function ($item) use ($totalVisitsPinCode) {
                return $totalVisitsPinCode > 0 ? round(($item->visits / $totalVisitsPinCode) * 100, 2) : 0;
            })->toArray(),
            'totalVisitsPinCode' => $totalVisitsPinCode
        ];
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function categoryChartData($startDate , $endDate): array
    {
        $categoryChart = Analytics::select(
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(category, '$[*]')) as category")
        )
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->get();


        $categories = [];
        foreach ($categoryChart as $data) {
            $catArray = json_decode($data->category, true);
            if (is_array($catArray)) {
                foreach ($catArray as $cat) {
                    $categories[] = ucfirst($cat); // Capitalize first letter
                }
            }
        }

        // Count occurrences of each category
        $categoryCounts = array_count_values($categories);
        $totalVisits = array_sum($categoryCounts);

        // Prepare data for Chart.js
        return [
            'labels' => array_keys($categoryCounts),
            'values' => array_values($categoryCounts),
            'percentages' => array_map(function($count) use ($totalVisits) {
                return $totalVisits > 0 ? round(($count / $totalVisits) * 100, 2) : 0;
            }, array_values($categoryCounts)),
            'total' => $totalVisits
        ];
    }


    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function roomChartData($startDate , $endDate): array
    {
        $roomData = Analytics::select(
            DB::raw('JSON_UNQUOTE(room) as rooms_json')
        )->whereBetween('visited_at', [$startDate, $endDate])
            ->get();

        $roomCounts = [];
        $totalVisitsRooms = 0;

        foreach ($roomData as $row) {
            $rooms = json_decode($row->rooms_json, true);

            if (!is_array($rooms)) continue;

            foreach ($rooms as $room) {
                $roomId = $room['room_id'];
                $roomName = $room['room_name'];

                if (isset($roomCounts[$roomName])) {
                    $roomCounts[$roomName] += 1;
                } else {
                    $roomCounts[$roomName] = 1;
                }
                $totalVisitsRooms++;
            }
        }

        // Prepare data for Donut Chart
        return [
            'labels' => array_keys($roomCounts), // Room Names
            'values' => array_values($roomCounts), // Visit Counts
            'percentages' => array_map(function ($count) use ($totalVisitsRooms) {
                return $totalVisitsRooms > 0 ? round(($count / $totalVisitsRooms) * 100, 2) : 0;
            }, array_values($roomCounts)),
            'totalRoomVisits' => $totalVisitsRooms
        ];

    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function tilesTabularData($startDate , $endDate): array
    {
        $tilesData = Analytics::select(
            DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json'),
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        $processedTiles = [];
        // Loop through DB results
        foreach ($tilesData as $row) {
            // Check if tiles_json is empty or null
            if (empty($row->tiles_json)) {
                continue; // Skip this row
            }

            // Decode JSON
            $tiles = json_decode($row->tiles_json, true);

            // Check if JSON decoding failed
            if (!is_array($tiles)) {
                dd("JSON decoding failed", json_last_error_msg(), $row->tiles_json);
            }

            foreach ($tiles as $tile) {
                $tileId = $tile["tile_id"] ?? null;
                $surface = $tile["surface"] ?? null;

                if (!$tileId) continue; // Skip invalid tile data

                if (isset($processedTiles[$tileId])) {
                    $processedTiles[$tileId]['used_count'] += 1;
                    if ($surface === "floor") {
                        $processedTiles[$tileId]['floor_count'] += 1;
                    }
                    if ($surface === "wall") {
                        $processedTiles[$tileId]['wall_count'] += 1;
                    }
                    if ($surface === "counter") {
                        $processedTiles[$tileId]['counter_count'] += 1;
                    }
                } else {
                    $tilesPhotoSize = Helper::getTileNameAndSurface($tileId);
                    $processedTiles[$tileId] = [
                        "name" => $tile["tile_name"] ?? "Unknown",
                        "photo" => $tilesPhotoSize['photo'] ?? "default.jpg",
                        "size" => $tilesPhotoSize['size'] ?? "Unknown",
                        "finish" => $tilesPhotoSize['finish'] ?? "Unknown",
                        "view_count" => 0,
                        "used_count" => 1,
                        "floor_count" => ($surface === "floor") ? 1 : 0,
                        "wall_count" => ($surface === "wall") ? 1 : 0,
                        "counter_count" => ($surface === "counter") ? 1 : 0
                    ];
                }
            }
        }

        $processedTiles = array_values($processedTiles);
        $wallCount = array_sum(array_column($processedTiles, 'wall_count'));
        $floorCount = array_sum(array_column($processedTiles, 'floor_count'));
        $counterCount = array_sum(array_column($processedTiles, 'counter_count'));

        return ['processedTiles'=> $processedTiles ,'wall_count' => $wallCount, 'floor_count' => $floorCount, 'counter_count' => $counterCount];
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function summaryPdfChart($startDate , $endDate): array
    {
        $sessionReachSummaryPage = Analytics::whereNotNull('unique_cart_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DATE(created_at) ASC')->get();

        $downloadPdf = UserPdfData::whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DATE(created_at) ASC')->get();

        // Fetch Summary Page Sessions
        $sessionData = Analytics::whereNotNull('unique_cart_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at) ASC')->pluck('count', 'date')->toArray();

        // Fetch PDF Downloads
        $pdfDownloadData = UserPdfData::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)') // ✅ Required Group By
            ->orderByRaw('DATE(created_at) ASC')
            ->pluck('count', 'date')
            ->toArray();

        // Convert start & end dates to Carbon instances
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Generate the complete date range with zero for missing dates
        $dateRange = [];
        $currentDate = clone $startDate; // Use `clone` to avoid modifying original instance

        while ($currentDate->lte($endDate)) {
            $formattedDate = $currentDate->format('d-M'); // Example: 18-Feb, 19-Feb, etc.
            $dbDate = $currentDate->format('Y-m-d'); // For matching DB records

            $dateRange[$formattedDate] = [
                'sessions' => $sessionData[$dbDate] ?? 0, // Fill missing dates with 0
                'pdf_downloads' => $pdfDownloadData[$dbDate] ?? 0
            ];
            $currentDate->addDay(); // Move to next date
        }

        // Prepare data for frontend
        $summaryPdfChartData = [
            'labels' => array_keys($dateRange),
            'sessionData' => array_column($dateRange, 'sessions'),
            'pdfDownloadData' => array_column($dateRange, 'pdf_downloads')
        ];
        return ['sessionReachSummaryPage' => $sessionReachSummaryPage->count(), 'downloadPdf' => $downloadPdf->count(),'summaryPdfChartData'=>$summaryPdfChartData];
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    protected function queryAnalyticsBuilder($startDate , $endDate): mixed
    {
        return Analytics::whereBetween('visited_at', [$startDate, $endDate]);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function topFiveTiles($startDate,$endDate): array
    {
        $tilesData = Analytics::select(
            DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json')
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        $processedTiles = [];
        $totalUsedCount = 0;

        // Loop through DB results
        foreach ($tilesData as $row) {
            if (empty($row->tiles_json)) continue; // Skip empty rows

            $tiles = json_decode($row->tiles_json, true);
            if (!is_array($tiles)) continue; // Skip invalid JSON

            foreach ($tiles as $tile) {
                $tileId = $tile["tile_id"] ?? null;
                if (!$tileId) continue;

                if (!isset($processedTiles[$tileId])) {
                    $tilesPhotoSize = Helper::getTileNameAndSurface($tileId);
                    $processedTiles[$tileId] = [
                        "name" => $tile["tile_name"] ?? "Unknown",
                        "size" => $tilesPhotoSize['size'] ?? "Unknown",
                        "surface" => $tilesPhotoSize['surface'] ?? "Unknown",
                        "photo" => $tilesPhotoSize['photo'] ?? "default.jpg",
                        "used_count" => 0,
                        "finish" => $tilesPhotoSize["finish"] ?? "Unknown",
                    ];
                }

                $processedTiles[$tileId]['used_count'] += 1;
                $totalUsedCount += 1;
            }
        }

        // Sort by most used
        usort($processedTiles, function ($a, $b) {
            return $b['used_count'] - $a['used_count'];
        });

        // Get top 5 tiles
        $topTiles = array_slice($processedTiles, 0, 5);

        // Calculate percentages
        foreach ($topTiles as &$tile) {
            $tile['percentage'] = ($totalUsedCount > 0)
                ? round(($tile['used_count'] / $totalUsedCount) * 100, 2)
                : 0;
        }

        return $topTiles;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function topFiveUsedRooms($startDate, $endDate): array
    {
        $roomData = Analytics::select(
            DB::raw('JSON_UNQUOTE(room) as rooms_json')
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        $processedRooms = [];
        $bgColors = ['bg-success', 'bg-warning', 'bg-info', 'bg-danger', 'bg-primary'];

        // Loop through room data
        foreach ($roomData as $row) {
            if (empty($row->rooms_json)) continue; // Skip empty rows

            $rooms = json_decode($row->rooms_json, true);
            if (!is_array($rooms)) continue; // Skip invalid JSON

            foreach ($rooms as $room) {
                $roomId = $room["room_id"] ?? null;
                $roomName = $room["room_name"] ?? "Unknown";

                if (!$roomId) continue;

                if (isset($processedRooms[$roomId])) {
                    $processedRooms[$roomId]['count'] += 1;
                } else {
                    $processedRooms[$roomId] = [
                        "name" => $roomName,
                        "category" => Helper::getRoomCatgory($roomId),
                        "count" => 1
                    ];
                }
            }
        }

        // Sort by most used
        usort($processedRooms, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Get the highest count to calculate percentages
        $maxCount = $processedRooms[0]['count'] ?? 1;

        // Assign background colors and percentages
        foreach ($processedRooms as $index => &$room) {
            $room['percentage'] = ($room['count'] / $maxCount) * 100;  // Calculate %
            $room['bg_color'] = $bgColors[$index] ?? 'bg-secondary';  // Assign fixed colors
        }

        return array_slice($processedRooms, 0, 5);
    }


    /**
     * @param $startDate
     * @param $endDate
     * @return Collection
     */
    protected function topShowrooms($startDate , $endDate): Collection
    {
        // Step 1: Fetch showroom_ids and count occurrences
        $showroomCounts = Analytics::where('user_logged_in', '!=', 'guest')
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->whereNotNull('showroom')
            ->pluck('showroom')
            ->map(function ($value) {
                return json_decode($value, true); // Convert JSON string to an array
            })
            ->flatten() // Merge all showroom IDs into a single array
            ->countBy() // Count occurrences of each showroom ID
            ->sortDesc(); // Sort by count descending

        $showroomIds = $showroomCounts->keys()->take(5)->toArray(); // Get top 5 showroom IDs

        // Step 2: Fetch details of the top 5 showrooms
        $topShowrooms = Showroom::whereIn('id', $showroomIds)
            ->get()
            ->keyBy('id'); // Organize it by ID for easier mapping

        // Step 3: Get the highest count to calculate percentages
        $maxCount = $showroomCounts->first() ?? 1; // Avoid division by zero

        // Step 4: Define background colors
        $bgColors = ['bg-success', 'bg-warning', 'bg-info', 'bg-danger', 'bg-primary'];

        // Step 5: Format the response
        return collect($showroomIds)->map(function ($id, $index) use ($topShowrooms, $showroomCounts, $bgColors, $maxCount) {
            return [
                'id' => $id,
                'name' => $topShowrooms[$id]->name ?? 'Unknown',
                'city' => $topShowrooms[$id]->city ?? 'N/A',
                'usage_count' => $showroomCounts[$id] ?? 0, // Get showroom count
                'percentage' => ($showroomCounts[$id] / $maxCount) * 100, // Calculate percentage
                'bg_color' => $bgColors[$index] ?? 'bg-secondary', // Assign fixed colors
            ];
        });
    }


    public function showDetails($type, Request $request)
    {
        // Get start_date and end_date from request or default to last 7 days
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Fetch data based on a type
        switch ($type) {
            case 'appliedTiles':
            case 'pincode':
                return view('dashboard.details', compact('startDate', 'endDate','type'));
            default:
                abort(404);
        }
    }

    /**
     * @param $startDate
     * @param $endDate
     */
    public function getDetailReport (Request $request)
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';
        $type = $request->input('type');

        // Fetch data based on a type
        switch ($type) {
            case 'pincode':
                $data = $this->pincodeDetails($startDate , $endDate);
                break;

            case 'appliedTiles':
                $data = $this->appliedTilesDetails($startDate , $endDate);
                break;
            default:
                abort(404);
        }

        return $data;
    }

    protected function pincodeDetails($startDate , $endDate)
    {
        $pinCodeDetails =  Analytics::select(
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(zone, "$[0]")) as zone'),
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(pincode, "$[0]")) as pincode'),
            DB::raw('COUNT(*) as visits'),
            DB::raw('MAX(visited_at) as last_visited_at')
            )
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy('zone', 'pincode')
            ->orderBy('zone')
            ->get();

        return response()->json([
            'body' => view('dashboard.pincode_details', compact('pinCodeDetails'))->render(),
            'pinCodeDetails' => $pinCodeDetails,
        ]);
    }

    protected function appliedTilesDetails($startDate , $endDate) 
    {
        $appliedTilesData = Analytics::select(
            DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json'),
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        return response()->json([
            'body' => view('dashboard.applied_tile_details', compact('appliedTilesData'))->render(),
            'appliedTilesData' => $appliedTilesData,
        ]);
    }

    protected function tilesDetails($startDate , $endDate)
    {
        $tilesData = Analytics::select(
            DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json'),
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        $processedTiles = [];
        // Loop through DB results
        foreach ($tilesData as $row) {
            // Check if tiles_json is empty or null
            if (empty($row->tiles_json)) {
                continue; // Skip this row
            }

            // Decode JSON
            $tiles = json_decode($row->tiles_json, true);

            // Check if JSON decoding failed
            if (!is_array($tiles)) {
                dd("JSON decoding failed", json_last_error_msg(), $row->tiles_json);
            }

            foreach ($tiles as $tile) {
                $tileId = $tile["tile_id"] ?? null;
                $surface = $tile["surface"] ?? null;

                if (!$tileId) continue; // Skip invalid tile data

                if (isset($processedTiles[$tileId])) {
                    $processedTiles[$tileId]['used_count'] += 1;
                    if ($surface === "floor") {
                        $processedTiles[$tileId]['floor_count'] += 1;
                    }
                    if ($surface === "wall") {
                        $processedTiles[$tileId]['wall_count'] += 1;
                    }
                    if ($surface === "counter") {
                        $processedTiles[$tileId]['counter_count'] += 1;
                    }
                } else {
                    $tilesPhotoSize = Helper::getTileNameAndSurface($tileId);
                    $processedTiles[$tileId] = [
                        "name" => $tile["tile_name"] ?? "Unknown",
                        "photo" => $tilesPhotoSize['photo'] ?? "default.jpg",
                        "size" => $tilesPhotoSize['size'] ?? "Unknown",
                        "finish" => $tilesPhotoSize['finish'] ?? "Unknown",
                        "view_count" => 0,
                        "used_count" => 1,
                        "floor_count" => ($surface === "floor") ? 1 : 0,
                        "wall_count" => ($surface === "wall") ? 1 : 0,
                        "counter_count" => ($surface === "counter") ? 1 : 0,
                        "category" => $tilesPhotoSize['category'],
                        "color" => $tilesPhotoSize['color'],
                        "innovation" => $tilesPhotoSize['innovation'] ?? "-",
                    ];
                }
            }
        }

        $processedTiles = array_values($processedTiles);
        $wallCount = array_sum(array_column($processedTiles, 'wall_count'));
        $floorCount = array_sum(array_column($processedTiles, 'floor_count'));
        $counterCount = array_sum(array_column($processedTiles, 'counter_count'));

        // Add total counts inside each tile in processedTiles
        foreach ($processedTiles as &$tile) {
            $tile['total_wall_count'] = $wallCount > 0 ? $wallCount : "-";
            $tile['total_floor_count'] = $floorCount > 0 ? $floorCount : "-";
            $tile['total_counter_count'] = $counterCount > 0 ? $counterCount : "-";

            // Also handle individual tile counts
            $tile['wall_count'] = $tile['wall_count'] > 0 ? $tile['wall_count'] : "-";
            $tile['floor_count'] = $tile['floor_count'] > 0 ? $tile['floor_count'] : "-";
            $tile['counter_count'] = $tile['counter_count'] > 0 ? $tile['counter_count'] : "-";
        }


        return response()->json([
            'body' => view('dashboard.tile_details', compact('processedTiles'))->render(),
            'processedTiles' => $processedTiles,
        ]);
    }
}
