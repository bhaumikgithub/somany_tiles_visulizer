@extends('layouts.room')

@section('content')

<div id="container"></div>

@include('common.topPanelCeilingColor')


<div id="cameraViewMenu">
    <input id="radioCameraView_orbital" type="radio" name="radioCameraViewMenu" value="orbital" checked="checked">
        <label for="radioCameraView_orbital" title="Orbital Camera View">
            <img src="/img/icons/orbitalcamera.png" id="imgCameraView_orbital" alt="" class="camera-view-menu-item-image">
        </label>
    <input id="radioCameraView_firstPerson" type="radio" name="radioCameraViewMenu" value="firstPerson">
        <label for="radioCameraView_firstPerson" title="First Person Walk Through Camera View">
            <img src="/img/icons/walkcamera.png" alt="" class="camera-view-menu-item-image">
        </label>
</div>


<div id="bottomPanelMenu">

    <button id="bottomMenuRoomSelect" class="bottom-menu-text" title="@lang('Select Room')" onclick="window.$('#dialogRoomSelect').modal('show');">
        @lang('Select Room')
    </button>
    <button id="bottomMenuRoomInfo" title="@lang('Room Info')">
        <img src="/img/icons/info.png" alt="">
    </button>
    <button id="bottomMenuCapture" title="@lang('Capture')" onclick="window.$('#dialogSaveModalBox').modal('show');">
        <img src="/img/icons/capture.png" alt="">
    </button>
    <button id="bottomMenuMail" data-href="mailto:?subject={{ urlencode(__('SHARE_EMAIL_SUBJECT')) }}&body={{ urlencode(__('SHARE_EMAIL_BODY')) }}%20" title="@lang('E-mail Share')">
        <img src="/img/icons/mail.png" alt="">
    </button>
    <span class="dropup" style="position: static;">
        <button id="bottomMenuMapsSize" title="Maps Size" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="/img/icons/imageresolution.png" alt="">
        </button>
        <ul id="bottomDropdownMenuMapsSize" class="dropdown-menu" aria-labelledby="bottomMenuMapsSize" style="left: unset; right: 0;">
            <li class="dropdown-header">Images quality</li>
            <li><a id="bottomMenuMapsSize_4096" value="4096" href="#"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> High</a></li>
            <li><a id="bottomMenuMapsSize_2048" value="2048" href="#"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Medium</a></li>
            <li><a id="bottomMenuMapsSize_1024" value="1024" href="#"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Low</a></li>
        </ul>
    </span>
    <button id="bottomMenuVR" title="VR mode">
        <img src="/img/icons/vr.png" alt="">
    </button>
    <button id="bottomMenuFullScreen" title="@lang('Full Screen')">
        <img id="bottomMenuFullScreenImg" src="/img/icons/fullscreen.png" alt="">
        <img id="bottomMenuCancelFullScreenImg" src="/img/icons/normalscreen.png" alt="">
    </button>
</div>


<script src="/js/room/three.min.js"></script>
<script src="/js/room/tween.min.js"></script>

@if (config('app.js_as_module'))
<script src="/js/src/3d/Mirror.js"></script>
<script src="/js/src/3d/DeviceOrientationControls.js"></script>
<script src="/js/src/3d/FirstPersonControls.js"></script>
<script src="/js/src/3d/OrbitControls.js"></script>
<script src="/js/src/3d/StereoEffect.js"></script>
<script type="module" src="/js/src/3d/interior3d.js"></script>
@else
<script src="/js/room/3d.min.js"></script>
@endif

@endsection
