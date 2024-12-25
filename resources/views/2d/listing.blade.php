@extends('layouts.front')
@section('content')
    <script>
        $(function(){
            'use strict';
            $('.hover_2').on("mouseover", function () {
                $('.hover_1').removeClass('first_width');
                $('.hover_2').removeClass('first_width');
                $(this).addClass('first_width');
                // setTimeout(function(){
                //   $(this).addClass('first_width');
                // },5000);

            });
        });
        // $(function(){
        //     $('.mobile_hover_2').on("mouseover", function () {
        //         $('.mobile_hover_1').removeClass('mobile_first_width');
        //         $('.mobile_hover_2').removeClass('mobile_first_width');
        //         $(this).addClass('mobile_first_width');
        //     });
        // });
        $(function(){
            'use strict';
            $('.mobile_hover_2').on("click", function () {
                $('.mobile_hover_1').removeClass('mobile_first_width');
                $('.mobile_hover_2').removeClass('mobile_first_width');
                $(this).addClass('mobile_first_width');
            });
        });
    </script>
    <div class="mt-2 mb-2 row" style="padding: 0;margin-left:0.5%;margin-right: 0.5%;">
        @if(isset($rooms))
            @foreach($rooms as $aRoom)
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-section">
                    <div class="cms-element-text">
                        <div class="body-selection-item">
                            <a title="{{$aRoom->name}}" href="{{'/room2d/' . $aRoom->id}}">
                                <div class="body-selection-item-text">"{{$aRoom->name}}</div>
                                <div style="background-image:url({{$aRoom->image2}});" class="body-selection-item-image"></div>
{{--                                <div style="background-image:url(../images/l-1.jpg);" class="body-selection-item-image"></div>--}}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

@endsection