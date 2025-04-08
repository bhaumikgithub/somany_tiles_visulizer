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
        @if($type !== "showrooms" && $type !== "ai-studio")
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                            @if( $type === "appliedTiles")
                                Tiles Applied On Data
                            @elseif($type === "roomCategories")
                                Room Cartegories Data
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
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Size</th>
                                                <th>Finishes</th> <!-- KEY PARAM -->
                                                <th>Tiles Category</th>
                                                <th>Tiles Innovation</th>
                                                <th>Tiles Colour</th>
                                                <th>Floor</th>
                                                <th>Wall</th>
                                                <th>Counter</th>
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
                                        <table class="table">
                                            <thead>
                                                <tr style="text-align:center">
                                                    <th>Guest Users</th>
                                                    <th>Logged in Users</th>
                                                    <th>Total</td>
                                                    <th>Generate Summary page</td>
                                                    <th>Download PDF</th>
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