@extends('layouts.front')
@section('content')
    <div class="room-content-container">
        <div class="mt-2 mb-2 row" style="padding: 0;margin-left:0.5%;margin-right: 0.5%;">
            @if(isset($rooms))
                @foreach($rooms as $aRoom)
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-section">
                        <div class="cms-element-text">
                            <div class="body-selection-item">
                                <a title="{{$aRoom->name}}" href="javascript:void(0)" onclick="fetchRoom({{$aRoom->id}},'{{$aRoom->name}}');">
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
        <div class=" cmn-back-btn" >
            <a href="{{ url('/') }}"> <span class="span-icon"><i class="fa-solid fa-arrow-left"></i>
    </span></a>
            <p class="btn-text-set-back">Back </p></div>

    </div>
@endsection