@extends('layouts.ai_room')

@section('content')
    <div id="container" class="room-canvas-container">
        <canvas id="roomCanvas" class="room-canvas"></canvas>

        <div id="topPanelmainpanel" class="">
            <span id="mainpage-panel-btn" class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
        </div>

        <script src="/js/roomAI/three.min.js"></script>

@endsection
