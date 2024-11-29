<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('common.head')

    <link rel="stylesheet" href="/css/iorena/iorena.css">
    <link rel="stylesheet" href="/css/iorena/app-iorena.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!--Slick-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <!--Slick-->
</head>
<body>
    @include('js_constants.lang')
    @include('js_constants.ConfigTileVisualizer')

    <div class="bgRoom" >
        @yield('content')

        <div id="sceneLoading" class="cube-wrapper">
            <div class="cube-folding">
                <span class="leaf1"></span>
                <span class="leaf2"></span>
                <span class="leaf3"></span>
                <span class="leaf4"></span>
            </div>
            <span class="loading" data-name="Loading">Loading Scene</span>
        </div>


        @include('iorena.introContent')

        @include('iorena.roomCategories')

        @include('iorena.tilesCategory')

        @include('iorena.tilesDesigning')

        @include('iorena.share')

        @include('iorena.tilesInfo')


        @include('common.sourceLoadProgressBar')


        @include('iorena.missing')

        <!--Phone Rotation-->
        <div id="phoneRotation">
            <div class="phone">
            </div>
            <div class="message">
                Please rotate your device!
            </div>
        </div>
        <!--Phone Rotation-->

    </div>

    <script src="/js/iorena/jquery-3.2.0.js"></script>
    <script src="/js/iorena/Popper.js"></script>
    <script src="/js/iorena/jquery-migrate.min.js"></script>
    <script src="/js/iorena/bootstrap.min.js"></script>
    <script src="/js/iorena/slick.js"></script>
    <script src="/js/iorena/all.js"></script>


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
    @endif

    <script>
        $(window).on('load',function(){
            $('#introContent').modal('show');
        });
        $('.detail-category').click(function(){
            $(".sub-category").addClass("showCategory");
            $("#main-category").toggleClass("hideCategory");
        });
        $('#backButton').on('click',function () {
            $(".sub-category").removeClass("showCategory");
            $("#main-category").removeClass("hideCategory");
        });
        $('.openBtn').on('click', function (e) {
            $('.tilesCategory').toggleClass('openSidebar');
        });
        $('.groupSelection').slick({
            dots: false,
            slidesToShow: 4,
            slidesToScroll: 4,
            autoplay: false,
            infinite: true,
            arrows: true,
        });
        $('.save').click(function () {
        $('.saveAs').toggleClass('leftPadding');
        });
        $('.share').click(function () {
            $('.socialIcons').toggleClass('leftPadding');
        });
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>
