<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('roomAI.common_head')

    <link href="/css/roomAI/app.css" rel="stylesheet">
    <link href="/css/roomAI/ai_front.css" rel="stylesheet">
    <!-- <link href="/css/front.css" rel="stylesheet"> -->
    <link href="/modules/color-picker/color-picker.min.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

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
    @include('js_constants.ConfigTileVisualizerAI')


    @include('common.alerts')

    @include('common.roomAI.sourceLoadProgressBar2')

    @include('common.roomAI.roomListAI')


    @yield('content')

    @include('common.pincode_modal')

    @include('common.logo')

    @include('common.roomAI.' . config('app.product_panel') . 'productPanel')

    @include('common.productInfoPanel')

    @include('common.roomAI.addToCartInfoPanelAI')

    @include('common.applyingTilesAnimation')

    @include('common.addingToPdf')

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

    <script src="/modules/color-picker/color-picker.min.js"></script>
    <script src="/js/room/pincode.js"></script>
    <script src="/js/roomAI/add_to_pdf.js"></script>
    <script src="/js/roomAI/custom.js"></script>

    @if (config('app.js_pdf_lib') == 'jsPDF' || config('app.tiles_designer'))
    <script src="/js/room/jspdf.min.js"></script>
    @endif

    @if (config('app.js_pdf_lib') == 'pdfMake')
    <script src="/js/room/pdfmake.min.js"></script>
    <script src="/js/room/vfs_fonts.js"></script>
    @endif
</body>
</html>