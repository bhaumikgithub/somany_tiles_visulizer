@extends('layouts.ai_room')

@section('content')

<div class="room-menu">
    <div class="menu-container">
        <button class="" tabindex="0" type="button" aria-label="Change Room" id="change-room">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="rgba(255, 255, 255, 1.0)" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M224,177.3V78.7a8.1,8.1,0,0,0-4.1-7l-88-49.5a7.8,7.8,0,0,0-7.8,0l-88,49.5a8.1,8.1,0,0,0-4.1,7v98.6a8.1,8.1,0,0,0,4.1,7l88,49.5a7.8,7.8,0,0,0,7.8,0l88-49.5A8.1,8.1,0,0,0,224,177.3Z" fill="none" stroke="rgba(255, 255, 255, 1.0)" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></path><polyline points="222.9 74.6 128.9 128 33.1 74.6" fill="none" stroke="rgba(255, 255, 255, 1.0)" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></polyline><line x1="128.9" y1="128" x2="128" y2="234.8" fill="none" stroke="rgba(255, 255, 255, 1.0)" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></line></svg>
            <span>Change Room</span>
        </button>
        <div class="menu-item" tabindex="1">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 384 512" fill="rgba(255, 255, 255, 1.0)"><path d="M297.2 248.9C311.6 228.3 320 203.2 320 176c0-70.7-57.3-128-128-128S64 105.3 64 176c0 27.2 8.4 52.3 22.8 72.9c3.7 5.3 8.1 11.3 12.8 17.7l0 0c12.9 17.7 28.3 38.9 39.8 59.8c10.4 19 15.7 38.8 18.3 57.5H109c-2.2-12-5.9-23.7-11.8-34.5c-9.9-18-22.2-34.9-34.5-51.8l0 0 0 0c-5.2-7.1-10.4-14.2-15.4-21.4C27.6 247.9 16 213.3 16 176C16 78.8 94.8 0 192 0s176 78.8 176 176c0 37.3-11.6 71.9-31.4 100.3c-5 7.2-10.2 14.3-15.4 21.4l0 0 0 0c-12.3 16.8-24.6 33.7-34.5 51.8c-5.9 10.8-9.6 22.5-11.8 34.5H226.4c2.6-18.7 7.9-38.6 18.3-57.5c11.5-20.9 26.9-42.1 39.8-59.8l0 0 0 0 0 0c4.7-6.4 9-12.4 12.7-17.7zM192 128c-26.5 0-48 21.5-48 48c0 8.8-7.2 16-16 16s-16-7.2-16-16c0-44.2 35.8-80 80-80c8.8 0 16 7.2 16 16s-7.2 16-16 16zm0 384c-44.2 0-80-35.8-80-80V416H272v16c0 44.2-35.8 80-80 80z"/></svg>
                <span>Adjust Shadow/Light</span>
            </div>
            <input type="range" min="0" max="100" value="60" class="menu-slider" id="shadow-menu">
        </div>
    </div>
</div>
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
            <button class="share-link d-flex flex-wrap align-items-center normal-pdf-link" onclick="addToPDFAI();"><i
                        class="fa-regular fa-file-lines"></i> <span class="btn-text-set">Add to Selection </span></button>
            <button class="share-link d-flex flex-wrap align-items-center share-btn-close"><i
                        class="fa-solid fa-xmark"></i></button>
        </div>

        <div class=" cmn-room-btn room-cn-btn cn-btn">
            <button onclick="checkCartHasData()"> <span class="span-icon"><i class="fa-solid fa-arrow-right"></i>
                </span></button>
            <p class="btn-text-set-back">Continue </p>
        </div>

        <input type="hidden" value="ai_room" id="current_room_type_ai">
        <input type="hidden" value="" id="selected_tile_ids_ai">
        <input type="hidden" value="{{ session()->getId() }}" id="currentSessionIdAI">
        <input type="hidden" value="" id="free_tile_checkbox_value_ai">
</div>

@if (config('view.visualizer_layout') != 'iorena.')
    @include('common.' . config('app.bottom_menu') . 'bottomMenu2d')
@endif

<script src="/js/roomAI/three.min.js"></script>

@if (config('app.js_as_module'))
<script type="module" src="/js/src/2d/interior2d.js"></script>
@else
<script src="https://cdn.jsdelivr.net/npm/gammacv@0.5.3/dist/index.min.js" async></script>
<script src="/js/roomAI/opencv.js" async type="text/javascript"></script>
<script src="/js/roomAI/image.ops.js"></script>
<script src="/js/roomAI/zoom.js"></script>
<script src="/js/roomAI/2d_room_ai.min.js"></script>
@endif

<!-- contine modal start -->
<div class="modal fade continue-modal" id="continue-modal" role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header add-sec-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="add-sec-title">Add More to selection</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label class="mb-15">Would you like to add more ?</label>
                                <div class="d-flex flex-wrap align-items-center">
                                    <a class="cmn-room-btn  d-flex  align-items-center yes-no-btn mr-15"
                                       data-dismiss="modal"><img src="/img/yes.png" alt="yes" class="img-responsive">
                                        <span class="btn-text-set">Yes </span></a>
                                    <a class="cmn-room-btn  d-flex  align-items-center yes-no-btn" href="javascript:void(0)"
                                       id="cart_url" onclick="viewCartPdf();"><img src="/img/no.png" alt="no"
                                                                                   class="img-responsive"> <span class="btn-text-set">No </span></a>
                                </div>
                            </div>

                        </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- show confirmation popup on click on No button from continue modal -->
        <div class="modal fade no-data-in-cart-selection-modal" id="no-data-in-cart-selection-modal" role="dialog" data-keyboard="false"
             data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header add-sec-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="add-sec-title">Add Current Design to selection</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label class="mb-15">Would you like to add current Design into Selection ?</label>
                                <div class="d-flex flex-wrap align-items-center">
                                    <a class="cmn-room-btn  d-flex  align-items-center yes-no-btn mr-15"
                                       onclick="addToPDFAI();"><img src="/img/yes.png" alt="yes"
                                                                  class="img-responsive"> <span class="btn-text-set">Yes </span></a>
                                    <a class="cmn-room-btn  d-flex  align-items-center yes-no-btn"
                                       href="javascript:void(0)" id="cart_url" data-dismiss="modal"><img
                                                src="/img/no.png" alt="no" class="img-responsive"> <span
                                                class="btn-text-set">No </span></a>

                                </div>
                            </div>

                        </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
