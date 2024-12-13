@extends(config('view.visualizer_layout') .'layouts.room')

@section('content')

<div id="container" class="room-canvas-container">
    <canvas id="roomCanvas" class="room-canvas"></canvas>
</div>
<input type="hidden" value="{{@$roomId}}" id="current_room_id">
<input type="hidden" value="{{@$room_name}}" id="current_room_name">
<input type="hidden" value="{{@$room_type}}" id="current_room_type">
<input type="hidden" value="" id="selected_tile_ids">
@if (config('view.visualizer_layout') != 'iorena.')
    @include('common.' . config('app.bottom_menu') . 'bottomMenu2d')
@endif

<script src="/js/room/three.min.js"></script>

@if (config('app.js_as_module'))
<script type="module" src="/js/src/2d/interior2d.js"></script>
@else
<script src="/js/room/2d.min.js"></script>
@endif

@endsection
