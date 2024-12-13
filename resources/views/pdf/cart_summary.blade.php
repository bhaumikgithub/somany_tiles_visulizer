@extends('layouts.pdf')

@section('content')
    <div class="container mt-4" style="margin-top: 5px;">
        <!-- Header Section -->
        <div class="row header-section align-items-center">
            <div class="col-md-4 logo">
                <img src="{{asset('img/tiles_visu_logo.png')}}" alt="Somany Logo">
            </div>
            <div class="col-md-8 text-end">
                <div class="intro">
                    <p>Your space reflects your personality; make it impressive. We bring to you an exclusive selection of tiles that are engineered to perfection. Explore designs that make every space memorable.</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row mt-4">
            <div class="col-sm-8">
                <h3>Your Product Selection</h3>
                <button class="btn btn-danger modify-btn">Add More or Modify Selection</button>
                <p>Date: <span>{{\Carbon\Carbon::now()->format('d-m-Y')}}</span></p>
                <p>Name: <span>customer name</span></p>
                <p>Number: <span>customer number</span></p>
                <p>Here are the products you’ve selected from our collection. Visit more on <a href="https://tilevisualizer.com/">https://tilevisualizer.com/</a></p>
                @if( isset($allProduct))
                    @foreach($allProduct as $index=>$item)
                        <h4 class="selection-title">Selection {{$index+1}} of {{$allProduct->count()}}</h4>
                        <img src="{{ asset('storage/'.$item->current_room_design) }}" alt="Room" class="img-responsive product-image">
                        @foreach(json_decode($item->tiles_json) as $tile_detail)
                            <div class="details-card row">
                            <div class="col-sm-2">
                                <img src="{{ asset($tile_detail->icon) }}" alt="Wall A">
                            </div>
                            <div class="col-sm-6">
                                <h5>{{$tile_detail->surface}}</h5>
                                <p class="mb-1">{{$tile_detail->name}}</p>
                                <p class="mb-1">{{$tile_detail->width}} × {{$tile_detail->height}} MM</p>
                                <p class="mb-1">{{$tile_detail->finish}}</p>
                                <p class="mb-1">Sap Code: 12312321312</p>
                                <p class="mt-3"><a href="#">Open Tiles Calculator</a></p>
                            </div>
                            <div class="col-sm-4 text-right">
                                <p>Width: 10 ft</p>
                                <p>Height: 10 ft</p>
                                <p>Wastage: 10%</p>
                                <p>Number of Box Required: 10</p>
                                <p>Tiles in 1 Box: 2</p>
                                <p class="text-danger">{{$tile_detail->price === NULL ? 'Price not given' : 'Rs. '.$tile_detail->price.'/sq.ft'}}</p>
                                <a href="#">Update Price</a>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                @endif
                <div class="mt-4">
                    <h3>Notes:</h3>
                    <ul class="notes_ul">
                        <li>Prices quoted are as per current prevailing price.</li>
                        <li>Prices are subject to change without prior notice.</li>
                        <li>Price ruling at the time of delivery will be applicable.</li>
                        <li>Billing will be done through authorized dealers only.</li>
                    </ul>
                </div>
            </div>
            <!-- Right Panel -->
            <div class="col-sm-4">
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
        <hr style="border: 1px solid;">
        <!-- Footer Section -->
        <div class="footer-section row">
            <div class="col-sm-6">
                <p><strong>Contact Person Details</strong></p>
                <p><strong>Executive Name:</strong> <span>John Doe</span></p>
                <p><strong>Executive Number:</strong> <span>+91-9876543210</span></p>
            </div>
            <div class="col-sm-6 text-right">
                <p><strong>Showroom Information</strong></p>
                <p>Show Room Address, Showroom State, Showroom City</p>
                <p>Show Room Pincode</p>
            </div>
        </div>
    </div>

@endsection