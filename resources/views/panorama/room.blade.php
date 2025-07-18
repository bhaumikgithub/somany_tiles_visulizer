@extends('layouts.room')

@section('content')

    <div id="container" class="canvas-fullscreen" >
    </div>
    <div id="topPanelmainpanel" class="">
        <span id="mainpage-panel-btn" class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
    </div>
    <button class="share-btn-img cmn-room-btn" style="display:none">
        <i class="fa-regular fa-share-from-square"></i>

    </button>
    <div class="share-div d-flex flex-wrap social-share panorama-share-div">
        @if (config('app.share_button_whatsapp'))
            <a href="https://wa.me/?text={{ urlencode(__('SHARE_WHATSAPP_MESSAGE')) }}%20" title="@lang('Whatsapp Share')"
               target="_blank" class="share-link d-flex flex-wrap align-items-center share-link-main">
                <i class="fa-regular fa-share-from-square"></i><span class="btn-text-set">Share </span>
            </a>
        @endif
        <button id="btnDialogSaveSceneAsPanorama" class="share-link d-flex flex-wrap align-items-center"><i
                    class="fa-solid fa-panorama"></i> <span class="btn-text-set">Bake Panorama </span></button>
        <button class="share-link d-flex flex-wrap align-items-center downalod-link"onclick="downloadImage();"><i
                    class="fa-solid fa-download"></i> <span class="btn-text-set">Download </span></button>
        <button class="share-link d-flex flex-wrap align-items-center" onclick="addToPDF();"><i
                    class="fa-regular fa-file-lines"></i> <span class="btn-text-set">Add to Selection </span></button>
        <button class="share-link d-flex flex-wrap align-items-center share-btn-close"><i
                    class="fa-solid fa-xmark"></i></button>
    </div>

    <div class=" cmn-room-btn cmn-room-back-btn back-btn">
        <a href="{{ url('panorama-listing/' . @$room_type) }}"> <span class="span-icon"><i class="fa-solid fa-arrow-left"></i>
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
    <input type="hidden" value="" id="free_tile_checkbox_value">


    @include('common.topPanelCeilingColor')

    @include('common.' . config('app.bottom_menu') . 'bottomMenu2d')


    <script src="/js/room/three.min.js"></script>


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
                                   onclick="addToPDF();"><img src="/img/yes.png" alt="yes"
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

    <div id="dialogSavedRoomUrl" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Room successfully saved</h3>
                </div>
                <div class="modal-body">
                    <h4 >Url to your room</h4>
                    <input type="text" id="dialogSavedRoomUrlInput" value="/room3d" class="form-control" onclick="window.$(this).select();" readonly>
                    <div class="text-right">
                        <button id="bookmarkSavedRoomLink" type="button" class="btn btn-default">Bookmark link</button>
                        <a id="savedRoomGoToUrl" href="#" class="btn btn-default" role="button" style="display: none">Go to your room</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>



    @if (config('app.js_as_module'))
        <script type="module" src="/js/src/panorama/interiorPanorama.js"></script>
    @else
        <script src="/js/room/panorama.min.js"></script>
    @endif

@endsection
