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
                <div class="intro d-flex flex-column">
                    <p>Your space reflects your personality; make it impressive. We bring to you an exclusive selection
                        of tiles that are engineered to perfection. Explore designs that make every space memorable.
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row mt-20">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <h3 class="product-title font-bold">Your Product Selection</h3>
                <p>Date: <span>{{\Carbon\Carbon::now()->format('d-m-Y')}}</span></p>
                <p>Name: <span class="font-bold">customer name</span></p>
                <p>Number: <span class="font-bold">customer number</span></p>
                <p>Here are the products you’ve selected from our collection. Visit more on <a class="cmn_link"
                                                                                               href="www.somany.com">www.somany.com</a>
                </p>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <button class="btn btn-danger modify-btn" onclick="window.location.href='{{url('/')}}';">Add More or
                    Modify Selection
                </button>
            </div>
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
                                    <div class="col-md-3 col-sm-3 col-xs-12 col-pad-set xs-margin-set">
                                        <p>Width: {{Helper::mmToFeet($tile_detail->width)}} ft</p>
                                        <p>Height: {{Helper::mmToFeet($tile_detail->height)}} ft</p>
                                        <p>Wastage: 10%</p>
                                        <p>Number of Box Required: 10</p>
                                        <p>Tiles in 1 Box: 2</p>
                                        <button type="button" class="tile-cal-link" id="tile_cal" data-toggle="modal"
                                                data-target="#tilecal">Open Tiles Calculator
                                        </button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12 col-pad-set text-right xs-text-left update_price_wrapper"
                                         data-price-tile-id="{{$tile_detail->id}}">
                                        <h5 class="font-bold dark-grey-font mt-0 mr-10 margin-bottom-5 price_lbl"
                                            id="{{$index . '_' . $loop->index . '_'. 'price'}}">
                                                <?php $getPrice = Helper::getTilePrice($tile_detail->id); ?>
                                            @if($getPrice === NULL )
                                                Price not given
                                            @else
                                                Rs. <span class="price-update">{{$getPrice}}</span>/sq.ft
                                            @endif
                                        </h5>
                                        <button type="button" class="tile-cal-link mt-0 mr-10" id="update_price_btn"
                                                data-toggle="modal" data-target="#updateprice"
                                                data-tile-id="{{$tile_detail->id}}">Update Price
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
                <h5 class="font-bold">Notes:</h5>
                <ul class="notes_ul">
                    <li>Prices quoted are as per current prevailing price.</li>
                    <li>Prices are subject to change without prior notice.</li>
                    <li>Price ruling at the time of delivery will be applicable.</li>
                    <li>Billing will be done through authorized dealers only.</li>
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
    </div>

    <!-- tile_cal modal start -->
    <div class="modal fade" id="tilecal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                                            <label for="width_feet">Enter Floor/wall's Width
                                            </label>
                                            <input type="number" class="form-control" id="width_feet" name="width_feet"
                                                   placeholder="Width in Feet">
                                        </div>
                                        <div class="form-group">
                                            <label for="length_feet">Enter Floor/wall's Length/Height
                                            </label>
                                            <input type="number" class="form-control" id="length_feet"
                                                   name="length_feet" placeholder="Length/Height">
                                        </div>
                                        <div class="form-group">
                                            <label for="tiles_size">Tiles Size
                                            </label>
                                            <select class="form-control" id="tiles_size" name="tiles_size">
                                                <option value="">Select Size</option>
                                                <option value="1200x2780" data-picperbox="1">1200 x 2780 mm</option>
                                                <option value="1200x2400" data-picperbox="1">1200 x 2400 mm</option>
                                                <option value="1200x1200" data-picperbox="2">1200 x 1200 mm</option>

                                                <option value="800x3000" data-picperbox="1">800 x 3000 mm</option>
                                                <option value="800x1600" data-picperbox="2">800 x 1600 mm</option>
                                                <option value="800x1500" data-picperbox="2">800 x 1500 mm</option>
                                                <option value="800x800" data-picperbox="3">800 x 800 mm</option>

                                                <option value="600x2020" data-picperbox="2">600 x 2020 mm</option>
                                                <option value="600x1200" data-picperbox="2">600 x 1200 mm</option>
                                                <option value="600x900" data-picperbox="1">600 x 900 mm</option>
                                                <option value="600x600" data-picperbox="4">600 x 600 mm</option>

                                                <option value="300x2020" data-picperbox="3">300 x 2020 mm</option>
                                                <option value="300x1600" data-picperbox="4">300 x 1600 mm</option>
                                                <option value="300x1200" data-picperbox="3">300 x 1200 mm</option>
                                                <option value="300x600" data-picperbox="6">300 x 600 mm</option>
                                                <option value="300x450" data-picperbox="6">300 x 450 mm</option>
                                                <option value="300x300" data-picperbox="9">300 x 300 mm</option>

                                                <option value="200x1600" data-picperbox="4">200 x 1600 mm</option>
                                                <option value="195x1200" data-picperbox="6">195 x 1200 mm</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="wast_per">Wastage in Percentage

                                            </label>
                                            <input type="number" class="form-control" id="wast_per" name="length_feet"
                                                   placeholder="Percentage">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 result-main">
                                        <div id="result" class="col-12 result_clas">
                                        </div>

                                        <div class="form-label" id="area_covered_meter"></div>

                                        <div class="form-label" id="area_covered_feet"></div>


                                        <div class="form-label" id="required_tiles"></div>

                                        <div class="form-label" id="required_box"></div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="btn-div d-flex flex-wrap ">
                                    <!-- <button class="btn modify-btn tile-cal-btn" id="calculate_btn">Calculate</button>
                                    <button class="btn modify-btn ml-3 tile-cal-btn ml-10" id="reset_btn" >Reset</button> -->
                                    <a href="#" id="calculate_btn" class="btn modify-btn tile-cal-btn">Calculate</a>
                                    <a href="#" id="reset_btn" class="btn modify-btn ml-3 tile-cal-btn ml-10 reset_btn">Reset</a>
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
                    <h4 class="modal-title">Update Price</h4>
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
                                                   placeholder="Price" pattern="[0-9]+" required>
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

@endsection

