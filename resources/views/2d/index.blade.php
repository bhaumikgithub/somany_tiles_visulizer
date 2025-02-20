@extends('layouts.front')
@section('content')
    <div class="onLoadWrapper" style="display: none;">
        <div class="container-fluid mt-2 desktop-div" style="padding-right: 2px;">
            <div class="grow hover_1 first_width hover_2">
                <a href="{{url('/listing/livingroom')}}" onclick="fetchCategory('livingroom');">
                    <div class="image1" style="writing-mode: vertical-rl;">
                        <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Living Room</h1>
                    </div>
                </a>
            </div>

            <div class="grow hover_2">
                <a href="{{url('/listing/bedroom')}}" class="category-link" data-category="bedroom">
                    <div class="image2" style="writing-mode: vertical-rl;">
                        <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Bedroom</h1>
                    </div>
                </a>
            </div>

            <div class="grow hover_2" >
                <a href="{{url('/listing/kitchen')}}" class="category-link" data-category="kitchen">
                    <div class="image3" style="writing-mode: vertical-rl;">
                        <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Kitchen</h1>
                    </div>
                </a>
            </div>

            <div class="grow hover_2" >
                <a href="{{url('/listing/bathroom')}}" class="category-link" data-category="bathroom">
                    <div class="image4" style="writing-mode: vertical-rl;">
                        <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Bathroom</h1>
                    </div>
                </a>
            </div>

            <div class="grow hover_2" >
                <a href="{{url('/listing/prayer-room')}}" class="category-link" data-category="prayer-room">
                    <div class="image5" style="writing-mode: vertical-rl;">
                        <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Prayer Room</h1>
                    </div>
                </a>
            </div>

            <div class="grow hover_2" >
                <a href="{{url('/listing/outdoor')}}"class="category-link" data-category="outdoor">
                    <div class="image6" style="writing-mode: vertical-rl;">
                        <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Outdoor</h1>
                    </div>
                </a>
            </div>

            <div class="grow hover_2">
                <a href="{{url('/listing/commercial')}}" class="category-link" data-category="commercial">
                    <div class="image7" style="writing-mode: vertical-rl;">
                        <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Commercial</h1>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="container pr-0 mt-2 mobile-div">
        <div class="">
            <div class=" mobile-index-main">
                <a href="{{url('/listing/livingroom')}}" class="category-link" data-category="livingroom">
                    <div class="mobile_grow mobile_image1 mobile_hover_1 mobile_hover_2 col-md-12" >
                        <h1 class="mobile_h1">Living Room</h1>
                    </div>
                </a>
                <a href="{{url('/listing/bedroom')}}" class="category-link" data-category="bedroom">
                    <div class="mobile_grow mobile_image2 mobile_hover_2 " >
                        <h1 class="mobile_h1">Bedroom</h1>
                    </div>
                </a>
                <a href="{{url('/listing/kitchen')}}" class="category-link" data-category="kitchen">
                    <div class="mobile_grow mobile_image3 mobile_hover_2 " >
                        <h1 class="mobile_h1">Kitchen</h1>
                    </div>
                </a>
                <a href="{{url('/listing/bathroom')}}" class="category-link" data-category="bathroom">
                    <div class="mobile_grow mobile_image4 mobile_hover_2 " >
                        <h1 class="mobile_h1">Bathroom</h1>
                    </div>
                </a>
                <a href="{{url('/listing/prayer-room')}}" class="category-link" data-category="prayer-room">
                    <div class="mobile_grow mobile_image5 mobile_hover_2 " >
                        <h1 class="mobile_h1">Prayer Room</h1>
                    </div>
                </a>
                <a href="{{url('/listing/outdoor')}}" class="category-link" data-category="outdoor">
                    <div class="mobile_grow mobile_image6 mobile_hover_2 " >
                        <h1 class="mobile_h1">Outdoor</h1>
                    </div>
                </a>
                <a href="{{url('/listing/commercial')}}" class="category-link" data-category="commercial">
                    <div class="mobile_grow mobile_image7 mobile_hover_2 ">
                        <h1 class="mobile_h1">Commercial</h1>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
