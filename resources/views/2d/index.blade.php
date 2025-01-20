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
            <a href="{{url('/listing/bathroom')}}">
                <div class="image4" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Bathroom</h1>
                </div>
            </a>
        </div>

        <div class="grow hover_2" >
            <a href="{{url('/listing/prayer-room')}}">
                <div class="image5" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Prayer Room</h1>
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
            <a href="{{url('/listing/commercial')}}">
                <div class="image7" style="writing-mode: vertical-rl;">
                    <h1 style="text-orientation: mixed;padding: 60px 5px 5px 5px;">Commercial</h1>
                </div>
            </a>
        </div>
    </div>

    <div class="container pr-0 mt-2 mobile-div">
        <div class="">
            <div class=" mobile-index-main">
        <a href="{{url('/listing/livingroom')}}">
            <div class="mobile_grow mobile_image1 mobile_hover_1 mobile_hover_2 col-md-12" >
                <h1 class="mobile_h1">Living Room</h1>
            </div>
        </a>
        <a href="{{url('/listing/bedroom')}}">
            <div class="mobile_grow mobile_image2 mobile_hover_2 " >
                <h1 class="mobile_h1">Bedroom</h1>
            </div>
        </a>
        <a href="{{url('/listing/kitchen')}}">
            <div class="mobile_grow mobile_image3 mobile_hover_2 " >
                <h1 class="mobile_h1">Kitchen</h1>
            </div>
        </a>
        <a href="{{url('/listing/bathroom')}}">
            <div class="mobile_grow mobile_image4 mobile_hover_2 " >
                <h1 class="mobile_h1">Bathroom</h1>
            </div>
        </a>
        <a href="{{url('/listing/prayer-room')}}">
            <div class="mobile_grow mobile_image5 mobile_hover_2 " >
                <h1 class="mobile_h1">Prayer Room</h1>
            </div>
        </a>
        <a href="{{url('/listing/outdoor')}}">
            <div class="mobile_grow mobile_image6 mobile_hover_2 " >
                <h1 class="mobile_h1">Outdoor</h1>
            </div>
        </a>
        <a href="{{url('/listing/commercial')}}">
            <div class="mobile_grow mobile_image7 mobile_hover_2 ">
                <h1 class="mobile_h1">Commercial</h1>
            </div>
        </a>
    </div>
</div>
</div>

@endsection

  <!-- update price modal start -->
  <div class="modal fade" id="pincode" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Pincode</h4>
                </div>
                <div class="modal-body">
                    <form id="priceForm">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 cmn-form-data">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="price">Enter Pincode</label>
                                            <input type="text" class="form-control set_price" id="price" name="price"
                                                   placeholder="Enter your Pincode"   required>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="btn-div d-flex flex-wrap ">
                                    
                                    <button type="button" class="btn btn-danger modify-btn tile-cal-btn mt-0"
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