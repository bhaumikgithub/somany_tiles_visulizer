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
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/pdf.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="/js/app.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/room/add_to_pdf_room.js"></script>
<script>
    $(document).on('keydown', function(e){
          if (e.key === "Escape") {
            e.preventDefault();
          }
    });
</script>
@stack('custom-scripts')
</body>
</html>
