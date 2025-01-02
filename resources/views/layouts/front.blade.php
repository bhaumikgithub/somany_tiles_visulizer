<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">

    <title>{{ App\Company::findOrFail(1)->name }}</title>

    <link rel="shortcut icon" href="{{ App\Company::findOrFail(1)->logo }}" />

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:title" content="{{ App\Company::findOrFail(1)->name }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="Tile Visualizer" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
    </script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/front.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
@if (config('app.sub_css'))<link href="/css/{{ config('app.sub_css') }}" type="text/css" rel="stylesheet">@endIf

    <link href="/modules/color-picker/color-picker.min.css" type="text/css" rel="stylesheet">

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

    @yield('content')
    @if(session('show_pincode_modal') && !session()->has('pincode'))
        <script>
            $('#pincode').modal('show');
        </script>
    @endif
    <!-- Pin code modal start -->
    <div class="modal fade" id="pincode" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Please enter your Pincode Number</h4>
                </div>
                <div class="modal-body">
                    <form id="pincodeForm">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 cmn-form-data">
                                <div class="row">
                                    <div class="col-sm-9 col-xs-9">
                                        <div class="form-group">
                                            <label for="pin_code">Please enter your Pincode Number</label>
                                            <input type="text" class="form-control pin_code" id="pin_code" name="pin_code"
                                                   placeholder="Enter Pincode" minlength="6" maxlength="6" pattern="\d{6}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="btn-div d-flex flex-wrap ">
                                    <button type="submit" class="btn btn-danger modify-btn tile-cal-btn mt-0"
                                            id="pincode_submit_btn">Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/jquery-ui.min.js"></script>

    <script src="/modules/color-picker/color-picker.min.js"></script>

    @if (config('app.js_pdf_lib') == 'jsPDF' || config('app.tiles_designer'))
        <script src="/js/room/jspdf.min.js"></script>
    @endif

    @if (config('app.js_pdf_lib') == 'pdfMake')
        <script src="/js/room/pdfmake.min.js"></script>
        <script src="/js/room/vfs_fonts.js"></script>
    @endif

    <script src="/js/room/add_to_pdf_room.js"></script>
    <script src="/js/room/pincode.js"></script>
</body>
</html>
