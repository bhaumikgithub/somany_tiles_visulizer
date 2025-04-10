@extends('layouts.admin')
@section('content')
    <div class="content-wrapper report_wrapper">
        <div class="row">
            <div class="col-sm-6" id="displayResultDate">
                <input type="hidden" value="{{$type}}" id="detailPageType">
                <h3 class="mb-0 font-weight-bold">
                    @if( $type === "appliedTiles")
                        Tiles Applied On
                    @elseif($type === "roomCategories")
                        Room Cartegories
                    @elseif($type === "pdf")
                        PDF
                    @else
                        {{ucwords($type)}}
                    @endif
                    </h3>
                <div style="font-size: 11px;" id="selectedDateRangeDetailText">Showing result from --</div>
            </div>

            <div class="col-sm-6">
                <div class="d-flex align-items-center justify-content-md-end">
                    <div class="mb-3 mb-xl-0 pr-1">
                        <input type="text" id="daterangeDetail" name="daterangeDetail" class="form-control" placeholder="Select Date Range" />
                    </div>
                </div>
            </div>
        </div>
        @if($type !== "showroom" && $type !== "ai-studio")
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                            @if( $type === "appliedTiles")
                                Tiles Applied On Data
                            @elseif($type === "roomCategories")
                                Room Cartegories Data
                            @elseif($type === "pdf")
                                PDF Data
                            @else
                                {{$type}} Data
                            @endif    
                            </h4>
                            <div class="table-responsive">
                                @if( $type === "pincode")
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Pincode</th>
                                                <th>Zone Name</th>
                                                <th>Number of Times Visited</th>
                                                <th>Visited At</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pincode_tbody">
                                            @include('dashboard.pincode_details')
                                        </tbody>
                                    </table>
                                @elseif( $type === "appliedTiles")
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Tiles Name</th>
                                                <th>Finishes</th>
                                                <th>Room Name</th>
                                                <th>Category Name</th>
                                                <th>Number of Times Used</th>
                                            </tr>
                                        </thead>
                                        <tbody id="appliedTiles_tbody">
                                            @include('dashboard.applied_tile_details')
                                        </tbody>
                                    </table>
                                @elseif( $type === "roomCategories")
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Category Name</th>
                                                <th>Number of Times Used</th>
                                            </tr>
                                        </thead>
                                        <tbody id="roomCategories_tbody">
                                            @include('dashboard.room_categories_details')
                                        </tbody>
                                    </table>
                                @elseif( $type === "tiles")
                                    <table class="table" id="viewed_used_table">
                                        <thead>
                                            <tr>
                                                <th rowspan="2"></th>
                                                <th rowspan="2">Name</th>
                                                <th rowspan="2">Size</th>
                                                <th rowspan="2">Finishes</th>
                                                <th rowspan="2">Tiles Category</th>
                                                <th rowspan="2">Tiles Innovation</th>
                                                <th rowspan="2">Tiles Colour</th>
                                                <th rowspan="2">Floor</th>
                                                <th rowspan="2">Wall</th>
                                                <th rowspan="2">Counter</th>
                                                <th colspan="5">Viewed Tiles (Zone-wise)</th>
                                                <th colspan="5">Used Tiles (Zone-wise)</th>
                                            </tr>
                                            <tr>
                                                <th>Central</th>
                                                <th>East</th>
                                                <th>West</th>
                                                <th>North</th>
                                                <th>South</th>

                                                <th>Central</th>
                                                <th>East</th>
                                                <th>West</th>
                                                <th>North</th>
                                                <th>South</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tiles_tbody">
                                        </tbody>
                                    </table>
                                @elseif( $type === "rooms")
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Room Name</th>
                                                <th>Category Name</th>
                                                <th>Number of Times Used</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rooms_tbody">
                                            @include('dashboard.rooms_details')
                                        </tbody>
                                    </table>
                                @else
                                        <table class="table" id="pdf_table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>User</th>
                                                    <th>Contact No</td>
                                                    <th>Pincode</td>
                                                    <th>PDF</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pdf_tbody">
                                                @include('dashboard.pdf_session_details')
                                            </tbody>
                                        </table>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($type==="ai-studio")
            <div id="ai-studio_tbody">
                @include('dashboard.ai_studio_details')
            </div>
        @else 
            <div id="showrooms_tbody">
                @include('dashboard.showroom_details')
            </div>
        @endif
    </div>
@endsection