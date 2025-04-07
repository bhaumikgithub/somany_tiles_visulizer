<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('roomAI.common_head')
</head>
<body>

@yield('content')
@include('common.alerts')

@include('common.pincode_modal')

<!-- Scripts -->
<script src="/js/app.js"></script>
<script src="/js/roomAI/three.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/modules/color-picker/color-picker.min.js"></script>
<script src="/js/room/pincode.js"></script>
<script src="/js/roomAI/2d_room_ai.min.js"></script>
<script src="/js/roomAI/room_ai.js"></script>
</body>
</html>
