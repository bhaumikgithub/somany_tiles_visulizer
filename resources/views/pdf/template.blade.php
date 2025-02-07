@php use App\Helpers\Helper; @endphp
        <!DOCTYPE html>
<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Somany Tiles Visualizer | PDF</title>
    <style>
        body{
            /*font-family: "Lato", serif;*/
            font-family: "Roboto", serif;
        }
        * {
            margin: 0;
            padding: 0;
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
            border: 1px solid #b7bab2;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .details-card img {

            border-radius: 8px;
            min-width: 100px;
            min-height: 100px;
            width:130px;
            height:130px;
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
        .normalText{
            font-family: 'Roboto', sans-serif;
            width:100%;
            font-size:14px;
            line-height:0px;
            margin-top:-1px;
            font-weight:normal;
            color: #413f41;
        }
        .section-block{
            border:1px solid #908d86;
            border-radius:10px;
            padding:10px;
            margin-top:20px;
            width:100%;
            display: "inline-block";
            color:  #413f41;

        }
        .pageTitleBadge{
            font-family: 'Roboto', sans-serif;
            padding:25px 5px 5px 5px;
            background-color: #bdd4cf;
            font-size: 20px;
            color: #022e48;
        }
        .pageBackground{
            background-image: url('./img/pdf_back.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            width:100%;
            height:100%;
            background-position:100% 100%;
            padding:0px 30px 30px 30px;
            font-family: 'Roboto', sans-serif;
            position: relative;

        }

        @media print {
            tr.new-page {
                page-break-before: always;
                margin-top: 70px; /* Adds margin to the top of the row when printed */
            }
        }


    </style>
</head>
<body style="position:relative;">
<div >
    <!-- Header Section -->

    <div>
        <div class="pageBackground">
            <div class="pageTitleBadge" style="width:35%;">
                <span>YOUR PRODUCT(s) SELECTION</span>
                <!-- <img src="./img/YOUR PRODUCT(S) SELECTION.png" alt="YOUR PRODUCT(S) SELECTION" > -->
            </div>
            <div class="section-block" style="width:50%;">
                <p style="font-size:14px;line-height:14px;">Date: <span>{{\Carbon\Carbon::now()->format('d F Y')}}</span></p>
                <p style="font-size:14px;line-height:14px;">Selection Code: <b><span>{{$randomKey}}</span></b></p>
                <p style="margin-bottom: 0px;font-size:14px;line-height:14px;"><span><b>{{$basic_info['first_name']. " ". $basic_info['last_name']}}</b></span></p>
                <p style="margin-bottom:0px;font-size:14px;line-height:-14px;">{{$basic_info['contact_no']}}</span></p>
                <p style="margin-bottom:0px;font-size:14px;line-height:-14px;">{{$basic_info['state']}}</p>
                <p style="margin-bottom:0px;font-size:14px;line-height:-14px;">{{$basic_info['city']}}</p>

                @if( isset($basic_info['pin_code']))
                    <p style="margin-bottom:0px;font-size:14px;line-height:-14px;">Pincode: {{$basic_info['pin_code']}}</p>
                @endif
                <p style="font-weight:bold">Total Selection: <span>{{$allProduct->count()}}</span></p>
                @if($userShowroomInfo['user'])
            </div>

            <!-- Footer Section -->
            <div style="page-break-inside: avoid; width:100%; display:flex;">
                <!--<div class="section-block" style="width:50%; ">
                        <h2 style="font-size: 16px;">Showroom Executive Details</h2>
                        <div class="normalText">{{ $userShowroomInfo['user']['name'] }}</div>
                        @if($userShowroomInfo['user']['contact_no'])
                    <div  class="normalText">{{ $userShowroomInfo['user']['contact_no'] }}</div>
                        @endif
                @if($userShowroomInfo['user']['email'])
                    <div class="normalText" >{{ $userShowroomInfo['user']['email'] }}</div>
                        @endif
                </div>
                <div class="section-block" style="width:48%; margin-left:10px;">
                    <h2 style="font-size: 16px">Showroom Details</h2>
@foreach($userShowroomInfo['showrooms'] as $showroom)
                    <Div class="normalText" style="text-align:right;">{{ $showroom['name'] }}</div>
                            <div class="normalText" style="text-align:right;">{{ $showroom['address'] }}</div>
                        @endforeach
                </div>-->

                <table style="width:100%;">
                    <tr>
                        <td style="width:50%; height:300px; vertical-align: bottom;">
                            <div>
                                <p class="normalText" style="font-size: 16px; border:none; font-weight:bold;">Showroom Executive Details</p>
                                <p class="normalText" style="border:none;">{{ $userShowroomInfo['user']['name'] }}</p>
                                @if($userShowroomInfo['user']['contact_no'])
                                    <p  class="normalText">{{ $userShowroomInfo['user']['contact_no'] }}</p>
                                @endif
                                @if($userShowroomInfo['user']['email'])
                                    <p class="normalText" >{{ $userShowroomInfo['user']['email'] }}</p>
                                @endif
                            </div>
                        </td>
                        <td style="width:50%; height:300px; vertical-align: bottom;">
                            <div>
                                <p class="normalText" style="font-size: 16px; border:none; font-weight:bold;">Showroom Details</p>
                                @foreach($userShowroomInfo['showrooms'] as $showroom)
                                    <p class="normalText">{{ $showroom['name'] }}</p>
                                    <p class="normalText">{{ $showroom['address'] }}</p>
                                    <p class="normalText"></p>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                </table>

            </div>
            @endif
        </div>
    </div>
</div>
<div>
    <!-- 3 page -->
    <div class="pageBackground" style="min-height:100vh;">

        @if( isset($allProduct))
            @foreach($allProduct as $index=>$item)
                <div class="pageTitleBadge" style="width:25%;">
                    <span> SELECTION {{$index+1}} OF {{$allProduct->count()}} </span>
                </div>
                <div style="width:100%;">
                        <?php $showImage = $item->show_main_image; ?>
                    @if($showImage === "yes")
                        <table style="font-family: 'Roboto', sans-serif;vertical-align-top; width:100%; margin-top:20px;border-collapse: collapse;">
                            <tr>
                                <td style="width: 40%;vertical-align:top;">
                                    <img src="{{ public_path('storage/'.$item->current_room_design) }}" alt="Room" style="display: block; width: 600px; height: 338px; margin-bottom: 20px;">
                                </td>
                                <td style="width: 2%"></td>
                                <td style="width: 58%; vertical-align: top;">
                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;" cellpadding="2" cellspacing="2">
                                        @php
                                            $tiles = collect(json_decode($item->tiles_json));
                                            $tilesData = $tiles->isNotEmpty() && isset($tiles->first()->surface_title)
                                                ? $tiles->sortBy('surface_title')->values()
                                                : $tiles;
                                        @endphp

                                        @foreach($tilesData as $index => $tile_detail)
                                            @if($tile_detail->surface !== "paint")
                                                @if($index < 3)  <!-- Show only first 3 rows on the first page -->
                                                <tr>
                                                    <td colspan="2" style="padding: {{ $index == 0 ? '0px 5px 5px 0px' : '25px 5px 5px 0px' }}; margin-bottom:15px;">
                                                        <h5 style="font-size: 18px; font-weight: bold; color:#3e3e40;">
                                                            @if(isset($tile_detail->surface_title))
                                                                <span >{{ ucfirst($tile_detail->surface_title) }} - {{$tile_detail->name}}</span>
                                                            @else
                                                                <span >{{ ucfirst($tile_detail->surface) }} - {{$tile_detail->name}}</span>
                                                            @endif
                                                        </h5>
                                                    </td>
                                                </tr>

                                                <!-- Tile Image and Details Row -->
                                                <tr style="background:#ffffff; margin-bottom:10px;border:1px solid #d4c19b;border-top:none !important;">
                                                    <td style="width: 50%; text-align: left; padding: 10px;vertical-align:top;">
                                                        <img src="{{ public_path($tile_detail->icon) }}" alt="Tile Icon" style="width:130px;height:130px; border: 1px solid #cccccc;">
                                                    </td>
                                                    <td style="width: 50%; padding: 10px; vertical-align:top;">
                                                        <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">{{$tile_detail->width}} × {{$tile_detail->height}} MM</p>
                                                        <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">{{ ucfirst($tile_detail->finish) }}</p>
                                                            <?php $sku = Helper::getSAPCode($tile_detail->id); ?>
                                                        @if($sku !== null)
                                                            <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">SAP Code: {{$sku}}</p>
                                                        @endif
                                                        @if(isset($tile_detail->total_area_sq_meter))
                                                            <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Total Area: {{@$tile_detail->total_area_sq_meter}} ft²</p>
                                                        @endif
                                                        @if(isset($tile_detail->total_area))
                                                            <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Total Area: {{@$tile_detail->total_area}} ft²</p>
                                                        @endif
                                                        @if(isset($tile_detail->wastage))
                                                            <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Wastage: {{@$tile_detail->wastage}} %</p>
                                                        @endif
                                                        @if(isset($tile_detail->tiles_needed))
                                                            <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Tiles Needed: {{@$tile_detail->tiles_needed}}</p>
                                                        @endif
                                                            <?php $tiles_par_box = Helper::getTilesParCarton($tile_detail->id); ?>
                                                        @if(isset($tile_detail->box_needed))
                                                            <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Number of Boxes Required: {{@$tile_detail->box_needed}}</p>
                                                        @endif
                                                        @if($tiles_par_box !== NULL)
                                                            <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Tiles in 1 Box: <span class="tiles_in_box">{{$tiles_par_box}}</span></p>
                                                        @endif
                                                            <?php $getPrice = Helper::getTilePrice($tile_detail->id,$item->id); ?>
                                                        @if($getPrice !== NULL)
                                                            <p style="margin: 7px 0; font-size: 14px; color: red;">
                                                                Rs. <span class="price-update" style="font-size: 14px;color:#3e3e40;">{{$getPrice}}</span>/sq.ft
                                                            </p>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- End First Page -->
                        @if(count($tilesData) > 3)  <!-- Check if there are more than 3 tiles -->

                        <!-- Page Break for Second Page -->
                        <div style="page-break-after: always;"></div>

                        <!-- New Page: Show Remaining Tiles -->

                        <table style="font-family: 'Roboto', sans-serif; vertical-align: top; width: 100%;">
                            <tr>
                                @php
                                    $columnCount = 0;
                                @endphp

                                @foreach($tilesData as $index => $tile_detail)
                                    @if($tile_detail->surface !== "paint")
                                        @if($index >= 3)  <!-- Skip first two rows for second and subsequent pages -->
                                        @if($columnCount % 2 == 0 && $columnCount != 0)
                            </tr><tr> <!-- Start new row every 2 columns -->
                                @endif

                                <td style="width: 50%; vertical-align: top; padding-right: 5px; box-sizing: border-box; ">
                                    <table style="width: 100%;   border-collapse: collapse; margin-bottom: 20px;">
                                        <tr style="height: 100%;">
                                            <td colspan="2" style="padding: 25px 5px 5px 10px; margin-bottom:15px;">
                                                <h5 style="font-size: 16px; font-weight: bold; color:#3e3e40;">
                                                    @if(isset($tile_detail->surface_title))
                                                        <span >{{ ucfirst($tile_detail->surface_title) }} - {{$tile_detail->name}}</span>
                                                    @else
                                                        <span >{{ ucfirst($tile_detail->surface) }} - {{$tile_detail->name}}</span>
                                                    @endif
                                                </h5>
                                            </td>
                                        </tr>

                                        <tr style=" width: 100%;height: 100%;">
                                            <td style="border-radius:10px;width: 50%; text-align: left; padding: 10px; vertical-align: top;background: #ffffff;border-top:none;">
                                                <img src="{{ public_path($tile_detail->icon) }}" alt="Tile Icon" style="width:130px;height:130px; border: 1px solid #cccccc;">
                                            </td>
                                            <td style="border-radius:10px;width: 50%; padding: 10px; vertical-align: top;background: #ffffff;border-top:none;">
                                                <p style="margin: 7px 0; color:#3e3e40; font-size:14px;">{{$tile_detail->width}} × {{$tile_detail->height}} MM</p>
                                                <p style="margin: 7px 0; color:#3e3e40; font-size:14px;">{{ ucfirst($tile_detail->finish) }}</p>
                                                    <?php $sku = Helper::getSAPCode($tile_detail->id); ?>
                                                @if($sku !== null)
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">SAP Code: {{$sku}}</p>
                                                @endif
                                                @if(isset($tile_detail->total_area_sq_meter))
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Total Area: {{$tile_detail->total_area_sq_meter}} ft²</p>
                                                @endif
                                                @if(isset($tile_detail->total_area))
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Total Area: {{@$tile_detail->total_area}} ft²</p>
                                                @endif
                                                @if(isset($tile_detail->wastage))
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Wastage: {{$tile_detail->wastage}} %</p>
                                                @endif
                                                    <?php $tiles_par_box = Helper::getTilesParCarton($tile_detail->id); ?>
                                                @if(isset($tile_detail->box_needed))
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Number of Boxes Required: {{$tile_detail->box_needed}}</p>
                                                @endif
                                                @if($tiles_par_box !== NULL)
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Tiles in 1 Box: {{$tiles_par_box}}</p>
                                                @endif
                                                    <?php $getPrice = Helper::getTilePrice($tile_detail->id,$item->id); ?>
                                                @if($getPrice !== NULL)
                                                    <p style="margin: 7px 0; font-size: 14px; color: red;">
                                                        Rs. <span class="price-update" style="font-size: 14px;color:#3e3e40;">{{$getPrice}}</span>/sq.ft
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                @php $columnCount++; @endphp
                                @endif
                                @endif
                                @endforeach

                                <!-- If there's an odd number of tiles, add an empty cell to balance the row -->
                                @if($columnCount % 2 != 0)
                                    <td style="width: 50%; vertical-align: top; padding: 10px; box-sizing: border-box; height: 100%; display: inline-block;"></td>
                                @endif
                            </tr>
                        </table>

                        @endif

                    @else
                        <table style="font-family: 'Roboto', sans-serif; vertical-align: top; width: 100%;  margin-top:20px;">
                            <tr>
                                @php
                                    $tiles = collect(json_decode($item->tiles_json));
                                    $tilesData = $tiles->isNotEmpty() && isset($tiles->first()->surface_title)
                                        ? $tiles->sortBy('surface_title')->values()
                                        : $tiles;
                                    $columnCount = 0;
                                @endphp

                                @foreach($tilesData as $tile_detail)
                                    @if($tile_detail->surface !== "paint")
                                        @if($columnCount % 2 == 0 && $columnCount != 0)
                            </tr><tr> <!-- Start new row every 2 columns -->
                                @endif

                                <td style="width: 50%; vertical-align: top; padding-right: 5px; box-sizing: border-box; ">
                                    <table style="width: 100%;  border-collapse: collapse; margin-bottom: 20px;">
                                        <!-- Title Row -->
                                        <tr style="height: 100%;">
                                            <td colspan="2" style="padding: 10px;">
                                                <h5 style="font-size: 16px; font-weight: bold; color:#3e3e40;">
                                                    {{ ucfirst($tile_detail->surface_title ?? $tile_detail->surface) }} - {{$tile_detail->name}}
                                                </h5>
                                            </td>
                                        </tr>

                                        <!-- Tile Image and Details Row -->
                                        <tr style=" width: 100%;height: 100%;">
                                            <td style="background: #ffffff;border: 1px solid #d4c19b; border-radius:10px;width: 50%; text-align: left; padding: 10px; vertical-align: top; border-right:none;">
                                                <img src="{{ public_path($tile_detail->icon) }}" alt="Tile Icon" style="width:130px;height:130px; border: 1px solid #cccccc;">
                                            </td>
                                            <td style="background: #ffffff;border: 1px solid #d4c19b; border-radius:10px;width: 50%; padding: 10px; vertical-align: top;border-left:none;">
                                                <!-- <img src="./img/QR.png" alt="QR Code" style="margin-bottom: 10px;">-->
                                                <p style="margin: 7px 0; color:#3e3e40; font-size:14px;">{{$tile_detail->width}} × {{$tile_detail->height}} MM</p>
                                                <p style="margin: 7px 0; color:#3e3e40; font-size:14px;">{{ ucfirst($tile_detail->finish) }}</p>

                                                    <?php $sku = Helper::getSAPCode($tile_detail->id); ?>
                                                @if($sku !== null)
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">SAP Code: {{$sku}}</p>
                                                @endif

                                                @if(isset($tile_detail->total_area_sq_meter))
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Total Area: {{$tile_detail->total_area_sq_meter}} ft²</p>
                                                @endif
                                                @if(isset($tile_detail->total_area))
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Total Area: {{@$tile_detail->total_area}} ft²</p>
                                                @endif

                                                @if(isset($tile_detail->wastage))
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Wastage: {{$tile_detail->wastage}} %</p>
                                                @endif

                                                    <?php $tiles_par_box = Helper::getTilesParCarton($tile_detail->id); ?>


                                                @if(isset($tile_detail->box_needed))
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Number of Boxes Required: {{$tile_detail->box_needed}}</p>
                                                @endif
                                                @if($tiles_par_box !== NULL)
                                                    <p style="margin: 7px 0; color:#3e3e40;font-size:14px;">Tiles in 1 Box: {{$tiles_par_box}}</p>
                                                @endif
                                                    <?php $getPrice = Helper::getTilePrice($tile_detail->id,$item->id); ?>
                                                @if($getPrice !== NULL)
                                                    <p style="margin: 7px 0; font-size: 14px; color: red;">
                                                        Rs. <span class="price-update" style="font-size: 14px;color:#3e3e40;">{{$getPrice}}</span>/sq.ft
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                @php $columnCount++; @endphp
                                @endif
                                @endforeach

                                <!-- Check if there's an odd number of tiles and add an empty td to balance the row -->
                                @if($columnCount % 2 != 0)
                                    <td style="width: 50%; vertical-align: top; padding: 10px; box-sizing: border-box; height: 100%; display: inline-block;"></td>
                                @endif
                            </tr>
                        </table>
                    @endif
                </div>
                @if(!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
            @endforeach
        @endif
    </div>


    <div style='page-break-after:always'></div>
    <div>
        <div class="pageBackground">
            <div class="pageTitleBadge" style="width:25%;">
                <span>SUMMARY</span>
                <!-- <img src="./img/YOUR PRODUCT(S) SELECTION.png" alt="YOUR PRODUCT(S) SELECTION" > -->
            </div>
            @if(isset($groupedTiles))
                <div class="row summary-page-table-row">
                    <table style=" width: 100%;border-collapse: collapse;margin-top: 20px;font-family: 'Roboto', sans-serif; font-size:12px; color:#3a3e40;">
                        <thead>
                        <tr>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Sr. No</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Name</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Size</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Finish</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;"> <br>Area</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Area<br>Sq. Ft.</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Tiles/Box</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Box Coverage<br>Area Sq. Ft.</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">Box<br> Required</th>
                            <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #cbd3be;">MRP/<br>Box</th>
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
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: center;background-color: #eff2eb;">{{ $i }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $tile['name'] }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $tile['size'] }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ ucfirst($tile['finish']) }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ ucwords($tile['apply_on']) }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: center;background-color: #eff2eb;">{{ ( $tile['area_sq_ft'] === "-" ) ? "-" : number_format($tile['area_sq_ft'])  }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: center;background-color: #eff2eb;">{{ $tile['tiles_per_box'] }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: center;background-color: #eff2eb;">{{ ( $tile['box_coverage_area_sq_ft'] === "-" ) ? "-" : number_format($tile['box_coverage_area_sq_ft'])  }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: center;background-color: #eff2eb;">{{ $tile['box_required'] }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ $tile['mrp_per_sq_ft'] }}</td>
                                        <td style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #eff2eb;">{{ ( $tile['mrp_price'] === "-" ) ? "-" : number_format($tile['mrp_price'])  }}</td>
                                        @php
                                            $i++; // Increment only when a valid row is printed
                                        @endphp
                                    </tr>
                                @endif
                                @php
                                    $totalArea += (int)$tile['area_sq_ft'];
                                    $totalTilesPerBox += (int)$tile['tiles_per_box'];
                                    $totalBoxCoverageAreaSqFt += (int)$tile['box_coverage_area_sq_ft'];
                                    $totalBoxRequired += (int)$tile['box_required'];
                                    $totalMrpPerSqFt += (int)$tile['mrp_per_sq_ft'];
                                    $totalMrpPrice += (int)$tile['mrp_price'];
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
                                <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">
                                    <!--{{ ( $totalBoxCoverageAreaSqFt === 0 ) ? "" : number_format($totalBoxCoverageAreaSqFt) }}</th>-->
                                <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">{{ ( $totalBoxRequired === 0 ) ? "" : $totalBoxRequired }}</th>
                                <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">
                                    <!--{{ ( $totalMrpPerSqFt === 0 ) ? "" : number_format($totalMrpPerSqFt) }}-->
                                </th>
                                <th style="border: 1px solid #b7bab2;padding: 8px;text-align: left;background-color: #e5efd7;">{{ ( $totalMrpPrice === 0 ) ? "" : "Rs. ". number_format($totalMrpPrice) }}</th>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            @endif



        </div>
        <div>
            <div class="pageBackground">
                <div class="pageTitleBadge" style="width:25%;">
                    <span>DISCLAIMER </span>
                    <!-- <img src="./img/YOUR PRODUCT(S) SELECTION.png" alt="YOUR PRODUCT(S) SELECTION" > -->
                </div>

                <div class="section-block">
                    <h3 style="color:#413f41;">Exciting offers:</h3>
                    <p class="normalText">The prices listed above are the Maximum Retail Price (MRP). Visit your nearest Somany store to unlock exclusive offers and discover deals that'll make your wallet smile! </p>
                </div>
                <div  class="section-block">
                    <h3 style="color:#413f41;">Disclaimer:</h3>
                    <ul style="margin-left:10px;list-style-type: decimal;  " class="normalText">
                        <li style="margin-left:-25px;">The visuals are for reference purposes only; actual colors, finishes, and tile dimensions may vary.</li>
                        <li style="margin-left:-25px;">Shade variation is an inherent characteristic of tiles; therefore, physical inspection is recommended for accurate selection.
                        </li>
                        <li style="margin-left:-25px;">Tiles with multiple faces exhibit varied patterns, resulting in natural design variations.</li>
                        <li style="margin-left:-25px;">Prices quoted are subject to change without prior notice. The final price applicable at the time of purchase will prevail.
                        </li>
                    </ul>
                </div>
            </div>

            <!-- <div style="page-break-inside: avoid;width: 100%; max-width: 800px; margin: 0 auto; font-family: Arial, sans-serif; border: 1px solid #000; padding: 10px;">

               <div style="margin-bottom: 20px;">

                  <p style="margin: 5px 0; font-size: 14px;"><strong>Toll Free Number:</strong> <a href="tel:1800-1030-004" class="tile-cal-link font-bold">1800-1030-004</a></p>

                  <p style="margin: 5px 0; font-size: 14px;">09:30 am to 6:30 pm</p>

                  <p style="margin: 5px 0; font-size: 14px;">Monday to Saturday</p>

               </div>

               <div style="margin-bottom: 20px;">

                  <p style="margin: 5px 0; font-size: 14px;"><strong>Email Tile Enquiries:</strong> <a href="mailto:customer.care@somanyceramics.com">customer.care@somanyceramics.com</a></p>

                  <p style="margin: 5px 0; font-size: 14px;"><strong>International Business Enquiries:</strong> <a href="mailto:export@somanyceramics.com">export@somanyceramics.com</a></p>

               </div>

            </div> -->

        </div>
</body>
</html>