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

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    // protected function topFiveTiles($startDate,$endDate): array
    // {
    //     $tilesData = Analytics::select(
    //         DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json')
    //     )->whereBetween('visited_at', [$startDate, $endDate])->get();

    //     $processedTiles = [];
    //     $totalUsedCount = 0;
        
    //     // Loop through DB results
    //     foreach ($tilesData as $row) {
    //         if (empty($row->tiles_json)) continue; // Skip empty rows

    //         $tiles = json_decode($row->tiles_json, true);
    //         if (!is_array($tiles)) continue; // Skip invalid JSON

    //         foreach ($tiles as $tile) {
    //             $tileId = $tile["tile_id"] ?? null;
    //             if (!$tileId) continue;

    //             if (!isset($processedTiles[$tileId])) {
    //                 $tilesPhotoSize = Helper::getTileNameAndSurface($tileId);
    //                 $processedTiles[$tileId] = [
    //                     "id" => $tileId,
    //                     "name" => $tile["tile_name"] ?? "Unknown",
    //                     "size" => $tilesPhotoSize['size'] ?? "Unknown",
    //                     "surface" => $tilesPhotoSize['surface'] ?? "Unknown",
    //                     "photo" => $tilesPhotoSize['photo'] ?? "default.jpg",
    //                     "used_count" => 0,
    //                     "finish" => $tilesPhotoSize["finish"] ?? "Unknown",
    //                 ];
    //             }

    //             $processedTiles[$tileId]['used_count'] += 1;
    //             $totalUsedCount += 1;
    //         }
    //     }

    //     // Sort by most used
    //     usort($processedTiles, function ($a, $b) {
    //         return $b['used_count'] - $a['used_count'];
    //     });

    //     // Get top 5 tiles
    //     $topTiles = array_slice($processedTiles, 0, 5);

    //     // Calculate percentages
    //     foreach ($topTiles as &$tile) {
    //         $tile['percentage'] = ($totalUsedCount > 0)
    //             ? round(($tile['used_count'] / $totalUsedCount) * 100, 2)
    //             : 0;
    //     }
    //     dd($topTiles);
    //     return $topTiles;
    // }
    protected function topFiveTiles($startDate, $endDate): array
    {
        $tilesData = Analytics::select(
            DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json')
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        $processedTiles = [];
        $totalUsedCount = 0;

        foreach ($tilesData as $row) {
            if (empty($row->tiles_json)) continue;

            $tiles = json_decode($row->tiles_json, true);
            if (!is_array($tiles)) continue;

            foreach ($tiles as $tile) {
                $tileId = $tile["tile_id"] ?? null;
                $tileName = $tile["tile_name"] ?? null;
                if (!$tileId || !$tileName) continue;

                // Fetch tile details
                $tileDetails = Helper::getTileNameAndSurface($tileId);

                // Group by tile name
                if (!isset($processedTiles[$tileName])) {
                    $processedTiles[$tileName] = [
                        "name" => $tileName,
                        "size" => $tileDetails['size'] ?? "Unknown",
                        "surface" => $tileDetails['surface'] ?? "Unknown", // Could be "wall", "floor", or overwrite later if needed
                        "photo" => $tileDetails['photo'] ?? "default.jpg",
                        "used_count" => 0,
                        "finish" => $tileDetails["finish"] ?? "Unknown",
                    ];
                }

                // Add usage count
                $processedTiles[$tileName]['used_count'] += 1;
                $totalUsedCount += 1;

                // Optional: prefer wall surface in display if exists
                if ($tileDetails['surface'] === 'wall') {
                    $processedTiles[$tileName]['surface'] = 'wall';
                }
            }
        }

        // Sort by usage
        usort($processedTiles, function ($a, $b) {
            return $b['used_count'] <=> $a['used_count'];
        });

        // Top 5 tiles
        $topTiles = array_slice($processedTiles, 0, 5);

        // Add percentages
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
            case 'pincode':
            case 'appliedTiles':
            case 'roomCategories':
            case 'tiles':
            case 'rooms':
            case 'showrooms':
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

            case 'showrooms':
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

    // protected function appliedTilesDetails($startDate , $endDate)
    // {
    //     $tilesData = Analytics::select(
    //         DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json')
    //     )->whereBetween('visited_at', [$startDate, $endDate])->get();
    
    //     $appliedTiles = [];
    
    //     foreach ($tilesData as $row) {
    //         if (empty($row->tiles_json)) continue; // Skip empty values
    
    //         $tiles = json_decode($row->tiles_json, true);
    //         if (!is_array($tiles)) continue; // Skip invalid JSON
    
    //         foreach ($tiles as $tile) {
    //             $tileId = $tile["tile_id"] ?? null;
    //             $roomName = $tile["room_name"] ?? "-";
    //             $roomType = $tile["room_type"] ?? "-";  // Now using room_type as Category Name
    //             $surface = $tile["surface"] ?? "-";
    //             $tileName = $tile["tile_name"] ?? "Unknown";
    
    //             if (!$tileId) continue; // Skip invalid tile data
    
    //             if (!isset($appliedTiles[$tileId])) {
    //                 $tileInfo = Helper::getTileNameAndSurface($tileId);
    
    //                 $appliedTiles[$tileId] = [
    //                     "photo" => $tileInfo['photo'] ?? "default.jpg",
    //                     "name" => $tileName,
    //                     "finish" => $tileInfo['finish'] ?? "-",
    //                     "category" => [],  // Store multiple room types
    //                     "room_names" => [],
    //                     "used_count" => 0,
    //                 ];
    //             }
    
    //             // Add room name if not already present
    //             if (!in_array($roomName, $appliedTiles[$tileId]['room_names'])) {
    //                 $appliedTiles[$tileId]['room_names'][] = $roomName;
    //             }
    
    //             // Add room type (category) if not already present
    //             if (!in_array($roomType, $appliedTiles[$tileId]['category'])) {
    //                 $appliedTiles[$tileId]['category'][] = $roomType;
    //             }
    
    //             // Increment usage count
    //             $appliedTiles[$tileId]['used_count'] += 1;
    //         }
    //     }
    
    //     // Convert room names & category arrays into a string
    //     foreach ($appliedTiles as &$tile) {
    //         $tile['room_names'] = implode(", ", array_filter(array_unique($tile['room_names'])));
    //         $tile['category'] = implode(", ", array_filter(array_unique($tile['category'])));
    //     }
        
    //     return response()->json([
    //         'body' => view('dashboard.applied_tile_details', compact('appliedTiles'))->render(),
    //         'appliedTiles' => $appliedTiles,
    //     ]);

    // }

    protected function appliedTilesDetails($startDate, $endDate)
    {
        $tilesData = Analytics::select(
            DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json')
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        $appliedTiles = [];

        foreach ($tilesData as $row) {
            if (empty($row->tiles_json)) continue;

            $tiles = json_decode($row->tiles_json, true);
            if (!is_array($tiles)) continue;

            foreach ($tiles as $tile) {
                $tileId = $tile["tile_id"] ?? null;
                $roomName = $tile["room_name"] ?? "-";
                $roomType = $tile["room_type"] ?? "-";
                $surface = $tile["surface"] ?? "-";
                $tileName = $tile["tile_name"] ?? "Unknown";

                if (!$tileName) continue;

                if (!isset($appliedTiles[$tileName])) {
                    $tileInfo = Helper::getTileNameAndSurface($tileId); // We'll just use the first ID encountered

                    $appliedTiles[$tileName] = [
                        "photo" => $tileInfo['photo'] ?? "default.jpg",
                        "name" => $tileName,
                        "finish" => $tileInfo['finish'] ?? "-",
                        "category" => [],
                        "room_names" => [],
                        "used_count" => 0,
                    ];
                }

                if (!in_array($roomName, $appliedTiles[$tileName]['room_names'])) {
                    $appliedTiles[$tileName]['room_names'][] = $roomName;
                }

                if (!in_array($roomType, $appliedTiles[$tileName]['category'])) {
                    $appliedTiles[$tileName]['category'][] = $roomType;
                }

                $appliedTiles[$tileName]['used_count'] += 1;
            }
        }

        // Convert arrays into comma-separated strings
        foreach ($appliedTiles as &$tile) {
            $tile['room_names'] = implode(", ", array_filter(array_unique($tile['room_names'])));
            $tile['category'] = implode(", ", array_filter(array_unique($tile['category'])));
        }

        return response()->json([
            'body' => view('dashboard.applied_tile_details', compact('appliedTiles'))->render(),
            'appliedTiles' => array_values($appliedTiles), // Remove keys if needed
        ]);
    }


    protected function roomCategoriesDetails($startDate , $endDate)
    {   
        $categories = Analytics::whereBetween('visited_at', [$startDate, $endDate])
            ->pluck('category') // Get only the category column
            ->filter() // Remove null values
            ->map(fn($cat) => json_decode($cat, true)) // Decode JSON to array
            ->toArray(); // Convert collection to array

        
        $categoryCount = collect($categories)->flatten()->countBy();

        
       // Transform into the required format
        $categoryData = $categoryCount->map(fn($count, $category) => [
            "category_name" => ucwords(str_replace("-", " ", $category)), // Converts "prayer-room" to "Prayer Room"
            "visits" => $count
        ])->values();


        return response()->json([
            'body' => view('dashboard.room_categories_details', compact('categoryData'))->render(),
            'categoryData' => $categoryData,
        ]);
    }

    /*** Tile id wise */
    // protected function tilesDetails($startDate , $endDate)
    // {
    //     $tilesData = Analytics::select(
    //         DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json'),
    //     )->whereBetween('visited_at', [$startDate, $endDate])->get();

    //     $processedTiles = [];
    //     // Loop through DB results
    //     foreach ($tilesData as $row) {
    //         // Check if tiles_json is empty or null
    //         if (empty($row->tiles_json)) {
    //             continue; // Skip this row
    //         }

    //         // Decode JSON
    //         $tiles = json_decode($row->tiles_json, true);

    //         // Check if JSON decoding failed
    //         if (!is_array($tiles)) {
    //             dd("JSON decoding failed", json_last_error_msg(), $row->tiles_json);
    //         }

    //         foreach ($tiles as $tile) {
    //             $tileId = $tile["tile_id"] ?? null;
    //             $surface = $tile["surface"] ?? null;

    //             if (!$tileId) continue; // Skip invalid tile data

    //             if (isset($processedTiles[$tileId])) {
    //                 $processedTiles[$tileId]['used_count'] += 1;
    //                 if ($surface === "floor") {
    //                     $processedTiles[$tileId]['floor_count'] += 1;
    //                 }
    //                 if ($surface === "wall") {
    //                     $processedTiles[$tileId]['wall_count'] += 1;
    //                 }
    //                 if ($surface === "counter") {
    //                     $processedTiles[$tileId]['counter_count'] += 1;
    //                 }
    //             } else {
    //                 $tilesPhotoSize = Helper::getTileNameAndSurface($tileId);
    //                 $processedTiles[$tileId] = [
    //                     "name" => $tile["tile_name"] ?? "Unknown",
    //                     "photo" => $tilesPhotoSize['photo'] ?? "default.jpg",
    //                     "size" => $tilesPhotoSize['size'] ?? "Unknown",
    //                     "finish" => $tilesPhotoSize['finish'] ?? "Unknown",
    //                     "view_count" => 0,
    //                     "used_count" => 1,
    //                     "floor_count" => ($surface === "floor") ? 1 : 0,
    //                     "wall_count" => ($surface === "wall") ? 1 : 0,
    //                     "counter_count" => ($surface === "counter") ? 1 : 0,
    //                     "category" => $tilesPhotoSize['category'],
    //                     "color" => $tilesPhotoSize['color'],
    //                     "innovation" => $tilesPhotoSize['innovation'] ?? "-",
    //                 ];
    //             }
    //         }
    //     }

    //     $processedTiles = array_values($processedTiles);
    //     $wallCount = array_sum(array_column($processedTiles, 'wall_count'));
    //     $floorCount = array_sum(array_column($processedTiles, 'floor_count'));
    //     $counterCount = array_sum(array_column($processedTiles, 'counter_count'));

    //     // Add total counts inside each tile in processedTiles
    //     foreach ($processedTiles as &$tile) {
    //         $tile['total_wall_count'] = $wallCount > 0 ? $wallCount : "-";
    //         $tile['total_floor_count'] = $floorCount > 0 ? $floorCount : "-";
    //         $tile['total_counter_count'] = $counterCount > 0 ? $counterCount : "-";

    //         // Also handle individual tile counts
    //         $tile['wall_count'] = $tile['wall_count'] > 0 ? $tile['wall_count'] : "-";
    //         $tile['floor_count'] = $tile['floor_count'] > 0 ? $tile['floor_count'] : "-";
    //         $tile['counter_count'] = $tile['counter_count'] > 0 ? $tile['counter_count'] : "-";
    //     }

    //     return response()->json([
    //         'body' => view('dashboard.tiles_details', compact('processedTiles'))->render(),
    //         'processedTiles' => $processedTiles,
    //     ]);
    // }

    /**
     * param => $startDate , $endDate
     */
    protected function tilesDetails($startDate, $endDate)
    {
        $tilesData = Analytics::select(
            DB::raw('JSON_UNQUOTE(used_tiles) as tiles_json')
        )->whereBetween('visited_at', [$startDate, $endDate])->get();

        $processedTiles = [];

        foreach ($tilesData as $row) {
            if (empty($row->tiles_json)) {
                continue;
            }

            $tiles = json_decode($row->tiles_json, true);
            if (!is_array($tiles)) {
                dd("JSON decoding failed", json_last_error_msg(), $row->tiles_json);
            }

            foreach ($tiles as $tile) {
                $tileName = $tile["tile_name"] ?? null;
                $surface = $tile["surface"] ?? null;
                $tileId = $tile["tile_id"] ?? null;

                if (!$tileName || !$tileId) continue;

                $key = strtolower(trim($tileName));

                if (!isset($processedTiles[$key])) {
                    $tileMeta = Helper::getTileNameAndSurface($tileId);
                    $processedTiles[$key] = [
                        "name" => $tileName,
                        "photo" => $tileMeta['photo'] ?? "default.jpg",
                        "size" => $tileMeta['size'] ?? "Unknown",
                        "finish" => $tileMeta['finish'] ?? "Unknown",
                        "view_count" => 0,
                        "used_count" => 1,
                        "floor_count" => ($surface === "floor") ? 1 : 0,
                        "wall_count" => ($surface === "wall") ? 1 : 0,
                        "counter_count" => ($surface === "counter") ? 1 : 0,
                        "category" => $tileMeta['category'] ?? "-",
                        "color" => $tileMeta['color'] ?? "-",
                        "innovation" => $tileMeta['innovation'] ?? "-",
                    ];
                } else {
                    $processedTiles[$key]['used_count'] += 1;
                    if ($surface === "floor") {
                        $processedTiles[$key]['floor_count'] += 1;
                    }
                    if ($surface === "wall") {
                        $processedTiles[$key]['wall_count'] += 1;
                    }
                    if ($surface === "counter") {
                        $processedTiles[$key]['counter_count'] += 1;
                    }
                }
            }
        }

        $processedTiles = array_values($processedTiles);

        $wallCount = array_sum(array_column($processedTiles, 'wall_count'));
        $floorCount = array_sum(array_column($processedTiles, 'floor_count'));
        $counterCount = array_sum(array_column($processedTiles, 'counter_count'));

        foreach ($processedTiles as &$tile) {
            $tile['total_wall_count'] = $wallCount > 0 ? $wallCount : "-";
            $tile['total_floor_count'] = $floorCount > 0 ? $floorCount : "-";
            $tile['total_counter_count'] = $counterCount > 0 ? $counterCount : "-";

            $tile['wall_count'] = $tile['wall_count'] > 0 ? $tile['wall_count'] : "-";
            $tile['floor_count'] = $tile['floor_count'] > 0 ? $tile['floor_count'] : "-";
            $tile['counter_count'] = $tile['counter_count'] > 0 ? $tile['counter_count'] : "-";
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
                if (!empty($room["room_id"])) {
                    $roomIds[] = $room["room_id"];
                }
            }
        }

        // Fetch room types from room2ds table
        $roomTypes = Room2d::whereIn('id', $roomIds)
            ->pluck('type', 'id')
            ->toArray(); // Returns [room_id => room_type]

        $processedRooms = [];

        foreach ($roomData as $row) {
            if (empty($row->rooms_json)) continue;

            $rooms = json_decode($row->rooms_json, true);
            if (!is_array($rooms)) continue;

            foreach ($rooms as $room) {
                $roomName = $room["room_name"] ?? "Unknown";
                $roomId = $room["room_id"] ?? null;

                // Fetch room_type from room2ds table
                $roomType = $roomId ? ($roomTypes[$roomId] ?? "Unknown") : "Unknown";

                $key = $roomName . "|" . $roomType; // Unique key for grouping

                if (!isset($processedRooms[$key])) {
                    $processedRooms[$key] = [
                        "room_name" => $roomName,
                        "category_name" => ucwords($roomType),
                        "used_count" => 0,
                    ];
                }

                $processedRooms[$key]["used_count"] += 1; // Count usage
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

        // Format and return the final response
        return response()->json([
            'body' => view('dashboard.showroom_details', compact('showroomData', 'totalSessionCount', 'summaryPageCount', 'totalCustomers'))->render(),
            'showroomData' => array_values($showroomData), // Convert associative array to indexed array
            'totalSessions' => $totalSessionCount,
            'summaryPageSessions' => $summaryPageCount,
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
        // Get all sessions in the date range
        $sessions = $this->totalSession($startDate , $endDate);

        // Count logged-in users
        $loggedInUsers = $sessions->whereNotNull('user_logged_in')->unique('user_logged_in')->count();

        // Count guest users
        $guestUsers = $sessions->where('user_logged_in','guest')->count();

        // Total users (Logged-in + Guests)
        $totalUsers = $loggedInUsers + $guestUsers;

        // Count generated summary pages (sessions that reached summary page)
        $summaryPages = $sessions->whereNotNull('unique_cart_id')->count();

        // Count PDF downloads
        $pdfDownloads = UserPdfData::whereBetween('created_at', [$startDate, $endDate])->count();

        $user_analytics = [
            'guest_users' => $guestUsers,
            'logged_in_users' => $loggedInUsers,
            'total_users' => $totalUsers,
            'generated_summary_pages' => $summaryPages,
            'pdf_downloads' => $pdfDownloads
        ];

        return response()->json([
            'body' => view('dashboard.pdf_session_details', compact('user_analytics'))->render(),
            'user_analytics' => $user_analytics,
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
