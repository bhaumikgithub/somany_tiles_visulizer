@php use App\Helpers\Helper; @endphp
@extends('layouts.pdf')

@section('content')
    <div class="container pdf-summary-container">
        <!-- Header Section -->
        <div class="row header-section mt-20">
            <div class="col-md-12 col-xs-12 d-flex flex-wrap align-items-center">
                <div class="logo d-flex flex-column">
                    <img src="{{asset('img/somany-logo-new.jpg')}}" alt="Somany Logo">
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row mt-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h3 class="product-title font-bold">Your Product Selection</h3>
                <p>Date: <span>{{ $cc_date }}</span></p>
                @if( isset($pincode) )
                    <p>Pincode: <span>{{$pincode}}</span></p>
                @endif
                <p>Here are the products you’ve selected from our collection. Visit more on
                    <a class="cmn_link" target="_blank" href="https://www.somanyceramics.com/">www.somanyceramics.com</a>
                </p>
            </div>
        </div>


        <div class="row">
            <div class="col-md-9 col-sm-12 col-xs-12">
                @if( isset($allProduct))
                    <input type="hidden" value="{{@$randomKey}}" id="random_key" class="form-control">
                    @foreach($allProduct as $index=>$item)
                        <h4 class="selection-title">Selection {{$index+1}} of {{$allProduct->count()}}</h4>
                            <?php $showImage = $item->show_main_image ;?>
                        <div class="show_main_image_wrapper" id="imageWrapper_<?= $item->id ?>"  style="display: <?php echo ($showImage === 'yes') ? 'block' : 'none'; ?>;">
                            <img src="{{ asset('storage/'.$item->current_room_design) }}" alt="Room" class="img-responsive product-image">
                        </div>
                        <input type="checkbox" value="{{$showImage}}"  name="show_main_image" id="show_main_image" {{ $showImage === "yes" ? 'checked' : '' }} data-cart-item-id="{{$item->id}}"> Show Image?
                        @php
                            $tiles = collect(json_decode($item->tiles_json));
                            // Check if the first item has the surface_title key
                            $tilesData = $tiles->isNotEmpty() && isset($tiles->first()->surface_title)
                                ? $tiles->sortBy('surface_title')->values()
                                : $tiles;
                        @endphp
                        @foreach($tilesData as $tile_detail)
                            @if( $tile_detail->surface !== "paint" )
                                <h5 class="mt-20 font-bold dark-grey-font">
                                    @if( isset($tile_detail->surface_title ) )
                                        {{ucfirst($tile_detail->surface_title)}}
                                    @else
                                        {{ucfirst($tile_detail->surface)}}
                                    @endif
                                    @if( isset($tile_detail->free_tile ) && $tile_detail->free_tile === true )
                                        ( Free Tile )
                                    @endif
                                </h5>
                                <div class="details-card" id="{{$index . '_' . $loop->index}}">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-pad-set">
                                            <div class="img-wall-set">
                                                <img src="{{ asset($tile_detail->icon) }}" alt="{{$tile_detail->surface}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12 col-pad-set">
                                            <p class=" cap-text"><b>{{$tile_detail->name}}</b></p>
                                            <p class="">{{$tile_detail->width}} × {{$tile_detail->height}} MM</p>
                                            <p class="">{{ucfirst($tile_detail->finish)}}</p>
                                                <?php $sku = Helper::getSAPCode($tile_detail->id);?>
                                            @if( $sku !== null )
                                                <p class="">Sap Code: {{$sku}}</p>
                                            @endif
                                        </div>
                                        <div id="tile{{$tile_detail->id}}" class="col-md-3 col-sm-3 col-xs-12 col-pad-set xs-margin-set" data-weight="{{$tile_detail->width}}" data-height="{{$tile_detail->height}}">
                                            <input type="hidden" value="{{$tile_detail->width}}" id="tiles_width">
                                            <input type="hidden" value="{{$tile_detail->height}}" id="tiles_height">

                                            @if( isset($tile_detail->total_area_sq_meter) && $tile_detail->total_area_sq_meter !== null )
                                                <div class="tiles_calculation_wrapper_{{$item->id}}_{{$loop->index}} tiles_calculation_wrapper_from_db">
                                                    <input type="hidden" value="{{$tile_detail->width_in_feet}}" id="width_in_feet">
                                                    <input type="hidden" value="{{$tile_detail->height_in_feet}}" id="height_in_feet">
                                                    <input type="hidden" value="{{$tile_detail->wastage}}" id="tiles_wastage">
                                                    @else
                                                        <div class="tiles_calculation_wrapper_{{$item->id}}_{{$loop->index}}" style="display: none;">
                                                            @endif
                                                            <p>Total Area: <span class="total_area_covered_meter">{{@$tile_detail->total_area_sq_meter}}</span> Sq. Meter</p>
                                                            <p>Total Area: <span class="total_area_covered_feet">{{@$tile_detail->total_area}}</span> Sq. Feet</p>
                                                            <p>Wastage: <span class="tiles_wastage">{{@$tile_detail->wastage}}</span> %</p>
                                                            <p>Tiles Needed: <span class="tiles_needed">{{@$tile_detail->tiles_needed}}</span></p>
                                                        </div>

                                                            <?php $tiles_par_box = Helper::getTilesParCarton($tile_detail->id);?>
                                                        <input type="hidden" value="{{$tiles_par_box}}" id="tiles_par_carton">
                                                        @if( $tiles_par_box !== NULL )
                                                            <div class="tiles_carton_wrapper_{{$item->id}}_{{$loop->index}}" style="display: <?php echo ($tiles_par_box !== NULL ) ? 'block' : 'none'; ?>">
                                                                <input type="hidden" value="" id="require_box">
                                                                @isset($tile_detail->box_needed)
                                                                    <p>Number of Box Required: <span class="require_box">{{@$tile_detail->box_needed}}</span></p>
                                                                @else
                                                                    <p class="textBoxWrap" style="display: none;">Number of Box Required: <span class="require_box"></span></p>
                                                                @endisset
                                                            </div>
                                                            <p>Tiles in 1 Box: <span class="tiles_in_box">{{$tiles_par_box}}</span></p>
                                                        @endif
                                                        @if( isset($pincode) )
                                                            @php
                                                                $surface_title = (  isset($tile_detail->surface_title ) ) ? ucfirst($tile_detail->surface_title) : ucfirst($tile_detail->surface) ;
                                                            @endphp
                                                            <button class="tile-cal-link tile_calculation" id="tile_cal" data-tile-id="{{$tile_detail->id}}" data-calculate-cart-item-id="{{$item->id}}" data-unique-id={{$loop->index}} data-surface-name="{{str_replace(" ","_",$surface_title)}}">Open Tiles Calculator
                                                            </button>
                                                        @endif
                                                </div>
                                                {{--                                            <div class="col-md-3 col-sm-3 col-xs-12 col-pad-set text-right xs-text-left update_price_wrapper"--}}
                                                {{--                                                 data-price-tile-id="{{$tile_detail->id}}" data-cart-item-id="{{$item->id}}">--}}
                                                {{--                                                <button id="update_price_btn" class="update_price_btn" data-tile-id="{{$tile_detail->id}}" data-price-update-cart-item-id="{{$item->id}}">--}}
                                                {{--                                                        <?php $getPrice = Helper::getTilePrice($tile_detail->id,$item->id); ?>--}}
                                                {{--                                                    <input type="hidden" value="{{( $getPrice === "" || $getPrice === NULL ) ? "" : $getPrice }}" name="confirm_price" id="confirm_price">--}}
                                                {{--                                                    <h5 class="font-bold dark-grey-font mt-0 mr-10 margin-bottom-5 price_lbl" id="{{$index . '_' . $loop->index . '_'. 'price'}}">--}}
                                                {{--                                                        @if($getPrice === "" || $getPrice === NULL )--}}
                                                {{--                                                            Price not given--}}
                                                {{--                                                        @else--}}
                                                {{--                                                            Rs. <span class="price-update">{{$getPrice}}</span>/sq.ft--}}
                                                {{--                                                        @endif--}}
                                                {{--                                                    </h5>--}}
                                                {{--                                                </button>--}}
                                                {{--                                                @if( session()->has('pincode') )--}}
                                                {{--                                                    <button type="button" class="tile-cal-link mt-0 mr-10 confirm_update" data-confirm-tile-id="{{$tile_detail->id}}" data-confirm-cart-item-id="{{$item->id}}">Update Price--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                @endif--}}
                                                {{--                                            </div>--}}
                                        </div>
                                    </div>
                                    @endif
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
                                                <input type="text" class="form-control" id="firstName" name="firstName" value="{{ old('firstName', $upform_data->first_name ?? '') }}"
                                                        {{ $isReadOnly ? 'readonly' : '' }}>
                                            </div>
                                            <div class="form-group">
                                                <label for="lastName">Last Name</label>
                                                <input type="text" class="form-control" id="lastName" name="lastName" value="{{ old('lastName', $upform_data->last_name ?? '') }}"
                                                        {{ $isReadOnly ? 'readonly' : '' }}>
                                            </div>
                                            <div class="form-group">
                                                <label for="mobileNumber">Mobile Number</label>
                                                <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" value="{{ old('mobileNumber', $upform_data->mobile ?? '') }}"
                                                        {{ $isReadOnly ? 'readonly' : '' }}>
                                            </div>
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $upform_data->state ?? '') }}"
                                                        {{ $isReadOnly ? 'readonly' : '' }}>
                                            </div>
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                @if( isset($pincode) )
                                                    <input type="hidden" class="form-control" name="pincode" value="{{$pincode}}">
                                                @endif
                                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $upform_data->city ?? '') }}"
                                                        {{ $isReadOnly ? 'readonly' : '' }}>
                                            </div>
                                            <input type="hidden" value="{{$randomKey}}" name="random_key">
                                            @if( isset($upform_data))
                                                <button class="btn btn-danger download-btn" id="download_pdf">Download PDF</button>
                                            @else
                                                <button class="btn btn-danger download-btn" id="download_pdf" disabled>Download PDF</button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
            </div>
            <!-- Table structure -->
            @if(isset($groupedTiles))
                <div class="summary-page-table-row">
                 <div class="">
                    <table class="table summary-page-table" id="summary-table">
                    <thead>
                        <tr class="table-active">
                            <th  class="text-center">Sr. No</th>
                            <th >Name</th>
                            <th >Size</th>
                            <th >Finish</th>
                            <th >Apply<br>On</th>
                            <th class="text-center">Area<br>Sq. Ft.</th>
                            <th class="text-center">Tiles/Box</th>
                            <th class="text-center">Box Coverage<br>Area Sq. Ft.</th>
                            <th class="text-center">Box<br> Required</th>
                            <th class="text-center">MRP</th>
                            <th class="text-center">MRP/<br>Sq. Ft.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($groupedTiles))
                            @php $totalMrpPrice = 0;
                            $i = 1;
                            @endphp
                            @foreach($groupedTiles as $index => $tile)
                                    <tr data-tile-id="{{ $tile['id'] }}">
                                        <td class="text-center">{{ $i }}</td>
                                        <td>{{ $tile['name'] }}</td>
                                        <td>{{ $tile['size'] }}</td>
                                        <td>{{ ucfirst($tile['finish']) }}</td>
                                        <td>{{ ucwords($tile['apply_on']) }}</td>
                                        <td class="text-center summary-total-area">{{ ( $tile['area_sq_ft'] === "-" ) ? "-" : number_format($tile['area_sq_ft'])  }}</td>
                                        <td class="text-center">{{ $tile['tiles_per_box'] }}</td>
                                        <td class="text-center">{{ ( $tile['box_coverage_area_sq_ft'] === "-" ) ? "-" : $tile['box_coverage_area_sq_ft'] }}</td>
                                        <td class="text-center summary-box-needed">{{ $tile['box_required'] }}</td>
                                        <td class="text-center summary-mrp-price">{{ ( $tile['mrp_price'] === "-" ) ? "-" : number_format($tile['mrp_price'])  }}</td>
                                        <td class="text-center">{{ $tile['mrp_per_sq_ft'] }}</td>
                                    </tr>
                                    @php
                                        $i++; // Increment only when a valid row is printed
                                    @endphp
                                @php
                                    $totalMrpPrice += (int)$tile['mrp_price'];
                                @endphp
                            @endforeach
                            <tr class="table-active footer-table-text">
                                <td></td>
                                <td><b>Total</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center" id="summary-total-mrp-price">{{ ( $totalMrpPrice === 0 ) ? "" : "Rs. ". number_format($totalMrpPrice) }}</td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
              </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h5 class="font-bold">Disclaimer:</h5>
                    <ul class="notes_ul">
                        <li>The visuals are for reference purposes only; actual colors, finishes, and tile dimensions may vary.</li>
                        <li>Shade variation is a natural characteristic of tiles, making each piece unique. We highly recommend a physical inspection for accurate selection. Visit our showroom for the best selection and precise assessment.</li>
                        <li>Tiles with multiple faces exhibit varied patterns, resulting in natural design variations.</li>
                        <li>Prices quoted are subject to change without prior notice. For the best offers and discounts, visit our nearest showroom.</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h5 class="font-bold">Exciting offers:</h5>
                    <p class="normalText">The prices listed above are the Maximum Retail Price (MRP). Visit your nearest Somany store to unlock exclusive offers and discover deals that'll make your wallet smile! </p>
                </div>
            </div>
            @if($userShowroomInfo['user'])
                <hr style="border: 1px solid;">
                <!-- Footer Section -->
                <div class="footer-section row ">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if($userShowroomInfo['user'])
                            <p><strong>Contact Person Details</strong></p>
                            <p><strong>Executive Name:</strong> <span>{{ $userShowroomInfo['user']['name'] }}</span></p>
                            @if( $userShowroomInfo['user']['contact_no'] )
                                <p><strong>Executive Number:</strong>
                                    <a href="tel:{{ $userShowroomInfo['user']['contact_no'] }}">
                                        {{ $userShowroomInfo['user']['contact_no'] }}
                                    </a>
                                </p>
                            @endif
                            @if( $userShowroomInfo['user']['email'] )
                                <p><strong>Executive Email:</strong>
                                    <a href="mailto:{{ $userShowroomInfo['user']['email'] }}">
                                        {{ $userShowroomInfo['user']['email'] }}
                                    </a>
                                </p>
                            @endif
                            {{--                @else--}}
                            {{--                    <p><strong>No user information available.</strong></p>--}}
                        @endif

                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right xs-text-left xs-margin-top-20">
                        @if($userShowroomInfo['user'])
                            @if($userShowroomInfo['showrooms'])
                                <p><strong>Showroom Information</strong></p>
                                @foreach($userShowroomInfo['showrooms'] as $showroom)
                                    <div class="showroom">
                                        <p>{{ $showroom['name'] }},
                                            {{ $showroom['address'] }}.
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <p><strong>This user currently does not have any showroom.</strong></p>
                            @endif
                        @endif

                    </div>
                </div>
            @endif
            <hr style="border: 1px solid;">
            <!-- Footer Section -->
            <div class="footer-section row ">
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
                                            <input type="number" class="form-control" id="wast_per" name="length_feet" placeholder="Percentage" value="10" readonly="readonly">
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
                                    <input type="hidden" value="" id="unique_block_id" name="unique_block_id">
                                    <input type="hidden" value="" id="surface_title" name="surface_title">
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
    </div>

    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof validateForm === 'function' && validateForm()) {
                document.getElementById('download_pdf').disabled = false;
                document.getElementById('download_pdf').classList.add('enabled');
            }
        });
    </script>

    <script>
        function reloadPage() {
            // After form submission, reload the page
            setTimeout(function() {
                location.reload();
            }, 1000); // 1-second delay for the form submission to complete before reloading
        }
    </script> -->

@endsection