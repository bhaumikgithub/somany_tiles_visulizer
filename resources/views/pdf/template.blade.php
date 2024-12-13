<!DOCTYPE html>
<html>
<head>
    <title>Tiles Visualizer | PDF</title>
    <style>
        .header-section {
            padding: 20px 15px;
        }
        .selection-title {
            margin-top: 20px;
            font-weight: bold;
        }
        .product-image {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .details-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .details-card img {
            width: 100px;
            border-radius: 8px;
        }
        .details-card h5 {
            font-size: 16px;
        }
        .right-panel {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
            margin-top: 190px;
        }
        .footer-section {
            margin-top: 30px;
        }
        .modify-btn{
            background-color: #ce1f22;
            color: #fff;
            border: none;
            font-weight: bold;
            float: right;
            padding: 10px;
            margin-top: -40px;
            border-radius: 10px;
        }

        .notes_ul{
            margin-left: 30px;
        }

        .notes_ul li {
            list-style-type: disc;
        }
    </style>
</head>
<body>
    <div class="container mt-4" style="margin-top: 5px;">
        <!-- Header Section -->
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
            <tr>
                <td style="width: 40%; vertical-align: top; padding: 10px;">
                    <img src="{{ public_path('img/tiles_visu_logo.png') }}" alt="Tiles Logo" style="max-height: 80px;">
                </td>
                <td style="width: 60%; vertical-align: top; padding: 10px;">
                    <p style="margin: 0; font-size: 14px; line-height: 1.5;">
                        Your space reflects your personality; make it impressive. We bring to you an exclusive selection of tiles that are engineered to perfection. Explore designs that make every space memorable.
                    </p>
                </td>
            </tr>
        </table>

        <div class="row mt-4">
            <div class="col-sm-8">
                <h3>Your Product Selection</h3>
                <p>Date: <span>{{\Carbon\Carbon::now()->format('d-m-Y')}}</span></p>
                <p>Name: <span>{{$basic_info['first_name']. " ". $basic_info['last_name']}}</span></p>
                <p>Number: <span>{{$basic_info['contact_no']}}</span></p>
                <p>Here are the products you’ve selected from our collection. Visit more on <a href="https://tilevisualizer.com/">https://tilevisualizer.com/</a></p>
                @if( isset($allProduct))
                    @foreach($allProduct as $index=>$item)
                        <div>
                            <h4 style="font-size: 16px; margin-bottom: 10px;">Selection {{$index+1}} of {{$allProduct->count()}}</h4>
                            <img src="{{ public_path('storage/'.$item->current_room_design) }}" alt="Room" style="display: block; width: 640px; height: 320px; margin-bottom: 20px;">
                            <table style="width: 100%; border-collapse: collapse;margin-bottom: 20px;">
                                @foreach(json_decode($item->tiles_json) as $tile_detail)
                                    <tr style="border: 1px solid #000;">
                                        <td style="width: 20%; border: 1px solid #000; text-align: center; padding: 10px;">
                                            <img src="{{ public_path($tile_detail->icon) }}" alt="Wall A" style="width: 100%; max-width: 100px; height: auto;">
                                        </td>
                                        <td style="width: 60%; border: 1px solid #000; padding: 10px;">
                                            <h5 style="margin: 5px 0; font-size: 14px;">{{$tile_detail->surface}}</h5>
                                            <p style="margin: 5px 0; font-size: 12px;">{{$tile_detail->name}}</p>
                                            <p style="margin: 5px 0; font-size: 12px;">{{$tile_detail->width}} × {{$tile_detail->height}} MM</p>
                                            <p style="margin: 5px 0; font-size: 12px;">{{$tile_detail->finish}}</p>
                                            <p style="margin: 5px 0; font-size: 12px;">Sap Code: 12312321312</p>
                                        </td>
                                        <td style="width: 20%; border: 1px solid #000; padding: 10px;">
                                            <p style="margin: 5px 0; font-size: 12px;">Width: 10 ft</p>
                                            <p style="margin: 5px 0; font-size: 12px;">Height: 10 ft</p>
                                            <p style="margin: 5px 0; font-size: 12px;">Wastage: 10%</p>
                                            <p style="margin: 5px 0; font-size: 12px;">Number of Box Required: 10</p>
                                            <p style="margin: 5px 0; font-size: 12px;">Tiles in 1 Box: 2</p>
                                            <p style="margin: 5px 0; font-size: 12px; color: red;">{{$tile_detail->price === NULL ? 'Price not given' : 'Rs. '.$tile_detail->price.'/sq.ft'}}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
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
        </div>
        <hr style="border: 1px solid;">
        <!-- Footer Section -->
        <div style="page-break-inside: avoid;width: 100%; max-width: 800px; margin: 0 auto; font-family: Arial, sans-serif; border: 1px solid #000; padding: 10px;">
            <div style="margin-bottom: 20px;">
                <h2 style="font-size: 16px; margin-bottom: 8px; border-bottom: 1px solid #000; padding-bottom: 5px;">Contact Person Details</h2>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Executive Name:</strong> John Doe</p>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Executive Number:</strong> +91-9876543210</p>
            </div>
            <div style="margin-bottom: 20px;">
                <h2 style="font-size: 16px; margin-bottom: 8px; border-bottom: 1px solid #000; padding-bottom: 5px;">Showroom Information</h2>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Address:</strong> Show Room Address, Showroom State, Showroom City</p>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Pincode:</strong> Show Room Pincode</p>
            </div>
        </div>
    </div>
</body>
</html>
