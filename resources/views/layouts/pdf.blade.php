<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tiles Visualizer</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/pdf.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div>
    @yield('content')
</div>

<!-- Scripts -->
<script src="/js/app.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/room/add_to_pdf_room.js"></script>
@stack('custom-scripts')
</body>
</html>
