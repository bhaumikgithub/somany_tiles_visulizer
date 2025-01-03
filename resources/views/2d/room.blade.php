@extends(config('view.visualizer_layout') .'layouts.room')

@section('content')

<div id="container" class="room-canvas-container">
    <canvas id="roomCanvas" class="room-canvas"></canvas>
    <div class="detail-section">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-12">
                       </div>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <div style="float:right;">
                                <button class="share-btn-img cmn-room-btn">
                                <img src="/img/share.png" alt="share-button" class="img-responsive">
                                </button>
                               
                                <div class="share-div mb-3 d-flex flex-wrap" style="display: none;">
                                    <button class="share-link d-flex flex-wrap align-items-center"><img src="/img/share.png" alt="share" class="img-responsive"> <span class="btn-text-set">share </span></button>
                                    <button class="share-link d-flex flex-wrap align-items-center"><img src="/img/download.png" alt="share" class="img-responsive"> <span class="btn-text-set">download </span></button>
                                    <button class="share-link d-flex flex-wrap align-items-center"><img src="/img/add_section.png" alt="add_section" class="img-responsive"> <span class="btn-text-set">Add to Selection </span></button>
                                    <button href=""  class="share-link d-flex flex-wrap align-items-center share-btn-close"><img src="/img/close.png" alt="close" class="img-responsive"></button>
                                     
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="back-cn-main d-flex flex-wrap w-100">
                            <div class="back-con-div-wrap ">
                            <button class="cmn-room-btn back-btn d-flex flex-wrap align-items-center" > <img src="/img/arrow-back.png" alt="arrow-back" class="img-responsive"> <span class="btn-text-set">Back </span> </button>
                            </div>
                            <div class="back-con-div-wrap d-flex flex-wrap justify-content-end">
                            <button class="cmn-room-btn cn-btn d-flex flex-wrap align-items-center" data-toggle="modal" data-target="#continue-modal" > <div class="cn-img-set"> <img src="/img/arrow-cn.png" alt="arrow-continue" class="img-responsive"> </div> <span class="btn-text-set">continue </span> </button>
                            </div>
                           </div>
                           </div>
                        </div>
                    </div>
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

 <!-- contine modal start -->
 <div class="modal fade" id="continue-modal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header add-sec-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="add-sec-title">Add More Selection</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="mb-15">Would you like to add more Selection?</label>
                        <div class="d-flex flex-wrap align-items-center">
                        <a class="cmn-room-btn  d-flex  align-items-center yes-no-btn mr-15" data-dismiss="modal"><img src="/img/yes.png" alt="yes" class="img-responsive"> <span class="btn-text-set">Yes </span></a>
                        <a class="cmn-room-btn  d-flex  align-items-center yes-no-btn" href="#"><img src="/img/no.png" alt="no" class="img-responsive"> <span class="btn-text-set">No </span></a>

                        </div>
                    </div>

                  </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
