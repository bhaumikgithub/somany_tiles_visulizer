@extends('layouts.admin')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-6" id="displayResultDate">
                <h3 class="mb-0 font-weight-bold">Dashboard</h3>
                <div style="font-size: 11px;" id="selectedDateRangeText">Showing result from --</div>
            </div>

            <div class="col-sm-6">
                <div class="d-flex align-items-center justify-content-md-end">
                    <div class="mb-3 mb-xl-0 pr-1">
                        <input type="text" id="daterange" name="daterange" class="form-control" placeholder="Select Date Range" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4 d-flex grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Users Trends</h4>
                        </div>
                        <hr>
                        <div class="mt-2">
                            <div class="d-flex justify-content-between">
                                <small>Total Users</small>
                                <small class="totalUsers"></small>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between">
                                <small>Guest Users</small>
                                <small class="totalGuestUsers"></small>
                            </div>

                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between">
                                <small>Showroom Users</small>
                                <small class="totalLoggedInUsers"></small>
                            </div>

                        </div>
                        <hr>
                        <div class="mr-1">
                            <h2 class="mb-2 mt-2 font-weight-bold total_sessions"></h2>
                            <div class="">
                                Total Sessions
                            </div>
                        </div>
                        <hr>
                        <div class="mr-1">
                            <h2 class="mb-2 mt-2 font-weight-bold session_to_summary_page"></h2>
                            <div class="">
                                Session reached to Summary Page
                            </div>
                        </div>
                        <hr>
                        <div class="mr-1">
                            <h2 class="mb-2 mt-2 font-weight-bold pdf_download"></h2>
                            <div class="">
                                Unique PDF Download
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 d-flex grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Session Reached to Summary page or Download PDF</h4>
                        </div>
                        <div class="row" id="sessionSummary">
                            <div class="col-12">
                                <div class="d-md-flex mb-4">
                                    <div class="mr-md-5 mb-4">
                                        <h5 class="mb-1"><i class="typcn typcn-globe-outline mr-1"></i>Summary Page Session</h5>
                                        <h2 class="text-primary mb-1 font-weight-bold session_to_summary_page"></h2>
                                    </div>
                                    <div class="mr-md-5 mb-4">
                                        <h5 class="mb-1"><i class="typcn typcn-archive mr-1"></i>PDF Downloaded</h5>
                                        <h2 class="text-secondary mb-1 font-weight-bold pdf_download"></h2>
                                    </div>

                                </div>
                                <canvas id="summaryPdfDownloadChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 d-flex grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Pincode</h4>
                            <small>Sessions (Percentage)</small>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row" id="pincodeChart">
                                    <div class="col-12">
                                        <canvas id="zonePincodeChart"></canvas>
                                    </div>
                                    <div class="col-12">
                                        <ul class="session-by-channel-legend" id="summaryList">

                                        </ul>
                                    </div>
                                    <div class="col-12 mt-2" style="text-align: right;">
                                        <a href="{{ route('analytics.details', ['type' => 'pincode']) }}" id="viewAllPincode">View All ></a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 d-flex grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Tiles Applied on</h4>
                            <small>Number of Finalized Tiles (Percentage)</small>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row appliedTilesBlock">
                                    <div class="col-12">
                                        <canvas id="tilesAppliedOnChart"></canvas>
                                    </div>
                                    <div class="col-12">
                                        <ul class="session-by-channel-legend" id="tile-applied-list">
                                        </ul>
                                    </div>
                                    <div class="col-12 mt-2" style="text-align: right;">
                                        <a href="{{ route('analytics.details', ['type' => 'appliedTiles']) }}" id="viewAllAppliedTiles">View All ></a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 d-flex grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Room Categories used</h4>
                        </div>
                        <div class="row" id="roomCategoryBlock">
                            <div class="col-12">
                                <canvas id="roomCategoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 d-flex grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Most Used Tiles (Top 5)</h4>
                        </div>

                        <div class="table-responsive topFiveTilesWrapper">
                            @include('dashboard.top_tiles')
                        </div>
                        <div style="text-align: right;">
                            <a href="{{ route('analytics.details', ['type' => 'tiles']) }}" id="viewAllTiles">View All ></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 d-flex grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Most Used Rooms (Top 5)</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row topFiveRoomsWrapper">
                                    @include('dashboard.top_rooms')
                                </div>
                                <div class="col-12 mt-2" style="text-align: right;">
                                    <a href="{{ route('analytics.details', ['type' => 'rooms']) }}" id="viewAllRooms">View All ></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 d-flex grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Most Active Showrooms (Top 5)</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row topFiveShowRoomsWrapper">
                                    @include('dashboard.top_show_rooms')
                                </div>
                                <div class="col-12 mt-2" style="text-align: right;">
                                    <a href="{{ route('analytics.details', ['type' => 'showrooms']) }}" id="viewAllShowRooms">View All ></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection