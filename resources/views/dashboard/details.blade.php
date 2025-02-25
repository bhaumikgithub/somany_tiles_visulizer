@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>{{ ucfirst($type) }} Data</h2>
        @if($type === "zone")
            <table id="zonePincodeTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>Zone</th>
                    <th>Pincode</th>
                    <th>Visits</th>
                </tr>
                </thead>
                <tbody>
                    <tr></tr>
                </tbody>
            </table>
        @endif
    </div>
@endsection