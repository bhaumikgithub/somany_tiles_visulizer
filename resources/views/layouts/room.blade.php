<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('common.head')

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    @if (config('app.sub_css'))<link href="/css/{{ config('app.sub_css') }}" type="text/css" rel="stylesheet">@endIf

    <link href="/modules/color-picker/color-picker.min.css" type="text/css" rel="stylesheet">
    <link href="/css/front.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/lil-gui@0.18"></script>

    @if (config('app.room_font_family'))
    <style>
    body {
        font-family: {{ config('app.room_font_family') }};
    }
    </style>
    @endif
    
    <!-- Google tag (gtag.js) -->

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-64919967-1"></script>

<script>

  window.dataLayer = window.dataLayer || [];

  function gtag(){dataLayer.push(arguments);}

  gtag('js', new Date());

 

  gtag('config', 'UA-64919967-1');

</script>
</head>
<body>
    @include('js_constants.lang')
    @include('js_constants.ConfigTileVisualizer')


    @include('common.alerts')

    @include('common.sourceLoadProgressBar')

    @include('common.roomsList')

    @include('common.modalDialogs')


    @yield('content')


    @include('common.logo')

    @include('common.' . config('app.product_panel') . 'productPanel')

    @include('common.productInfoPanel')

    @include('common.addToCartInfoPanel')

    @include('common.applyingTilesAnimation')

    @if (config('app.tiles_designer'))
        @include('2d.tilesDesigner')
    @endif


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


    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    @if (config('app.js_as_module'))
        <script type="module" src="/js/src/2d/interior2d.js"></script>
    @else
        <script src="/js/room/2d.min.js"></script>

    @endif
    <script src="/modules/color-picker/color-picker.min.js"></script>

    @if (config('app.js_pdf_lib') == 'jsPDF' || config('app.tiles_designer'))
    <script src="/js/room/jspdf.min.js"></script>
    @endif

    @if (config('app.js_pdf_lib') == 'pdfMake')
    <script src="/js/room/pdfmake.min.js"></script>
    <script src="/js/room/vfs_fonts.js"></script>
    @endif

    <script src="/js/room/add_to_pdf_room.js"></script>
    <script src="/js/room/custom.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        
       $(window).resize(function() {
      var windowWidth = $(window).width();
      var windowHeight = $(window).height();
      var newWidth = windowHeight * 1.78;
      var newLeft = Math.round((windowWidth-newWidth)/2);
      $("#roomCanvas").height(windowHeight);
      $("#roomCanvas").width(newWidth);
      $("#container").css({left:newLeft });
      });
      });
        
    </script>
</body>
</html>
