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

    public function getAnalyticsResult(Request $request): JsonResponse
    {
        $startDate = $request->input('startDate') . ' 00:00:00';
        $endDate = $request->input('endDate') . ' 23:59:59';

        /*** Pin code Analytics **/
        $pinCodeChartData = $this->pincodeChartData($startDate, $endDate);

        /*** Category Analytics **/
        $categoryChartData = $this->categoryChartData($startDate,$endDate);

        /*** Rooms Analytics **/
        $roomChartData = $this->roomChartData($startDate,$endDate);

        /*** Tiles Analytics **/
        $finalTiles = $this->tilesTabularData($startDate,$endDate);

        $queryAnalyticsBuilder = $this->queryAnalyticsBuilder($startDate,$endDate);

        //TotalUsers
        $totalGuestUsers = $queryAnalyticsBuilder->where('user_logged_in','guest')->count();
        $totalLoggedInUsers = $queryAnalyticsBuilder->where('user_logged_in','!=' , 'guest')->count();
        $totalUsers = $totalGuestUsers + $totalLoggedInUsers;

        /** Summary PDF Analytics */
        $showRoomsUsers = $queryAnalyticsBuilder->where('user_logged_in','!=',"guest")->whereNotNull('showroom')->get();

        $totalSession = Analytics::whereBetween('visited_at', [$startDate, $endDate])->get();
        $summaryPDFData = $this->summaryPdfChart($startDate,$endDate);

        /** Most used five tiles */
        $topFiveTiles = $this->topFiveTiles($startDate,$endDate);

        /** Most used five rooms */
        $topFiveUsedRooms = $this->topFiveUsedRooms($startDate,$endDate);

        /** Most top showrooms */
        $topShowRooms = $this->topShowrooms($startDate,$endDate);

        return response()->json([
            'pincode_chartData' => $pinCodeChartData['pincode_chartData'],
            'category_chart_data' => $categoryChartData,
            'pincodeTabularData' => $pinCodeChartData['pincodeTabularData'],
            'tilesTabularData' => $finalTiles['processedTiles'],
            'room_chart_data' => $roomChartData,
            'total_users' => $totalUsers,
            'total_logged_in_users' => $totalLoggedInUsers,
            'total_guest_users' => $totalGuestUsers,
            'total_session' => $totalSession->count(),
            'showroom_users' => $showRoomsUsers,
            'session_reach_summary_page' => $summaryPDFData['sessionReachSummaryPage'],
            'download_pdf' => $summaryPDFData['downloadPdf'],
            'summary_pdf_chart_data' => $summaryPDFData['summaryPdfChartData'],
            'wall_count' => $finalTiles['wall_count'],
            'floor_count' => $finalTiles['floor_count'],
            'counter_count' => $finalTiles['counter_count'],
            'top_five_tiles' => $topFiveTiles,
            'top_five_rooms' => $topFiveUsedRooms,
            'top_showrooms' => $topShowRooms
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

        $pincodeTabularData = Analytics::select(
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(zone, "$[0]")) as zone'),
            DB::raw('JSON_UNQUOTE(JSON_EXTRACT(pincode, "$[0]")) as pincode'),
            DB::raw('COUNT(*) as visits')
        )->whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy('zone', 'pincode')
            ->orderBy('zone')
            ->get();

        // Format data for Chart.js
        $pincode_chartData = [
            'labels' => $pincodeChart->pluck('zone')->toArray(),
            'values' => $pincodeChart->pluck('visits')->toArray(),
            'percentages' => $pincodeChart->map(function ($item) use ($totalVisitsPinCode) {
                return $totalVisitsPinCode > 0 ? round(($item->visits / $totalVisitsPinCode) * 100, 2) : 0;
            })->toArray(),
            'totalVisitsPinCode' => $totalVisitsPinCode
        ];

        return ['pincodeTabularData'=>$pincodeTabularData , 'pincode_chartData' =>$pincode_chartData ];
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
    protected function topFiveUsedRooms($startDate , $endDate): array
    {
        $roomData = Analytics::select(
            DB::raw('JSON_UNQUOTE(room) as rooms_json')
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        $processedRooms = [];

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

        return array_slice($processedRooms, 0, 5);

    }

    /**
     * @param $startDate
     * @param $endDate
     * @return Collection
     */
    protected function topShowrooms($startDate , $endDate): Collection
    {
        // Fetch showroom_ids from analytics table
        $showroomIds = Analytics::where('user_logged_in', '!=', 'guest')
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->whereNotNull('showroom')
            ->pluck('showroom')
            ->map(function ($value) {
                return json_decode($value, true); // Convert JSON string to an array
            })
            ->flatten() // Merge all showroom IDs into a single array
            ->countBy() // Count occurrences of each showroom ID
            ->sortDesc() // Sort by count descending
            ->take(5) // Get top 5 showrooms by usage
            ->keys() // Extract showroom IDs
            ->toArray();

        // Step 2: Fetch details of the top 5 showrooms
        $topShowrooms = Showroom::whereIn('id', $showroomIds)
            ->get()
            ->keyBy('id'); // Organize it by ID for easier mapping

        // Step 3: Format the response
        return collect($showroomIds)->map(function ($id) use ($topShowrooms) {
            return [
                'id' => $id,
                'name' => $topShowrooms[$id]->name ?? 'Unknown',
                'city' => $topShowrooms[$id]->city ?? 'N/A',
                'usage_count' => Analytics::whereJsonContains('showroom', (string) $id)->count(), // Count exact occurrences
            ];
        });
    }

    public function showDetails($type)
    {
        if ($type === 'zone') {
            /*** Pin code Analytics **/
            $pinCodeChartData = Analytics::select(
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(zone, "$[0]")) as zone'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(pincode, "$[0]")) as pincode'),
                DB::raw('COUNT(*) as visits')
            )->groupBy('zone', 'pincode')
                ->orderBy('zone')
                ->get();

        } elseif ($type === 'tiles') {
            $data = Tile::select('tile_name', 'views_count', 'usage_count')->get();
        } else {
            abort(404, 'Invalid Type');
        }

        return view('dashboard.details', compact('pinCodeChartData', 'type'));
    }
}
