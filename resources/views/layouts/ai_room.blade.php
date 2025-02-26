<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('roomAI.common_head')
    <link href="/css/front.css" type="text/css" rel="stylesheet">
    <link href="/css/custom.css" type="text/css" rel="stylesheet">
</head>

<body>
    @include('js_constants.lang')
    @include('js_constants.ConfigTileVisualizer')
    @yield('content')

    @include('common.' . config('app.product_panel') . 'productPanel')

    @include('common.productInfoPanel')

    <script src="/js/app.js"></script>
    <script src="/js/jquery-ui.min.js"></script>

    <script src="/js/roomAI/2d_room_ai.min.js"></script>
    <script src="/js/roomAI/custom.js"></script>

</body>