<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ App\Company::findOrFail(1)->name }}</title>


    <!-- base:css -->
    <link rel="stylesheet" href="/css/admin/vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="/css/admin/vendors/css/vendor.bundle.base.css">

    <!-- inject:css -->
    <link rel="stylesheet" href="/css/admin/vertical-layout-light/style.css">

    <link href="/css/daterangepicker.css" rel="stylesheet">
    <link href="/css/dashboard.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper" style="padding-top: 0px;">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">


                        <p class="sidebar-menu-title"><a href="/home">Back to Admin</a></p>
                    </li>
                    <li class="nav-item {{ $view_name == 'dashboard.index' ? 'active' : '' }}">
                        <a class="nav-link" href="/dashboard">
                            <i class="typcn typcn-device-desktop menu-icon"></i>
                            <span class="menu-title">Dashboard </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('analytics.details', ['type' => 'pincode']) }}?start_date={{ now()->subDays(6)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}">
                            <i class="typcn typcn-briefcase menu-icon"></i>
                            <span class="menu-title">Pincode</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('analytics.details', ['type' => 'appliedTiles']) }}?start_date={{ now()->subDays(6)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}">
                            <i class="typcn typcn-film menu-icon"></i>
                            <span class="menu-title">Tiles Applied On</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('analytics.details', ['type' => 'roomCategories']) }}?start_date={{ now()->subDays(6)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}">
                            <i class="typcn typcn-chart-pie-outline menu-icon"></i>
                            <span class="menu-title">Room Categories</span>
{{--                            <i class="menu-arrow"></i>--}}
                        </a>
{{--                        <div class="collapse" id="charts">--}}
{{--                            <ul class="nav flex-column sub-menu">--}}
{{--                                <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('analytics.details', ['type' => 'tiles']) }}?start_date={{ now()->subDays(6)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}">
                            <i class="typcn typcn-th-small-outline menu-icon"></i>
                            <span class="menu-title">Tiles</span>
{{--                            <i class="menu-arrow"></i>--}}
                        </a>
{{--                        <div class="collapse" id="tables">--}}
{{--                            <ul class="nav flex-column sub-menu">--}}
{{--                                <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('analytics.details', ['type' => 'rooms']) }}?start_date={{ now()->subDays(6)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}">
                            <i class="typcn typcn-compass menu-icon"></i>
                            <span class="menu-title">Rooms</span>
{{--                            <i class="menu-arrow"></i>--}}
                        </a>
{{--                        <div class="collapse" id="icons">--}}
{{--                            <ul class="nav flex-column sub-menu">--}}
{{--                                <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('analytics.details', ['type' => 'rooms']) }}?start_date={{ now()->subDays(6)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}" id="viewAllShowRooms">
                            <i class="typcn typcn-user-add-outline menu-icon"></i>
                            <span class="menu-title">Showrooms</span>
                        </a>
{{--                        <div class="collapse" id="auth">--}}
{{--                            <ul class="nav flex-column sub-menu">--}}
{{--                                <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>--}}
{{--                                <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('analytics.details', ['type' => 'pdf']) }}?start_date={{ now()->subDays(6)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}" id="pdf">
                            <i class="typcn typcn-document-text menu-icon"></i>
                            <span class="menu-title">Session/PDF</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="main-panel">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <!-- base:js -->
    <script src="/css/admin/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="/js/admin/js/off-canvas.js"></script>
    <script src="/js/admin/js/hoverable-collapse.js"></script>
    <script src="/js/admin/js/template.js"></script>
    <script src="/js/admin/js/settings.js"></script>
    <script src="/js/admin/js/todolist.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="/css/admin/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="/css/admin/vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for this page-->
{{--    <script src="/js/admin/js/dashboard.js"></script>--}}
    <script src="/js/admin/js/custom.js"></script>
    <!-- End custom js for this page-->
    <script src="/js/dashboard/moment.min.js"></script>
    <script src="/js/dashboard/daterangepicker.min.js"></script>
{{--    <script src="/js/dashboard/chart.js"></script>--}}
    <script src="/js/report.js"></script>
    @stack('custom-scripts')
</body>
</html>