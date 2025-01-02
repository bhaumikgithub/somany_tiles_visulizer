@extends('layouts.front')
@section('content')
    <div class="container-fluid mt-2 desktop-div" style="padding-right: 2px;">
        <div class="grow hover_1 first_width hover_2">
            <a href="{{url('/listing/livingroom')}}">
                <div class="image1" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Living Room</h1>
                </div>
            </a>
        </div>

        <div class="grow hover_2">
            <a href="#">
                <div class="image2" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Bedroom</h1>
                </div>
            </a>
        </div>

        <div class="grow hover_2" >
            <a href="{{url('/listing/kitchen')}}">
                <div class="image3" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Kitchen</h1>
                </div>
            </a>
        </div>

        <div class="grow hover_2" >
            <a href="#">
                <div class="image4" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Bathroom</h1>
                </div>
            </a>
        </div>

        <div class="grow hover_2" >
            <a href="#">
                <div class="image5" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Player Room</h1>
                </div>
            </a>
        </div>

        <div class="grow hover_2" >
            <a href="{{url('/listing/outdoor')}}">
                <div class="image6" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Outdoor</h1>
                </div>
            </a>
        </div>

        <div class="grow hover_2">
            <a href="#">
                <div class="image7" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Commercial</h1>
                </div>
            </a>
        </div>
    </div>

    <div class="container-fluid pr-0 mt-2 mobile-div row" style="padding-right: 0;">
        <a href="{{url('/listing/livingroom')}}">
            <div class="mobile_grow mobile_image1 mobile_hover_1 mobile_hover_2 col-md-12" >
                <h1 class="mobile_h1">Living Room</h1>
            </div>
        </a>
        <a href="#">
            <div class="mobile_grow mobile_image2 mobile_hover_2 col-md-12" >
                <h1 class="mobile_h1">Bedroom</h1>
            </div>
        </a>
        <a href="{{url('/listing/kitchen')}}">
            <div class="mobile_grow mobile_image3 mobile_hover_2 col-md-12" >
                <h1 class="mobile_h1">Kitchen</h1>
            </div>
        </a>
        <a href="#">
            <div class="mobile_grow mobile_image4 mobile_hover_2 col-md-12" >
                <h1 class="mobile_h1">Bathroom</h1>
            </div>
        </a>
        <a href="#">
            <div class="mobile_grow mobile_image5 mobile_hover_2 col-md-12" >
                <h1 class="mobile_h1">Player Room</h1>
            </div>
        </a>
        <a href="{{url('/listing/outdoor')}}">
            <div class="mobile_grow mobile_image6 mobile_hover_2 col-md-12" >
                <h1 class="mobile_h1">Outdoor</h1>
            </div>
        </a>
        <a href="#">
            <div class="mobile_grow mobile_image7 mobile_hover_2  col-md-12">
                <h1 class="mobile_h1">Commercial</h1>
            </div>
        </a>
    </div>
@endsection