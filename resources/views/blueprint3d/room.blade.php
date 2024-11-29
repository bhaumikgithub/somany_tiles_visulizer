<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ App\Company::findOrFail(1)->name }}</title>

    <meta property="og:title" content="{{ App\Company::findOrFail(1)->name }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL::to('/') }}" />
    <meta property="og:description" content="Tile Visualizer" />
    <meta property="og:image" content="{{ $room_icon }}" />


    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    @if (config('app.sub_css'))<link href="/css/' . config('app.sub_css')" type="text/css" rel="stylesheet">@endIf

    <link href="/modules/color-picker/color-picker.min.css" type="text/css" rel="stylesheet">


    <!-- <link href="css/bootstrap.css" rel="stylesheet"> -->
    <link href="/css/blueprint3d.css" rel="stylesheet">


    @if (config('app.room_font_family'))
    <style>
    body {
        font-family: {{ config('app.room_font_family') }};
    }
    </style>
    @endif


    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <script src="/js/app.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
  </head>

  <body>
    @include('js_constants.lang')
    @include('js_constants.ConfigTileVisualizer')

    <script src="/js/blueprint3d/items.js"></script>
    @if (config('app.js_as_module'))
    <script src="/js/src/blueprint3d/three.js"></script>
    <script type="module" src="/js/src/blueprint3d/blueprint3d.js"></script>
    <script type="module" src="/js/src/blueprint3d/room.js"></script>
    @else
    <script src="/js/blueprint3d/three.min.js"></script>
    <script src="/js/blueprint3d/blueprint3d.lib.min.js"></script>
    <script src="/js/blueprint3d/blueprint3d.min.js"></script>
    @endif

    <div class="container-fluid">
      <div class="row main-row">
        <!-- Left Column -->
        <div class="col-xs-3 sidebar">
          <!-- Main Navigation -->
          <ul class="nav nav-sidebar">
            <li id="floorplan_tab"><a href="#">
              Edit Floorplan
              <span class="glyphicon glyphicon-chevron-right pull-right"></span>
            </a></li>
            <li id="design_tab"><a href="#">
              Design
              <span class="glyphicon glyphicon-chevron-right pull-right"></span>
            </a></li>
            <li id="items_tab"><a href="#">
              Add Items
              <span class="glyphicon glyphicon-chevron-right pull-right"></span>
            </a></li>
          </ul>
          <hr />

          <!-- Context Menu -->
          <div id="context-menu">
            <div style="margin: 0 20px">
              <span id="context-menu-name" class="lead"></span>
              <br /><br />
              <button class="btn btn-block btn-danger" id="context-menu-delete">
                <span class="glyphicon glyphicon-trash"></span>
                Delete Item
              </button>
            <br />
            <div class="panel panel-default">
              <div class="panel-heading">Adjust Size</div>
              <div class="panel-body" style="color: #333333">

                <div class="form form-horizontal" class="lead">
                  <div class="form-group">
                    <label class="col-sm-5 control-label">
                       Width
                    </label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" id="item-width">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-5 control-label">
                      Depth
                    </label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" id="item-depth">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-5 control-label">
                      Height
                    </label>
                    <div class="col-sm-6">
                      <input type="number" class="form-control" id="item-height">
                    </div>
                  </div>
                </div>
                <small><span class="text-muted">Measurements in inches.</span></small>
              </div>
            </div>

            <label><input type="checkbox" id="fixed" /> Lock in place</label>
            <br /><br />
            </div>
          </div>

          <!-- Floor textures -->
          <div id="floorTexturesDiv" style="display:none; padding: 0 20px">
            <div class="panel panel-default">
              <div class="panel-heading">Adjust Floor</div>
              <div class="panel-body" style="color: #333333">

                <div class="col-sm-6" style="padding: 3px">
                  <a href="#" class="thumbnail texture-select-thumbnail" texture-url="js/blueprint3d/rooms/textures/light_fine_wood.jpg" texture-stretch="false" texture-scale="300">
                    <img alt="Thumbnail light fine wood" src="js/blueprint3d/rooms/thumbnails/thumbnail_light_fine_wood.jpg" />
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Wall Textures -->
          <div id="wallTextures" style="display:none; padding: 0 20px">
            <div class="panel panel-default">
              <div class="panel-heading">Adjust Wall</div>
              <div class="panel-body" style="color: #333333">
                <div class="col-sm-6" style="padding: 3px">
                  <a href="#" class="thumbnail texture-select-thumbnail" texture-url="js/blueprint3d/rooms/textures/marbletiles.jpg" texture-stretch="false" texture-scale="300">
                    <img alt="Thumbnail marbletiles" src="js/blueprint3d/rooms/thumbnails/thumbnail_marbletiles.jpg" />
                  </a>
                </div>
                <div class="col-sm-6" style="padding: 3px">
                  <a href="#" class="thumbnail texture-select-thumbnail" texture-url="js/blueprint3d/rooms/textures/wallmap_yellow.png" texture-stretch="true" texture-scale="">
                    <img alt="Thumbnail wallmap yellow" src="js/blueprint3d/rooms/thumbnails/thumbnail_wallmap_yellow.png" />
                  </a>
                </div>
                <div class="col-sm-6" style="padding: 3px">
                  <a href="#" class="thumbnail texture-select-thumbnail" texture-url="js/blueprint3d/rooms/textures/light_brick.jpg" texture-stretch="false" texture-scale="100">
                    <img alt="Thumbnail light brick" src="js/blueprint3d/rooms/thumbnails/thumbnail_light_brick.jpg" />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-xs-9 main">

          <!-- 3D Viewer -->
          <div id="viewer">

            <div id="main-controls">
              <a href="#" class="btn btn-default btn-sm" id="new">
                New Plan
              </a>
              <a href="#" class="btn btn-default btn-sm" id="saveFile">
                Save Plan
              </a>
              <a class="btn btn-sm btn-default btn-file">
               <input type="file" class="hidden-input" id="loadFile">
               Load Plan
              </a>
            </div>

            <div id="camera-controls">
              <a href="#" class="btn btn-default bottom" id="zoom-out">
                <span class="glyphicon glyphicon-zoom-out"></span>
              </a>
              <a href="#" class="btn btn-default bottom" id="reset-view">
                <span class="glyphicon glyphicon glyphicon-home"></span>
              </a>
              <a href="#" class="btn btn-default bottom" id="zoom-in">
                <span class="glyphicon glyphicon-zoom-in"></span>
              </a>

              <span>&nbsp;</span>

              <a class="btn btn-default bottom" href="#" id="move-left" >
                <span class="glyphicon glyphicon-arrow-left"></span>
              </a>
              <span class="btn-group-vertical">
                <a class="btn btn-default" href="#" id="move-up">
                  <span class="glyphicon glyphicon-arrow-up"></span>
                </a>
                <a class="btn btn-default" href="#" id="move-down">
                  <span class="glyphicon glyphicon-arrow-down"></span>
                </a>
              </span>
              <a class="btn btn-default bottom" href="#" id="move-right" >
                <span class="glyphicon glyphicon-arrow-right"></span>
              </a>
            </div>

            <div id="loading-modal">
              <h1>Loading...</h1>
            </div>
          </div>

          <!-- 2D Floorplanner -->
          <div id="floorplanner">
            <canvas id="floorplanner-canvas"></canvas>
            <div id="floorplanner-controls">

              <button id="move" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-move"></span>
                Move Walls
              </button>
              <button id="draw" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-pencil"></span>
                Draw Walls
              </button>
              <button id="delete" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-remove"></span>
                Delete Walls
              </button>
              <span class="pull-right">
                <button class="btn btn-primary btn-sm" id="update-floorplan">Done &raquo;</button>
              </span>

            </div>
            <div id="draw-walls-hint">
              Press the "Esc" key to stop drawing walls
            </div>
          </div>

          <!-- Add Items -->
          <div id="add-items">
            <div class="row" id="items-wrapper">

              <!-- Items added here by items.js -->
            </div>
          </div>

        </div>
        <!-- End Right Column -->
      </div>

    </div>

    @include('common.alerts')

    @include('common.' . config('app.product_panel') . 'productPanel')

    <!--

    @include('common.sourceLoadProgressBar')

    @include('common.roomsList')

    @include('common.modalDialogs')


    @yield('content')


    @if (config('app.copyright_text') || config('app.copyright_app_developer_text'))
    <div class="copyright">
        ©
        @if (config('app.copyright_text'))
        <a href="{{ config('app.copyright_link') }}" target="blank">{{ config('app.copyright_text') }}</a>
        @endif
        @if (config('app.copyright_app_developer_text'))
        <a href="{{ config('app.copyright_app_developer_link') }}" target="blank" class="black-text">{{ config('app.copyright_app_developer_text') }}</a>
        @endif
    </div>
    @endif



    <script src="/modules/color-picker/color-picker.min.js"></script>

    @if (config('app.js_pdf_lib') == 'jsPDF' || config('app.tiles_designer'))
    <script src="/js/room/jspdf.min.js"></script>
    @endif

    @if (config('app.js_pdf_lib') == 'pdfMake')
    <script src="/js/room/pdfmake.min.js"></script>
    <script src="/js/room/vfs_fonts.js"></script>
    @endif -->

</body>
</html>



<!-- @section('content')

<div id="container" class="canvas-fullscreen"></div>

@include('common.applyingTilesAnimation')

@include('common.logo')

@include('common.' . config('app.bottom_menu') . 'bottomMenu2d')

@include('common.productInfoPanel')

@if (config('app.tiles_designer'))
    @include('2d.tilesDesigner')
@endif


<script src="/js/room/three.min.js"></script>

@if (config('app.js_as_module'))
<script type="module" src="/js/src/blueprint3d/blueprint3d.js"></script>
@else
<script src="/js/room/blueprint3d.min.js"></script>
@endif

@endsection -->
