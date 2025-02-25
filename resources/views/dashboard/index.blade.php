@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h4>Select Date Range</h4>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" id="daterange" name="daterange" class="form-control" placeholder="Select Date Range" />
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="filterButton">Filter</button>
                </div>
                <div class="col-md-6" id="displayResultDate">
                    <h4 id="selectedDateRangeText">Showing result from --</h4>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h2>Users Trends</h2>
                <div>
                    <p class="text-gray-600">Total Users: <b><span class="totalUsers"></span></b></p>
                    <p class="text-gray-600">Guest Users: <b><span class="totalGuestUsers"></span></b></p>
                    <p class="text-gray-600">Showroom Users: <b><span class="totalLoggedInUsers"></span></b></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="mt-6">
                    <b><p class="total_sessions"></p></b>
                    <p class="text-gray-600">Total Sessions</p>
                </div>
                <div class="mt-4">
                    <b><p class="session_to_summary_page"></p></b>
                    <p class="text-gray-600">Session reached to Summary Page</p>
                </div>
                <div class="mt-4">
                    <b><p class="pdf_download"></p></b>
                    <p class="text-gray-600">Unique PDF Download</p>
                </div>
            </div>
        </div>
        <hr>
        <!-- Chart Container -->
        <div class="row">
            <h4>Session Reach to Summary Page or Download PDF</h4>
            <div class="col-md-4" style="height: 300px;width: 900px">
                <canvas id="summaryPdfDownloadChart"></canvas>
            </div>
        </div>

        <hr>
        <div class="row">
            <h4>Zone/Pincode</h4>
            <div class="col-md-4" style="height: 300px;width: 300px">
                <canvas id="zonePincodeChart"></canvas>
            </div>
            <!-- Summary Container -->
            <div class="summary-container col-md-2">
                <ul id="summaryList"></ul>
            </div>
            <a href="{{ route('analytics.details', ['type' => 'zone']) }}" class="view-all">View All ></a>
        </div>
        <hr>

        <div class="row">
            <!-- Room chart -->
            <h4>Tiles Applied On</h4>
            <p>Number of Finalized Tiles (Percentage)</p>

            <div class="col-md-4" style="height: 300px;width: 300px">
                <canvas id="tilesAppliedOnChart"></canvas>
            </div>
            <!-- Summary Container -->
            <div class="summary-container col-md-2">
                <ul class="tile-summary-list">
                    <li><span class="dot" style="background:#ffe6aa;"></span> Wall <b id="wallCount"></b></li>
                    <li><span class="dot" style="background:#9ad0f5;"></span> Floor <b id="floorCount"></b></li>
                    <li><span class="dot" style="background:#ffb1c1;"></span> Counters <b id="counterCount"></b></li>
                    <li><span class="dot" style="background:#76adff;"></span><b>Total <b id="totalTiles"></b></b></li>
                </ul>

                <a href="{{ route('analytics.details', ['type' => 'tiles']) }}" class="view-all">View All ></a>
            </div>
        </div>
        <hr>
        <div class="row">
            <!-- Room category chart -->
            <h4>Category</h4>
            <div class="col-md-4" style="height: 300px;width: 900px">
                <canvas id="roomCategoryChart"></canvas>
            </div>
            <!-- Summary Container -->
            <div class="summary-container col-md-2">
                <ul id="roomCategorySummary"></ul>
            </div>
        </div>
        <hr>

        <div class="row">
            <!-- Room chart -->
            <h4>Room</h4>
            <div class="col-md-4" style="height: 300px;width: 300px">
                <canvas id="roomChart"></canvas>
            </div>
            <!-- Summary Container -->
            <div class="summary-container col-md-2">
                <ul id="roomSummary"></ul>
            </div>

            <div class="col-md-6"></div>
        </div>
        <hr>
{{--        <div class="row">--}}
{{--            <!-- Room category chart -->--}}
{{--            <h4>Tiles</h4>--}}
{{--            <!-- Summary Container -->--}}
{{--            <div class="summary-container col-md-12">--}}
{{--                <table class="table table-bordered" id="tilesTable">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>Name</th>--}}
{{--                        <th>Photo</th>--}}
{{--                        <th>Size</th>--}}
{{--                        <th>View Count</th>--}}
{{--                        <th>Used Count</th>--}}
{{--                        <th>Floor Count</th>--}}
{{--                        <th>Wall Count</th>--}}
{{--                        <th>Counter Count</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                        <tr></tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="row">
            <div class="col-md-6">
                <h4><b>Most Used Tiles (Top 5) </b></h4>
                <ul id="topTilesList"></ul>
            </div>

            <div class="col-md-3">
                <h4><b>Most Used Rooms (Top 5) </b></h4>
                <ul id="topUsedRooms"></ul>
            </div>

            <div class="col-md-3">
                <h4><b>Most Active ShowRooms (Top 5) </b></h4>
                <ul id="activeShowRooms"></ul>
            </div>
        </div>

    </div>
@endsection