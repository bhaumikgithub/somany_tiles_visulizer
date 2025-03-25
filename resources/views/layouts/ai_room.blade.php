<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('roomAI.common_head')
    <link href="/modules/color-picker/color-picker.min.css" type="text/css" rel="stylesheet">
    <link href="/css/front.css" type="text/css" rel="stylesheet">
    <link href="/css/custom.css" type="text/css" rel="stylesheet">
</head>

<body>
    @include('js_constants.lang')
    @include('js_constants.ConfigTileVisualizer')

    @include('common.alerts')

    @include('common.sourceLoadProgressBar')

    @yield('content')

    @include('common.logo')

    @include('common.' . config('app.product_panel') . 'productPanel')

    @include('common.productInfoPanel')

    @include('common.addToCartInfoPanel')

    @include('common.applyingTilesAnimation')

    @include('common.addingToPdf')

    <script src="/js/app.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/roomAI/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gammacv@0.5.3/dist/index.min.js"></script>
    <script src="/js/roomAI/image.ops.js"></script>
    <script src="/js/roomAI/2d_room_ai.min.js"></script>
    <script src="/modules/color-picker/color-picker.min.js"></script>

    @if (config('app.js_pdf_lib') == 'jsPDF' || config('app.tiles_designer'))
    <script src="/js/room/jspdf.min.js"></script>
    @endif

    @if (config('app.js_pdf_lib') == 'pdfMake')
    <script src="/js/room/pdfmake.min.js"></script>
    <script src="/js/room/vfs_fonts.js"></script>
    @endif
    <script src="/js/roomAI/custom.js"></script>

</body>