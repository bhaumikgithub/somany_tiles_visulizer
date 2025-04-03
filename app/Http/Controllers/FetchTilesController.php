<?php

namespace App\Http\Controllers;

use App\Company;
use App\Jobs\ProcessTilesJob;
use App\Tile;
use App\Traits\ApiHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FetchTilesController extends Controller
{
    use ApiHelper;

    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $api_details = Company::select('last_fetch_date_from_api','fetch_products_count')->first();
        // Get last successfully inserted record's date
        $lastInsertedDate = Tile::max('record_creation_time');
        $lastFetchDateFromRecord = Carbon::parse($lastInsertedDate)->format('Y-m-d');
        return view('fetch_tiles_index',compact('api_details','lastFetchDateFromRecord'));
    }

    /**
     * @throws Exception
     */
    public function fetchData(Request $request): JsonResponse
    {
        $getToken = $this->loginAPI();
        set_time_limit(0);
        ini_set('memory_limit', '2048M'); // Adjust the limit as needed

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $apiUrl = "https://somany-backend.brndaddo.ai/api/v1/en_GB/products/autocomplete";

        $queryParams = http_build_query([
            'limit' => 1,
            's' => $startDate,
            'e' => $endDate,
        ]);

        $headers = [
            'JWTAuthorization: Bearer ' . $getToken,
        ];
        // Use the trait function for GET request
        $data = $this->makeGetRequest($apiUrl, $queryParams, $headers);

        if (isset($data['error'])) {
            return response()->json([
                'error' => 'Unable to fetch total records: ' . $data['error'],
            ], 500);
        }

        if (empty($data)) {
            exit(); // Stop if there are no more records
        }

        $totalRecords = count($data);

        // Initialize Cache for Progress
        Cache::forever('tile_processing_progress', [
            'total' => $totalRecords,
            'processed' => 0,
            'percentage' => 0,
            'status' => 'Processing started...',
            'skipped_records' => [],
        ]);

        // Dispatch Job to Process Records in Background
        dispatch_sync(new ProcessTilesJob($data, $endDate, $totalRecords));

        // Return Immediate Response to UI
        return response()->json([
            'success' => true,
            'message' => 'Processing started...',
            'total_records' => $totalRecords,
            'new_date' => $endDate
        ]);
    }
}
