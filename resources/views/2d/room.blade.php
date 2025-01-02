@extends(config('view.visualizer_layout') .'layouts.room')

@section('content')

<div id="container" class="room-canvas-container">
    <canvas id="roomCanvas" class="room-canvas"></canvas>
    <!-- <div class="detail-section">
                    <div class="row">
                        <div class="col-md-2">
                       </div>
                        <div class="col-md-10">
                            <div style="float:right;">
                                <button class="share-btn-img cmn-room-btn">
                                <img src="/img/share.png" alt="share-button" class="img-responsive">
                                </button>
                               
                                <div class="share-div mb-3" style="display: none;">
                                    <img src="images/share.png" class="img-responsive">
                                    <img src="images/download.png" class="img-responsive">
                                    <img src="images/add-to-selection.png" class="img-responsive">
                                    <img src="images/material-symbols_close.png" class="img-responsive pt-2 share-btn-close">
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                            <button class="cmn-room-btn back-btn d-flex flex-wrap align-items-center" > <img src="/img/arrow-back.png" alt="arrow-back" class="img-responsive"> <span class="btn-text-set">Back </span> </button>
                            </div>
                            <div class="col-md-4 text-right cn-btn-set d-flex flex-wrap justify-content-end">
                            <button class="cmn-room-btn cn-btn d-flex flex-wrap align-items-center" > <div class="cn-img-set"> <img src="/img/arrow-cn.png" alt="arrow-continue" class="img-responsive"> </div> <span class="btn-text-set">continue </span> </button>
                                <div class="continue-div" style="display: none;">
                                    <p class="mt-3 ">Would you like to add more Selection? </p>
                                    <p style="display: flex;gap: 10px;" class="mt-2 mb-0"><img src="images/detail/yes-icon.png" class="img-responsive"> <span class="mt-2">Yes</span></p>
                                    <p style="display: flex;gap: 10px;" class="mt-2 mb-0 continue-btn-close"><img src="images/detail/no-icon.png" class="img-responsive "> <span class="mt-2">No</span></p>
                                </div>
                            </div>
                        </div>
                    </div> -->
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
<script src="/js/room/custom.js"></script>
@endif

@endsection
