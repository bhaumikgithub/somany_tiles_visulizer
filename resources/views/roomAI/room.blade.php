@extends('layouts.ai_room')

@section('content')
    <div id="container" class="room-canvas-container">
        <canvas id="roomCanvas" class="room-canvas"></canvas>

        <div id="topPanelmainpanel" class="">
            <span id="mainpage-panel-btn" class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
        </div>

        <button class="share-btn-img cmn-room-btn" style="display:none">
            <i class="fa-regular fa-share-from-square"></i>

        </button>

        <div class="share-div d-flex flex-wrap social-share">
            @if (config('app.share_button_whatsapp'))
                <a href="https://wa.me/?text={{ urlencode(__('SHARE_WHATSAPP_MESSAGE')) }}%20" title="@lang('Whatsapp Share')"
                   target="_blank" class="share-link d-flex flex-wrap align-items-center">
                    <i class="fa-regular fa-share-from-square"></i><span class="btn-text-set">Share </span>
                </a>
            @endif
            <button class="share-link d-flex flex-wrap align-items-center "onclick="downloadImage();"><i
                        class="fa-solid fa-download"></i> <span class="btn-text-set">Download </span></button>
            <button class="share-link d-flex flex-wrap align-items-center normal-pdf-link" onclick="addToPDF();"><i
                        class="fa-regular fa-file-lines"></i> <span class="btn-text-set">Add to Selection </span></button>
            <button class="share-link d-flex flex-wrap align-items-center share-btn-close"><i
                        class="fa-solid fa-xmark"></i></button>
        </div>
@endsection
