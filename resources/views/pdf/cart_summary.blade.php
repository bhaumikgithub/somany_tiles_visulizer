@php use App\Helpers\Helper; @endphp
@extends('layouts.pdf')

@section('content')
    <div class="container pdf-summary-container">
        <!-- Header Section -->
        <div class="row header-section mt-20">
            <div class="col-md-12 col-xs-12 d-flex flex-wrap align-items-center">
                <div class="logo d-flex flex-column">
                    <img src="{{asset('img/tiles_visu_logo.png')}}" alt="Tiles Logo">
                </div>
{{--                <div class="intro d-flex flex-column">--}}
{{--                    <p>Your space reflects your personality; make it impressive. We bring to you an exclusive selection--}}
{{--                        of tiles that are engineered to perfection. Explore designs that make every space memorable.--}}
{{--                    </p>--}}
{{--                </div>--}}
            </div>
        </div>

        <!-- Main Content -->
        <div class="row mt-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h3 class="product-title font-bold">Your Product Selection</h3>
                <p>Date: <span>{{\Carbon\Carbon::now()->format('d-m-Y')}}</span></p>
{{--                <p>Name: <span class="font-bold">customer name</span></p>--}}
{{--                <p>Number: <span class="font-bold">customer number</span></p>--}}
                <p>Here are the products you’ve selected from our collection. Visit more on
                    <a class="cmn_link" href="https://tilevisualizer.com/" target="_blank">www.tilesvisualizer.com</a>
                </p>
            </div>
            <!-- <div class="col-md-3 col-sm-12 col-xs-12" >
                <button class="btn btn-danger modify-btn" onclick="window.location.href='{{url('/room2d/'.@$allProduct[0]->room_id)}}';">Add More or
                    Modify Selection
                </button>
            </div> -->
        </div>
        <div class="row">
            <div class="col-md-9 col-sm-12 col-xs-12">
                @if( isset($allProduct))
                    @foreach($allProduct as $index=>$item)
                        <h4 class="selection-title">Selection {{$index+1}} of {{$allProduct->count()}}</h4>
                        <img src="{{ asset('storage/'.$item->current_room_design) }}" alt="Room"
                             class="img-responsive product-image">
                        @foreach(json_decode($item->tiles_json) as $tile_detail)
                            <h5 class="mt-20 font-bold dark-grey-font">{{ucfirst($tile_detail->surface)}}</h5>
                            <div class="details-card" id="{{$index . '_' . $loop->index}}">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-pad-set">
                                        <div class="img-wall-set">
                                            <img src="{{ asset($tile_detail->icon) }}" alt="{{$tile_detail->surface}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12 col-pad-set">
                                        <p class=" cap-text">{{$tile_detail->name}}</p>
                                        <p class="">{{$tile_detail->width}} × {{$tile_detail->height}} MM</p>
                                        <p class="">{{$tile_detail->finish}}</p>
                                        <p class="">Sap Code: 12312321312</p>
                                    </div>
                                    <div id="tile{{$tile_detail->id}}" class="col-md-3 col-sm-3 col-xs-12 col-pad-set xs-margin-set" data-weight="{{$tile_detail->width}}" data-height="{{$tile_detail->height}}">
                                        <input type="hidden" value="{{$tile_detail->width}}" id="tiles_width">
                                        <input type="hidden" value="{{$tile_detail->height}}" id="tiles_height">

                                        @if( isset($tile_detail->total_area_sq_meter) && $tile_detail->total_area_sq_meter !== null )
                                            <div class="tiles_calculation_wrapper tiles_calculation_wrapper_from_db">
                                                <input type="hidden" value="{{$tile_detail->width_in_feet}}" id="width_in_feet">
                                                <input type="hidden" value="{{$tile_detail->height_in_feet}}" id="height_in_feet">
                                                <input type="hidden" value="{{$tile_detail->wastage}}" id="tiles_wastage">
                                        @else
                                            <div class="tiles_calculation_wrapper" style="display: none;">
                                        @endif
                                            <p>Total Area: <span class="total_area_covered_meter">{{@$tile_detail->total_area_sq_meter}}</span> Sq. Meter</p>
                                            <p>Total Area: <span class="total_area_covered_feet">{{@$tile_detail->total_area}}</span> Sq. Feet</p>
                                            <p>Wastage: <span class="tiles_wastage">{{@$tile_detail->wastage}}</span> %</p>
                                            <p>Tiles Needed: <span class="tiles_needed">{{@$tile_detail->tiles_needed}}</span></p>
                                        </div>

                                        <?php $tiles_par_box = Helper::getTilesParCarton($tile_detail->id);?>
                                        <input type="hidden" value="{{$tiles_par_box}}" id="tiles_par_carton">
                                        @if( $tiles_par_box !== NULL )
                                            <div class="tiles_carton_wrapper" style="display: none;">
                                                <p>Number of Box Required: <span class="require_box"></span></p>
                                            </div>
                                            <p>Tiles in 1 Box: <span class="tiles_in_box">{{$tiles_par_box}}</span></p>
                                        @endif
                                        <button class="tile-cal-link" id="tile_cal" data-tile-id="{{$tile_detail->id}}" data-calculate-cart-item-id="{{$item->id}}">Open Tiles Calculator
                                        </button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12 col-pad-set text-right xs-text-left update_price_wrapper"
                                         data-price-tile-id="{{$tile_detail->id}}" data-cart-item-id="{{$item->id}}">
                                        <button id="update_price_btn" class="update_price_btn" data-tile-id="{{$tile_detail->id}}" data-price-update-cart-item-id="{{$item->id}}">
                                              <?php $getPrice = Helper::getTilePrice($tile_detail->id,$item->id); ?>
                                                <input type="hidden" value="{{( $getPrice === "" || $getPrice === NULL ) ? "" : $getPrice }}" name="confirm_price" id="confirm_price">
                                                <h5 class="font-bold dark-grey-font mt-0 mr-10 margin-bottom-5 price_lbl" id="{{$index . '_' . $loop->index . '_'. 'price'}}">
                                                @if($getPrice === "" || $getPrice === NULL )
                                                    Price not given
                                                @else
                                                    Rs. <span class="price-update">{{$getPrice}}</span>/sq.ft
                                                @endif
                                            </h5>
                                        </button>
                                        <button type="button" class="tile-cal-link mt-0 mr-10 confirm_update" data-confirm-tile-id="{{$tile_detail->id}}" data-confirm-cart-item-id="{{$item->id}}">Update Price
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="right-panel form-container">
                    <form action="{{ route('generate-pdf') }}" name="fill_basic_form" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName">
                        </div>
                        <div class="form-group">
                            <label for="mobileNumber">Mobile Number</label>
                            <input type="text" class="form-control" id="mobileNumber" name="mobileNumber">
                        </div>
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state">
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city">
                        </div>
                        <input type="hidden" value="{{$randomKey}}" name="random_key">
                        <button class="btn btn-danger download-btn" id="download_pdf" disabled>Download PDF</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h5 class="font-bold">Disclaimer:</h5>
                <ul class="notes_ul">
                    <li>The visuals are for reference purposes only; actual colors, finishes, and tile dimensions may vary.</li>
                    <li>Shade variation is an inherent characteristic of tiles; therefore, physical inspection is
                    recommended for accurate selection</li>
                    <li>Tiles with multiple faces feature varied pa0erns, resulting in natural design variations</li>
                    <li>Prices quoted are subject to change without prior notice. The final price applicable at the time of
                    delivery will prevail.</li>
                </ul>
            </div>
        </div>
        <hr style="border: 1px solid;">
        <!-- Footer Section -->
        <div class="footer-section row ">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <p><strong>Contact Person Details</strong></p>
                <p><strong>Executive Name:</strong> <span>John Doe</span></p>
                <p><strong>Executive Number:</strong> <span>+91-9876543210</span></p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 text-right xs-text-left xs-margin-top-20">
                <p><strong>Showroom Information</strong></p>
                <p>Show Room Address, Showroom State, Showroom City</p>
                <p>Show Room Pincode</p>
            </div>
        </div>
        <hr style="border: 1px solid;">
        <!-- Footer Section -->
        <div class="footer-section row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <p>Toll Free Number: <a href="tel:1800-1030-004" class="tile-cal-link font-bold">1800-1030-004</a></p>
                <p>09:30 am to 6:30 pm</p>
                <p>Monday to Saturday</p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 text-right xs-text-left xs-margin-top-20">
                <p><span>Email Tile Enquiries:</span><br/><a href="mailto:customer.care@somanyceramics.com" class="tile-cal-link font-bold mt-0">customer.care@somanyceramics.com</a></p>
                <p>International Business Enquiries:<br/><a href="mailto:export@somanyceramics.com" class="tile-cal-link font-bold mt-0">export@somanyceramics.com</a></p>
                
            </div>
        </div>
    </div>
    </div>
   

    <!-- tile_cal modal start -->
    <div class="modal fade" id="tilecal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" id="closeTileCalcModal">&times;</button>
                    <h4 class="modal-title">Tiles Calculator
                    </h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 cmn-form-data">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="width_feet">Enter Floor/wall's Width</label>
                                            <input type="number" class="form-control" id="width_feet" name="width_feet" placeholder="Width in Feet">
                                        </div>
                                        <div class="form-group">
                                            <label for="length_feet">Enter Floor/wall's Length/Height</label>
                                            <input type="number" class="form-control" id="length_feet" name="length_feet" placeholder="Length/Height">
                                        </div>
                                        <div class="form-group">
                                            <label for="tiles_size">Tiles Size</label>
                                            <input type="hidden" value="" id="sizes" name="sizes">
                                            <input type="text" class="form-control" id="tiles_size" name="tiles_size" readonly="readonly">
                                        </div>
                                        <div class="form-group">
                                            <label for="wast_per">Wastage in Percentage</label>
                                            <input type="number" class="form-control" id="wast_per" name="length_feet" placeholder="Percentage">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 result-main">
                                        <div id="result" class="col-12 result_clas"></div>
                                        <div class="form-label" id="area_covered_meter"></div>
                                        <div class="form-label" id="area_covered_feet"></div>
                                        <div class="form-label" id="required_tiles"></div>
                                        <div class="form-label" id="required_box" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="btn-div d-flex flex-wrap">
                                    <input type="hidden" value="" id="calc_area_covered_meter" name="calc_area_covered_meter">
                                    <input type="hidden" value="" id="calc_area_covered" name="calc_area_covered_meter">
                                    <input type="hidden" value="" id="calc_wastage" name="calc_wastage">
                                    <input type="hidden" value="" id="calc_tiles_needed" name="calc_tiles_needed">
                                    <input type="hidden" value="" id="calc_tiles_par_carton" name="calc_tiles_par_carton">
                                    <input type="hidden" value="" id="calc_tile_id" name="calc_tile_id">
                                    <input type="hidden" value="" id="calc_cart_item_id" name="calc_cart_item_id">
                                    <a href="javascript:void(0);" id="calculate_btn" class="btn modify-btn tile-cal-btn">Calculate</a>
                                    <a href="javascript:void(0);" id="reset_btn" class="btn modify-btn ml-3 tile-cal-btn ml-10 reset_btn">Reset</a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
    <!-- update price modal start -->
    <div class="modal fade" id="updateprice" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tiles Price in Sq. Ft</h4>
                </div>
                <div class="modal-body">
                    <form id="priceForm">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 cmn-form-data">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="price">Enter Price</label>
                                            <input type="text" class="form-control set_price" id="price" name="price"
                                                   placeholder="Tiles price in Sq. Ft" pattern="[0-9]+" maxlength="5" required>
                                            <span id="price-error" class="text-danger"></span>  <!-- This is where the error message will be shown -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="btn-div d-flex flex-wrap ">
                                    <input type="hidden" id="tile_id" name="tile_id">
                                    <input type="hidden" id="cart_item_id" name="cart_item_id">
                                    <button type="button" class="btn btn-danger modify-btn tile-cal-btn mt-0"
                                            id="submit_btn">Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div tabindex="-1">

@endsection

