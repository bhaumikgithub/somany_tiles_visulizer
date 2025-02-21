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
            </div>
        </div>
        <hr>
        <div class="row">
            <h4>Zone/Pincode Analytics</h4>
            <div class="col-md-6">
                <canvas id="zoneChart"></canvas>
            </div>
            <div class="col-md-6" style="display: none;">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Zone</th>
                        <th>Pincode</th>
                        <th>Visits</th>
                    </tr>
                    </thead>
                    <tbody id="zoneData">
                    <tr><td colspan="3" class="text-center">No data available</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection