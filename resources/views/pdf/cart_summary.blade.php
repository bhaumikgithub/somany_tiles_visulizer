@extends('layouts.pdf')

@section('content')
    <div class="container">
        <!-- Header Section -->
        <div class="row header-section mt-20">
            <div class="col-md-12 col-xs-12 d-flex flex-wrap align-items-center">
                <div class="logo d-flex flex-column">
                    <img src="{{asset('img/somany-logo-new.jpg')}}" alt="Somany Logo">
                </div>
                <div class="intro d-flex flex-column">
                    <p>Your space reflects your personality; make it impressive. We bring to you an exclusive selection of tiles that are engineered to perfection. Explore designs that make every space memorable.
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row mt-20">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <h3 class="product-title font-bold">Your Product Selection</h3>
                <p>Date: <span>{{\Carbon\Carbon::now()->format('d-m-Y')}}</span></p>
                <p>Name: <span>customer name</span></p>
                <p>Number: <span>customer number</span></p>
                <p>Here are the products you’ve selected from our collection. Visit more on <a href="www.somany.com">www.somany.com</a></p>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <button class="btn btn-danger modify-btn" onclick="window.location.href='{{url('/')}}';">Add More or Modify Selection</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 col-sm-12 col-xs-12">
                @if( isset($allProduct))
                    @foreach($allProduct as $index=>$item)
                        <h4 class="selection-title">Selection {{$index+1}} of {{$allProduct->count()}}</h4>
                        <img src="{{ asset('storage/'.$item->current_room_design) }}" alt="Room" class="img-responsive product-image">
                        @foreach(json_decode($item->tiles_json) as $tile_detail)
                            <h5 class="mt-20 font-bold dark-grey-font">{{ucfirst($tile_detail->surface)}}</h5>
                            <div class="details-card">
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
                                        <p>Width: 10 ft</p>
                                        <p>Height: 10 ft</p>
                                        <p>Wastage: 10%</p>
                                        <p>Number of Box Required: 10</p>
                                        <p>Tiles in 1 Box: 2</p>
                                        <a href="#" class="tile-cal-link">Open Tiles Calculator</a>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12 col-pad-set text-right xs-text-left">
                                        <h5 class="font-bold dark-grey-font mt-0 mr-10 margin-bottom-5">{{$tile_detail->price === NULL ? 'Price not given' : 'Rs. '.$tile_detail->price.'/sq.ft'}}</h5>
                                        <a href="#" class="tile-cal-link mr-10 mt-0 ">Update Price</a>
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

@endsection