@extends('layouts.front')
@section('content')
 <div class="">
    <div class="mt-2 mb-2 row" style="padding: 0;margin-left:0.5%;margin-right: 0.5%;">
        @if(isset($rooms))
            @foreach($rooms as $aRoom)
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-section">
                    <div class="cms-element-text">
                        <div class="body-selection-item">
                            <a title="{{$aRoom->name}}" href="{{'/room2d/' . $aRoom->id}}">
                                <div class="body-selection-item-text">{{$aRoom->name}}</div>
                                <div style="background-image:url({{$aRoom->icon}});" class="body-selection-item-image"></div>
                               <!-- <div style="background-image:url(../images/l-1.jpg);" class="body-selection-item-image"></div> -->
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <a href="{{ url('/') }}" class=" cmn-back-btn d-flex flex-wrap align-items-center" >  <span class="span-icon"><i class="fa-solid fa-arrow-left"></i>
    </span> <span class="btn-text-set">Back </span> </a>
</div>
@endsection