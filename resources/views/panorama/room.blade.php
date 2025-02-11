@extends('layouts.room')

@section('content')

<div id="container" class="canvas-fullscreen" style="touch-action: none;"></div>

<input type="hidden" value="{{@$roomId}}" id="current_room_id">
<input type="hidden" value="{{@$room_name}}" id="current_room_name">
<input type="hidden" value="{{@$room_type}}" id="current_room_type">
<input type="hidden" value="" id="selected_tile_ids">
<input type="hidden" value="{{ session()->getId() }}" id="currentSessionId">


@include('common.topPanelCeilingColor')

@include('common.' . config('app.bottom_menu') . 'bottomMenu2d')


<script src="/js/room/three.min.js"></script>
<script src="/js/room/WebVR.js"></script>

@if (config('app.js_as_module'))
<script type="module" src="/js/src/panorama/interiorPanorama.js"></script>
@else
<script src="/js/room/panorama.min.js"></script>
@endif

@endsection
