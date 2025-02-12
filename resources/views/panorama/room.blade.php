@extends('layouts.room')

@section('content')

<div id="container" class="canvas-fullscreen" style="touch-action: none;">
</div>
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
            <button class="share-link d-flex flex-wrap align-items-center"onclick="downloadImage();"><i
                        class="fa-solid fa-download"></i> <span class="btn-text-set">Download </span></button>
            <button class="share-link d-flex flex-wrap align-items-center" onclick="addToPDF();"><i
                        class="fa-regular fa-file-lines"></i> <span class="btn-text-set">Add to Selection </span></button>
            <button class="share-link d-flex flex-wrap align-items-center share-btn-close"><i
                        class="fa-solid fa-xmark"></i></button>
        </div>


        <!-- <a href="{{ url('listing/' . @$room_type) }}" class="cmn-room-btn back-btn d-flex flex-wrap align-items-center"> <span class="span-icon"><i class="fa-solid fa-arrow-left"></i>
                                </span> <span class="btn-text-set">Back </span> </a> -->
        <div class=" cmn-room-btn cmn-room-back-btn back-btn">
            <a href="{{ url('/') }}"> <span class="span-icon"><i class="fa-solid fa-arrow-left"></i>
                </span></a>
            <p class="btn-text-set-back">Back </p>
        </div>
        <div class=" cmn-room-btn room-cn-btn cn-btn">
            <button onclick="checkCartHasData()"> <span class="span-icon"><i class="fa-solid fa-arrow-right"></i>
                </span></button>
            <p class="btn-text-set-back">Continue </p>
        </div>

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
