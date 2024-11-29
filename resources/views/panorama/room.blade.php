@extends('layouts.room')

@section('content')

<div id="container" class="canvas-fullscreen" style="touch-action: none;"></div>

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
