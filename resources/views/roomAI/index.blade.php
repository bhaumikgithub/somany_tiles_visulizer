@extends('layouts.front_room_ai')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>See products in your room</h1>
                <form id="uploadForm" enctype="multipart/form-data" data-action="{{ route('room_ai.upload') }}">
                    @csrf
                    <input type="file" name="user_own_room" id="user_own_room">
                </form>
            </div>
        </div>
    </div>
@endsection