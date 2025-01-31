@php use App\Helpers\Helper; @endphp
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
        </tr>
    </table>

    <div class="row mt-4">
        <div class="col-sm-8">
            <h3>Your Product(s) Selection</h3>
            <p><span>{{\Carbon\Carbon::now()->format('d F Y')}}</span></p>
            <p>Selection Code: <span>{{$randomKey}}</span></p>
            <p><span>{{$basic_info['first_name']. " ". $basic_info['last_name']}}</span></p>
            <p>{{$basic_info['contact_no']}}</span></p>
            <p>{{$basic_info['state']}},{{$basic_info['city']}}</span></p>
            <p>Total Selection: <span>{{$allProduct->count()}}</span></p>
        </div>
    </div>

    <div style='page-break-after:always'></div>

    <div class="row mt-4">
        @if( isset($allProduct))
            @foreach($allProduct as $index=>$item)
                    <div>
                        <h4 style="font-size: 16px; margin-bottom: 10px;">Selection {{$index+1}} of {{$allProduct->count()}}</h4>
                            <?php $showImage = $item->show_main_image ;?>
                        @if( $showImage === "yes")
                            <img src="{{ public_path('storage/'.$item->current_room_design) }}" alt="Room" style="display: block; width: 640px; height: 320px; margin-bottom: 20px;">
                        @endif
                        <table style="width: 100%; border-collapse: collapse;margin-bottom: 20px;">
                            @php
                                $tiles = collect(json_decode($item->tiles_json));
                                // Check if the first item has the surface_title key
                                $tilesData = $tiles->isNotEmpty() && isset($tiles->first()->surface_title)
                                    ? $tiles->sortBy('surface_title')->values()
                                    : $tiles;
                            @endphp
                            @foreach($tilesData as $tile_detail)
                                @if( $tile_detail->surface !== "paint")
                                    <tr style="border: 1px solid #000;">
                                    <td style="width: 20%; border: 1px solid #000; text-align: center; padding: 10px;">
                                        <img src="{{ public_path($tile_detail->icon) }}" alt="Wall A" style="width: 100%; max-width: 100px; height: auto;">
                                    </td>
                                    <td style="width: 60%; border: 1px solid #000; padding: 10px;">
                                        <h5 style="margin: 5px 0; font-size: 14px;font-weight: bold">
                                            @if( isset($tile_detail->surface_title ) )
                                                {{ucfirst($tile_detail->surface_title)}}
                                            @else
                                                {{ucfirst($tile_detail->surface)}}
                                            @endif
                                        </h5>
                                        <p style="margin: 5px 0; font-size: 12px;">{{$tile_detail->name}}</p>
                                        <p style="margin: 5px 0; font-size: 12px;">{{$tile_detail->width}} × {{$tile_detail->height}} MM</p>
                                        <p style="margin: 5px 0; font-size: 12px;">{{ucfirst($tile_detail->finish)}}</p>
{{--                                        <p style="margin: 5px 0; font-size: 12px;">Sap Code: 12312321312</p>--}}
                                    </td>
                                    <td style="width: 20%; border: 1px solid #000; padding: 10px;">
                                        @if( isset($tile_detail->total_area_sq_meter) )
                                            <p style="margin: 5px 0; font-size: 12px;">Total Area: {{@$tile_detail->total_area_sq_meter}} ft </p>
                                        @endif

                                        @if( isset($tile_detail->total_area) )
                                            <p style="margin: 5px 0; font-size: 12px;">Total Area: {{@$tile_detail->total_area}} ft </p>
                                        @endif

                                        @if( isset($tile_detail->wastage) )
                                            <p style="margin: 5px 0; font-size: 12px;">Wastage: {{@$tile_detail->wastage}} % </p>
                                        @endif

                                        @if( isset($tile_detail->tiles_needed) )
                                            <p style="margin: 5px 0; font-size: 12px;">Tiles Needed: {{@$tile_detail->tiles_needed}}</p>
                                        @endif

                                        <?php $tiles_par_box = Helper::getTilesParCarton($tile_detail->id);?>
                                        @if( $tiles_par_box !== NULL )
                                            <p style="margin: 5px 0; font-size: 12px;">Number of Box Required: {{@$tile_detail->box_needed}}</p>
                                            <p style="margin: 5px 0; font-size: 12px;">Tiles in 1 Box: <span class="tiles_in_box">{{$tiles_par_box}}</span></p>
                                        @endif
                                        <p style="margin: 5px 0; font-size: 12px; color: red;">
                                                <?php $getPrice = Helper::getTilePrice($tile_detail->id,$item->id); ?>
                                            @if($getPrice === NULL )
                                                Price not given
                                            @else
                                                Rs. <span class="price-update">{{$getPrice}}</span>/sq.ft
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                @if(!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
                @endforeach
        @endif
    </div>

    <div style='page-break-after:always'></div>

    <div class="row mt-4">
        <div style="font-size: 18px;font-weight: bold;margin-bottom: 10px;">Summary Table:</div>
        @if(isset($groupedTiles))
            <div class="row summary-page-table-row">
                <table style=" width: 100%;border-collapse: collapse;margin-top: 20px;">
                    <thead>
                    <tr>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Sr. No</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Name</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Size</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Finish</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Apply<br>On</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Area<br>Sq. Ft.</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Tiles/Box</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Box Coverage<br>Area Sq. Ft.</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Box<br> Required</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">MRP/<br>Sq. Ft.</th>
                        <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">MRP<br>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($groupedTiles))
                        @php $totalArea = 0;
                            $totalTilesPerBox = 0;
                            $totalBoxCoverageAreaSqFt = 0;
                            $totalBoxRequired = 0;
                            $totalMrpPerSqFt = 0;
                            $totalMrpPrice = 0;
                            $i = 1;
                        @endphp
                        @foreach($groupedTiles as $index => $tile)
                            @if( $tile['apply_on'] !== "paint")
                                <tr>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $i }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $tile['name'] }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $tile['size'] }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ ucfirst($tile['finish']) }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ ucwords($tile['apply_on']) }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ ( $tile['area_sq_ft'] === "-" ) ? "-" : number_format($tile['area_sq_ft'])  }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $tile['tiles_per_box'] }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ ( $tile['box_coverage_area_sq_ft'] === "-" ) ? "-" : number_format($tile['box_coverage_area_sq_ft'])  }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $tile['box_required'] }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $tile['mrp_per_sq_ft'] }}</td>
                                    <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ ( $tile['mrp_price'] === "-" ) ? "-" : number_format($tile['mrp_price'])  }}</td>
                                </tr>
                            @endif
                            @php
                                $totalArea += (int)$tile['area_sq_ft'];
                                $totalTilesPerBox += (int)$tile['tiles_per_box'];
                                $totalBoxCoverageAreaSqFt += (int)$tile['box_coverage_area_sq_ft'];
                                $totalBoxRequired += (int)$tile['box_required'];
                                $totalMrpPerSqFt += (int)$tile['mrp_per_sq_ft'];
                                $totalMrpPrice += (int)$tile['mrp_price'];
                                $i++;
                            @endphp
                        @endforeach
                        <tr class="table-active footer-table-text">
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;"></th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;"><b>Total</b></th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;"></th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;"></th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;"></th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">{{ ( $totalArea === 0 ) ? "" : number_format($totalArea) }}</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;"></th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">{{ ( $totalBoxCoverageAreaSqFt === 0 ) ? "" : number_format($totalBoxCoverageAreaSqFt) }}</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">{{ ( $totalBoxRequired === 0 ) ? "" : $totalBoxRequired }}</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">{{ ( $totalMrpPerSqFt === 0 ) ? "" : number_format($totalMrpPerSqFt) }}</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">{{ ( $totalMrpPrice === 0 ) ? "" : "Rs. ". number_format($totalMrpPrice) }}</th>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div style='page-break-after:always'></div>
    <div class="mt-4">
        <h3>Disclaimer:</h3>
        <ul class="notes_ul">
            <li>The visuals are for reference purposes only; actual colors, finishes, and tile dimensions may vary.</li>
            <li>Shade variation is an inherent characteristic of tiles; therefore, physical inspection is
                recommended for accurate selection</li>
            <li>Tiles with multiple faces feature varied patterns, resulting in natural design variations</li>
            <li>Prices quoted are subject to change without prior notice. The final price applicable at the time of
                delivery will prevail.</li>
        </ul>
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

    <br>

    <div style="page-break-inside: avoid;width: 100%; max-width: 800px; margin: 0 auto; font-family: Arial, sans-serif; border: 1px solid #000; padding: 10px;">
        <div style="margin-bottom: 20px;">
            <p style="margin: 5px 0; font-size: 14px;"><strong>Toll Free Number:</strong> <a href="tel:1800-1030-004" class="tile-cal-link font-bold">1800-1030-004</a></p>
            <p style="margin: 5px 0; font-size: 14px;">09:30 am to 6:30 pm</p>
            <p style="margin: 5px 0; font-size: 14px;">Monday to Saturday</p>
        </div>
        <div style="margin-bottom: 20px;">
            <p style="margin: 5px 0; font-size: 14px;"><strong>Email Tile Enquiries:</strong> <a href="mailto:customer.care@somanyceramics.com">customer.care@somanyceramics.com</a></p>
            <p style="margin: 5px 0; font-size: 14px;"><strong>International Business Enquiries:</strong> <a href="mailto:export@somanyceramics.com">export@somanyceramics.com</a></p>
        </div>
    </div>
</div>
</body>
</html>