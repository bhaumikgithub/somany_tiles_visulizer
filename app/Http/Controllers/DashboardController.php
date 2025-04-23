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
use App\Room2d;
use App\Panorama;

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

        $response = [];

        //TotalUsers
        $totalGuestUsers = Analytics::whereBetween('visited_at', [$startDate, $endDate])->where('user_logged_in', 'guest')->count();
        $totalLoggedInUsers = Analytics::whereBetween('visited_at', [$startDate, $endDate])->where('user_logged_in', '!=', 'guest')->count();
        $totalUsers = $totalGuestUsers + $totalLoggedInUsers;


        /** Summary PDF Analytics */
        $totalSession = $totalSession = $this->totalSession($startDate , $endDate);
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
    protected function categoryChartData($startDate, $endDate): array
    {
        $categoryChart = Analytics::select('category')
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->get();
    
        $categories = [];
    
        foreach ($categoryChart as $data) {
            $catArray = json_decode($data->category, true);
    
            if (is_array($catArray)) {
                foreach ($catArray as $cat) {
                    if (!empty($cat['category_name']) && !empty($cat['category_type'])) {
                        $name = ucwords(str_replace('-', ' ', $cat['category_name']));
                        $type = strtoupper($cat['category_type']);
                        if($cat['category_name'] === "users_room"){
                            $label = "Users Room";
                        } else {
                            $label =  "{$name} ({$type})";
                        }
                        
                        $label = $label;
                        $categories[] = $label;
                    }
                }
            }
        }
    
        // Count each category label
        $categoryCounts = array_count_values($categories);
        $totalVisits = array_sum($categoryCounts);
        
        // Prepare data for Chart.js
        return [
            'labels' => array_keys($categoryCounts),
            'values' => array_values($categoryCounts),
            'percentages' => array_map(function ($count) use ($totalVisits) {
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
        $sessionReachToSummaryPage = $this->sessionReachToSummaryPage($startDate , $endDate);

        $downloadPdf = $this->downloadPDF($startDate , $endDate);
        // Fetch Summary Page Sessions
        $sessionData = Analytics::whereNotNull('unique_cart_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at) ASC')->pluck('count', 'date')->toArray();

        // Fetch PDF Downloads
        $pdfDownloadData = UserPdfData::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)') // Required Group By
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
        return ['sessionReachSummaryPage' => $sessionReachToSummaryPage->count(), 'downloadPdf' => $downloadPdf->count(),'summaryPdfChartData'=>$summaryPdfChartData];
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

    protected function topFiveTiles($startDate, $endDate): array
    {
        $tilesData = Analytics::select(
            DB::raw('JSON_UNQUOTE(viewed_tiles) as viewed'),
            DB::raw('JSON_UNQUOTE(used_tiles) as used')
        )->whereBetween('visited_at', [$startDate, $endDate])->get();
    
        $processedTiles = [];
        $totalCount = 0;
    
        foreach ($tilesData as $row) {
            $allTiles = [];
    
            // Decode viewed tiles
            $viewed = json_decode($row->viewed ?? '[]', true);
            if (is_array($viewed)) {
                $allTiles = array_merge($allTiles, $viewed);
            }
    
            // Decode used tiles
            $used = json_decode($row->used ?? '[]', true);
            if (is_array($used)) {
                $allTiles = array_merge($allTiles, $used);
            }
    
            foreach ($allTiles as $tile) {
                $tileId = $tile["tile_id"] ?? null;
                $tileName = $tile["tile_name"] ?? null;
    
                if (!$tileId || !$tileName) continue;
    
                // Use tile_id as unique key
                if (!isset($processedTiles[$tileName])) {
                    $tileDetails = Helper::getTileNameAndSurface($tileId);
    
                    $processedTiles[$tileName] = [
                        "tile_id" => $tileId,
                        "name" => $tileName,
                        "size" => $tileDetails['size'] ?? "",
                        "surface" => $tileDetails['surface'] ?? "Unknown",
                        "photo" => $tileDetails['photo'] ?? "default.jpg",
                        "used_count" => 0,
                        "finish" => $tileDetails["finish"] ?? "Unknown",
                    ];
                }
    
                // Increment usage count
                $processedTiles[$tileName]['used_count'] += 1;
                $totalCount += 1;
    
                // Prefer 'wall' as surface if found
                if (($tileDetails['surface'] ?? '') === 'wall') {
                    $processedTiles[$tileName]['surface'] = 'wall';
                }
            }
        }
    
        // Sort tiles by usage count (descending)
        usort($processedTiles, function ($a, $b) {
            return $b['used_count'] <=> $a['used_count'];
        });
    
        // Get top 5 tiles
        $topTiles = array_slice($processedTiles, 0, 5);
    
        // Add usage percentage
        foreach ($topTiles as &$tile) {
            $tile['percentage'] = ($totalCount > 0)
                ? round(($tile['used_count'] / $totalCount) * 100, 2)
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

        foreach ($roomData as $row) {
            if (empty($row->rooms_json)) continue;

            $rooms = json_decode($row->rooms_json, true);
            if (!is_array($rooms)) continue;

            foreach ($rooms as $room) {
                $roomId = $room["room_id"] ?? null;
                $roomName = $room["room_name"] ?? "Unknown";
                $roomType = $room["room_type"] ?? null;

                // Special case: users_room (ai-studio)
                if ($roomName === "users_room" && $roomType === "ai-studio") {
                    $key = "users_room|ai-studio";
                    if (isset($processedRooms[$key])) {
                        $processedRooms[$key]['count'] += 1;
                    } else {
                        $processedRooms[$key] = [
                            "name" => "User's Room",
                            "category" => null, // or 'AI Studio' if you want
                            "count" => 1
                        ];
                    }
                    continue; // Skip regular processing for users_room
                }

                // Handle regular rooms with ID
                $fromApp = ($roomType === "2d") ? "2d" : "3d";

                if (!$roomId) continue;

                if (isset($processedRooms[$roomId])) {
                    $processedRooms[$roomId]['count'] += 1;
                } else {
                    $processedRooms[$roomId] = [
                        "name" => $roomName . " (" . $fromApp . ") ",
                        "category" => Helper::getRoomCatgory($roomId, $roomType),
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

        foreach ($processedRooms as $index => &$room) {
            $room['percentage'] = ($room['count'] / $maxCount) * 100;
            $room['bg_color'] = $bgColors[$index] ?? 'bg-secondary';
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
            case 'pincode':
            case 'appliedTiles':
            case 'roomCategories':
            case 'tiles':
            case 'rooms':
            case 'showroom':
            case 'pdf':
            case 'ai-studio':
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

            case 'roomCategories':
                $data = $this->roomCategoriesDetails($startDate , $endDate);
                break;

            case 'tiles':
                $data = $this->tilesDetails($startDate , $endDate);
                break;

            case 'rooms':
                $data = $this->roomDetails($startDate , $endDate);
                break;

            case 'showroom':
                $data = $this->showRoomDetails($startDate , $endDate);
                break;

            case 'pdf':
                $data = $this->sessionPDFDetails($startDate , $endDate);
                break;

            case 'ai-studio':
                $data = $this->aiStudioDetailsPage($startDate , $endDate);
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

    protected function appliedTilesDetails($startDate, $endDate)
    {
        $analyticsData = Analytics::select(
            DB::raw('JSON_UNQUOTE(used_tiles) as used_tiles_json')
        )
        ->whereBetween('visited_at', [$startDate, $endDate])
        ->get();
    
        $processedTiles = [];
    
        foreach ($analyticsData as $row) {
            $usedTiles = json_decode($row->used_tiles_json, true) ?: [];
    
            foreach ($usedTiles as $tile) {
                $tileId = $tile["tile_id"] ?? null;
                $tileName = $tile["tile_name"] ?? null;
                $surface = strtolower(trim($tile["surface"] ?? ""));
                $zone = strtolower(trim($tile["zone"] ?? "unknown"));
    
                if (!$tileId || !$tileName || !$surface) continue;
    
                $key = $tileName;
    
                if (!isset($processedTiles[$key])) {
                    $tileMeta = Helper::getTileNameAndSurface($tileId);
    
                    $processedTiles[$key] = [
                        "name" => $tileMeta['name'] ?? $tileName,
                        "photo" => $tileMeta['photo'] ?? "default.jpg",
                        "size" => $tileMeta['size'] ?? "Unknown",
                        "finish" => $tileMeta['finish'] ?? "Unknown",
                        "category" => $tileMeta['category'] ?? "-",
                        "color" => $tileMeta['color'] ?? "-",
                        "innovation" => $tileMeta['innovation'] ?? "-",
                        "used_count" => 0,
                        "wall_count" => 0,
                        "floor_count" => 0,
                        "counter_count" => 0,
                        "zone_used_count" => [],
                    ];
                }
    
                // Surface counts
                switch ($surface) {
                    case "wall":
                        $processedTiles[$key]['wall_count'] += 1;
                        break;
                    case "floor":
                        $processedTiles[$key]['floor_count'] += 1;
                        break;
                    case "counter":
                        $processedTiles[$key]['counter_count'] += 1;
                        break;
                }
    
                // Used count
                $processedTiles[$key]['used_count'] += 1;
                $processedTiles[$key]['zone_used_count'][$zone] = ($processedTiles[$key]['zone_used_count'][$zone] ?? 0) + 1;
            }
        }
    
        // Totals
        $wallCount = array_sum(array_column($processedTiles, 'wall_count'));
        $floorCount = array_sum(array_column($processedTiles, 'floor_count'));
        $counterCount = array_sum(array_column($processedTiles, 'counter_count'));
    
        foreach ($processedTiles as &$tile) {
            $tile['total_wall_count'] = $wallCount ?: "-";
            $tile['total_floor_count'] = $floorCount ?: "-";
            $tile['total_counter_count'] = $counterCount ?: "-";
    
            foreach (['wall_count', 'floor_count', 'counter_count', 'used_count'] as $field) {
                $tile[$field] = $tile[$field] ?: "-";
            }
    
            $tile['zone_used_count'] = empty($tile['zone_used_count']) ? new \stdClass() : $tile['zone_used_count'];
        }
        
        return response()->json([
            'body' => view('dashboard.applied_tile_details', compact('processedTiles'))->render(),
            'processedTiles' => $processedTiles,
        ]);
    }

    protected function roomCategoriesDetails($startDate, $endDate)
    {
        $categories = Analytics::whereBetween('visited_at', [$startDate, $endDate])
            ->pluck('category') // Only 'category' column
            ->filter() // Remove null
            ->map(fn($cat) => json_decode($cat, true)) // Decode each JSON array
            ->flatten(1) // Flatten outer array, keeping each category item
            ->toArray();

        // Group and count by category_name + category_type
        $categoryCount = collect($categories)->groupBy(function ($item) {
            return strtolower($item['category_name'] . '|' . $item['category_type']);
        })->map->count();

        // Format the result
        $categoryData = $categoryCount->map(function ($count, $key) {
            [$name, $type] = explode('|', $key);
        
            // Handle special case for users_room
            if ($name === 'users_room') {
                return [
                    "category_name" => "User's Room",
                    "visits" => $count
                ];
            }
        
            return [
                "category_name" => ucwords(str_replace("-", " ", $name)) . " (" . strtoupper($type) . ")",
                "visits" => $count
            ];
        })->values();

        return response()->json([
            'body' => view('dashboard.room_categories_details', compact('categoryData'))->render(),
            'categoryData' => $categoryData,
        ]);
    }

    protected function tilesDetails($startDate, $endDate)
    {
        $analyticsData = Analytics::select(
            DB::raw('JSON_UNQUOTE(viewed_tiles) as viewed_tiles_json')
        )
        ->whereBetween('visited_at', [$startDate, $endDate])
        ->get();
    
        $processedTiles = [];
    
        foreach ($analyticsData as $row) {
            $viewedTiles = json_decode($row->viewed_tiles_json, true) ?: [];
    
            foreach ($viewedTiles as $tile) {
                $tileId = $tile["tile_id"] ?? null;
                $tileName = $tile["tile_name"] ?? null;
                $surface = strtolower(trim($tile["surface"] ?? ""));
                $zone = strtolower(trim($tile["zone"] ?? "unknown"));
    
                if (!$tileId || !$tileName || !$surface) continue;
    
                $key = $tileName;
    
                if (!isset($processedTiles[$key])) {
                    $tileMeta = Helper::getTileNameAndSurface($tileId);
    
                    $processedTiles[$key] = [
                        "name" => $tileMeta['name'] ?? $tileName,
                        "photo" => $tileMeta['photo'] ?? "default.jpg",
                        "size" => $tileMeta['size'] ?? "Unknown",
                        "finish" => $tileMeta['finish'] ?? "Unknown",
                        "category" => $tileMeta['category'] ?? "-",
                        "color" => $tileMeta['color'] ?? "-",
                        "innovation" => $tileMeta['innovation'] ?? "-",
                        "view_count" => 0,
                        "wall_count" => 0,
                        "floor_count" => 0,
                        "counter_count" => 0,
                        "zone_view_count" => [],
                    ];
                }
    
                // Surface counts (only viewed tiles)
                switch ($surface) {
                    case "wall":
                        $processedTiles[$key]['wall_count'] += 1;
                        break;
                    case "floor":
                        $processedTiles[$key]['floor_count'] += 1;
                        break;
                    case "counter":
                        $processedTiles[$key]['counter_count'] += 1;
                        break;
                }
    
                // View count
                $processedTiles[$key]['view_count'] += 1;
                $processedTiles[$key]['zone_view_count'][$zone] = ($processedTiles[$key]['zone_view_count'][$zone] ?? 0) + 1;
            }
        }
    
        // Totals
        $wallCount = array_sum(array_column($processedTiles, 'wall_count'));
        $floorCount = array_sum(array_column($processedTiles, 'floor_count'));
        $counterCount = array_sum(array_column($processedTiles, 'counter_count'));
    
        foreach ($processedTiles as &$tile) {
            $tile['total_wall_count'] = $wallCount ?: "-";
            $tile['total_floor_count'] = $floorCount ?: "-";
            $tile['total_counter_count'] = $counterCount ?: "-";
    
            foreach (['wall_count', 'floor_count', 'counter_count', 'view_count'] as $field) {
                $tile[$field] = $tile[$field] ?: "-";
            }
    
            $tile['zone_view_count'] = empty($tile['zone_view_count']) ? new \stdClass() : $tile['zone_view_count'];
        }
    
        return response()->json([
            'body' => view('dashboard.tiles_details', compact('processedTiles'))->render(),
            'processedTiles' => $processedTiles,
        ]);
    }
    
    protected function roomDetails($startDate , $endDate)
    {
         // Fetch room data from Analytics
        $roomData = Analytics::select(
            DB::raw('JSON_UNQUOTE(room) as rooms_json')
        )->whereBetween('visited_at', [$startDate, $endDate])
            ->get();
        

        $roomIds = [];
        foreach ($roomData as $row) {
            if (empty($row->rooms_json)) continue; // Skip empty JSON

            $rooms = json_decode($row->rooms_json, true);
            if (!is_array($rooms)) continue;

            foreach ($rooms as $room) {
                if ($room['room_type'] === '2d') {
                    $room2dIds[] = $room['room_id'];
                } elseif ($room['room_type'] === 'panorama') {
                    $panoramaIds[] = $room['room_id'];
                }
            }
        }
        
        $roomTypes = [];

        if (!empty($room2dIds)) {
            $roomTypes2d = Room2d::whereIn('id', $room2dIds)
                ->pluck('type', 'id')
                ->toArray();
            $roomTypes += $roomTypes2d;
        }

        if (!empty($panoramaIds)) {
            $roomTypesPanorama = Panorama::whereIn('id', $panoramaIds)
                ->pluck('type', 'id')
                ->toArray();
            $roomTypes += $roomTypesPanorama;
        }

        $processedRooms = [];

        foreach ($roomData as $row) {
            if (empty($row->rooms_json)) continue;

            $rooms = json_decode($row->rooms_json, true);
            if (!is_array($rooms)) continue;

            foreach ($rooms as $room) {
                $roomName = $room["room_name"] ?? "Unknown";
                $roomId = $room["room_id"] ?? null;
                $fromApp = ($room['room_type'] === "2d") ? "2d" : "3d";

                // Fetch room_type from room2ds table
                $roomType = $roomId ? ($roomTypes[$roomId] ?? "Unknown") : "Unknown";

                // Special case for users_room
                if ($roomName === 'users_room') {
                    $key = 'users_room'; // Group by simple key
                    if (!isset($processedRooms[$key])) {
                        $processedRooms[$key] = [
                            "room_name" => "User's Room",
                            "category_name" => null, // Or you can omit this key
                            "used_count" => 0,
                            "from_app" => null, // Optional: can omit if not needed
                        ];
                    }
                    $processedRooms[$key]["used_count"] += 1;
                    continue; // Skip normal logic for this case
                }

                $key = $roomName . "|" . $roomType;

                if (!isset($processedRooms[$key])) {
                    $processedRooms[$key] = [
                        "room_name" => $roomName . " (" . $fromApp . ") ",
                        "category_name" => ucwords($roomType),
                        "used_count" => 0,
                        "from_app" => $fromApp,
                    ];
                }

                $processedRooms[$key]["used_count"] += 1;
            }
        }

        // Convert associative array to indexed array for JSON response
        $rooms = array_values($processedRooms);

        return response()->json([
            'body' => view('dashboard.rooms_details', compact('rooms'))->render(),
            'rooms' => $rooms,
        ]);
    }

    protected function showRoomDetails($startDate , $endDate)
    {
        $totalSessions = $this->totalSession($startDate , $endDate);
        $sessionReachToSummaryPage = $this->sessionReachToSummaryPage($startDate , $endDate);

        $totalSessionCount = $totalSessions->count();
        $summaryPageCount = $sessionReachToSummaryPage->count();


        // Get unique showrooms
        $showroomData = [];
        $customerList = []; // Collect all customers to get total unique customers

        // Loop through sessions and extract showroom-wise details
        foreach ($totalSessions as $session) {
            $showroomIds = json_decode($session->showroom, true); // Convert showroom JSON string to array
            if (!is_array($showroomIds)) {
                continue; // Skip if decoding fails
            }

            foreach ($showroomIds as $showroomId) {
                // 🔹 Ensure showroom ID exists in the array before accessing it
                if (!isset($showroomData[$showroomId])) {
                    $showroomDetails = Helper::getShowroomDetails($showroomId); // Fetch showroom details
                    if (!$showroomDetails) {
                        continue; // Skip if no showroom details are found
                    }
                    // Initialize showroom data
                    $showroomData[$showroomId] = [
                        'showroom_id' => $showroomId,
                        'name' => $showroomDetails['name'] ?? 'Unknown Showroom',
                        'city' => $showroomDetails['city'] ?? 'Unknown City',
                        'session_created' => 0,
                        'summary_page' => 0,
                        'customers' => [],
                    ];
                }

                // Increment session count
                $showroomData[$showroomId]['session_created']++;

                // Count summary page sessions per showroom
                if (!is_null($session->unique_cart_id)) {
                    $showroomData[$showroomId]['summary_page']++;
                }

                // Extract customers (logged-in users or guest)
                $userLoggedIn = json_decode($session->user_logged_in, true);
                if (!empty($userLoggedIn)) {
                    $showroomData[$showroomId]['customers'] = array_merge($showroomData[$showroomId]['customers'], (array) $userLoggedIn);
                } else {
                    $showroomData[$showroomId]['customers'][] = 'Guest';
                }
            }
        }

        // Finalize customer count per showroom
        foreach ($showroomData as &$showroom) {
            $showroom['customers'] = count(array_unique($showroom['customers']));
        }

        // Get total unique customers across all showrooms
        $totalCustomers = count(array_unique(array_merge(...array_map(fn($x) => (array) $x['customers'], $showroomData))));

        //dd($totalSessionCount,$summaryPageCount,$totalCustomers);
        // Format and return the final response
        return response()->json([
            'body' => view('dashboard.showroom_details', compact('showroomData', 'totalSessionCount', 'summaryPageCount', 'totalCustomers'))->render(),
            'showroomData' => array_values($showroomData), // Convert associative array to indexed array
            'totalSessionCount' => $totalSessionCount,
            'summaryPageCount' => $summaryPageCount,
            'totalCustomers' => $totalCustomers,
        ]);
    }

    protected function totalSession($startDate , $endDate)
    {
        return Analytics::whereBetween('visited_at', [$startDate, $endDate])->get();
    }

    protected function sessionReachToSummaryPage($startDate , $endDate) 
    {
        return Analytics::whereNotNull('unique_cart_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DATE(created_at) ASC')->get();

    }

    protected function downloadPDF($startDate, $endDate) 
    {
        return UserPdfData::whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DATE(created_at) ASC')->get();
        
    }

    protected function sessionPDFDetails($startDate, $endDate) 
    {
        // Count PDF downloads
        $pdfDownloads = UserPdfData::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at','DESC')->get();
        return response()->json([
            'body' => view('dashboard.pdf_session_details', compact('pdfDownloads'))->render(),
            'pdfDownloads' => $pdfDownloads,
        ]);

    }

    protected function aiStudioDetailsPage($startDate , $endDate)
    {
        $aiStudioSummary = Analytics::whereBetween('visited_at', [$startDate, $endDate])->whereJsonContains('room','ai-studio')->get();
        $totalAISession = $aiStudioSummary->count();
        // Get all unique_cart_ids from ai-studio sessions
        $cartIds = Analytics::whereBetween('visited_at', [$startDate, $endDate])
            ->where('room', 'ai-studio')
            ->whereNotNull('unique_cart_id')
            ->pluck('unique_cart_id');

        // Get the count from user_pdf_data
        $pdfsFromUserPdfDataCount = DB::table('user_pdf_data')->whereIn('unique_id', $cartIds)->count();
        $reachToSummaryPage = $cartIds->count();
        
        return response()->json([
            'body' => view('dashboard.ai_studio_details', compact('totalAISession','pdfsFromUserPdfDataCount','reachToSummaryPage'))->render(),
            'totalAISession' => $totalAISession,
            'pdfsFromUserPdfDataCount' => $pdfsFromUserPdfDataCount,
            'reachToSummaryPage' => $reachToSummaryPage,
        ]);
        
    }

}
