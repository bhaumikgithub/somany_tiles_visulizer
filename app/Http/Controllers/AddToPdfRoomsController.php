<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Showroom;
use App\Models\UserPdfData;
use App\Tile;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use Illuminate\Support\Facades\Session;

class AddToPdfRoomsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sessionId = $request->session()->getId();
        $getCartId = Cart::where('user_id',$sessionId)->first();
        if( $getCartId ) {
            $allProduct = CartItem::where('cart_id', $getCartId->id)->get();
            $count = $allProduct->count();

            $url = '/pdf-summary/' . $getCartId->random_key;
            return response()->json([
                'body' => view('common.cartPanel', compact('allProduct', 'count', 'url'))->render(),
                'data' => ['emptyCart'=>'filled','all_selection' => $allProduct->count()],
                'success' => 'success']);
        } else {
            return response()->json([
                'data' => ['emptyCart'=>'unfilled'],
                'message' => "No items added in PDF",
                'success' => 'success']);
        }

    }

    public function store(Request $request): JsonResponse
    {
        // Extract the image data
        $imageData = $request->data['thumbnail'];

        // Retrieve session ID
        $sessionId = $request->session()->getId();
        // Retrieve or initialize cart from session
        //$allProduct = $request->session()->get('allProduct', []);

        // Decode base64 image
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = uniqid() . '.jpeg';

        // Save the image to the public folder (e.g., storage/thumbnails)
        if (!Storage::exists('thumbnails')) {
            Storage::makeDirectory('thumbnails');
        }

        $filePath = 'thumbnails/' . $imageName;
        Storage::disk('public')->put($filePath, base64_decode($image));


        //CanvasLargeImage Store
        $largeImageData = $request->data['currentDesign'];
        // Decode base64 image
        $image1 = str_replace('data:image/jpeg;base64,', '', $largeImageData);
        $image1 = str_replace(' ', '+', $image1);
        $imageName1 = uniqid() . '.jpeg';

        // Save the image to the public folder (e.g., storage/thumbnails)
        if (!Storage::exists('largeImages')) {
            Storage::makeDirectory('largeImages');
        }

        $filePath1 = 'largeImages/' . $imageName1;
        Storage::disk('public')->put($filePath1, base64_decode($image1));



        //Check if same session key exists or not
        $checkSessionId = Cart::where('user_id',$sessionId)->first();

        //insert data into the Cart table
        if( empty($checkSessionId) ) {
            $cart = new Cart();
            $cart->user_type = ($sessionId) ? "guest" : "logged_in";
            $cart->user_id = $sessionId;
            $cart->random_key = Str::random(8);
            $cart->save();

            $cart_id = $cart->id;
        } else {
            $cart_id = $checkSessionId->id;
        }

        if (auth()->check()) {

            $loged_user = auth()->user();

            $loged_showrooms_id = $loged_user->showroom_id ? json_decode($loged_user->showroom_id, true) : [];

            if (is_array($loged_showrooms_id) && !empty($loged_showrooms_id)) {
                // Fetch showrooms only if showroom IDs are present
                $showrooms = Showroom::whereIn('id', $loged_showrooms_id)->get();
            } else {
                // No showroom IDs assigned
                $showrooms = collect(); // Empty collection
            }

            // Prepare user and showroom JSON
            $userShowroomInfo = [
                'user' => $loged_user ? [
                    'id' => $loged_user->id,
                    'name' => $loged_user->name,
                    'email' => $loged_user->email,
                    'contact_no' => $loged_user->contact_no,
                ] : null,
                'showrooms' => $showrooms ? $showrooms->toArray() : [],
            ];

        }else{
            $userShowroomInfo = [
                'user' => null,
                'showrooms' => [],
            ];
        }
        $user_showroom_Info = json_encode($userShowroomInfo);

        // Decode the JSON string from the request
        $selectedTiles = collect(json_decode($request->data['selected_tiles_ids'], true)); // Convert to a collection

        // Fetch tiles data from the database using all tile IDs (including duplicates)
        $tileIds = $selectedTiles->pluck('tileId')->all();

        $tiles = Tile::select('id', 'name', 'width', 'height', 'surface', 'finish', 'file', 'price')
            ->whereIn('id', $tileIds)
            ->get();

        // Map surface titles to each tile (considering duplicates)
        $tilesWithSurfaceTitle = $selectedTiles->map(function ($selectedTile) use ($tiles) {
            $tile = $tiles->firstWhere('id', $selectedTile['tileId']);
            return [
                'id' => $tile->id,
                'name' => $tile->name,
                'width' => $tile->width,
                'height' => $tile->height,
                'surface' => $tile->surface,
                'surface_title' => $selectedTile['surfaceTitle'], // Directly map the surfaceTitle from the selectedTiles
                'finish' => $tile->finish,
                'file' => $tile->file,
                'price' => $tile->price,
                'icon' => $tile->icon, // Access the appended 'icon' attribute
            ];
        });

        // Save the file path to the database
        $productInfo = new CartItem();
        $productInfo->room_id = $request->data['room_id'];
        $productInfo->room_name = $request->data['room_name'];
        $productInfo->room_type = $request->data['room_type'];
        $productInfo->current_room_design = $filePath1;
        $productInfo->current_room_thumbnail = $filePath;
        $productInfo->tiles_json = $tilesWithSurfaceTitle->toJson();
        $productInfo->user_showroom_Info = $user_showroom_Info;
        $productInfo->cart_id = $cart_id;
        $productInfo->save();

        //$request->session()->put('allProduct', $productInfo);
        $getCartId = Cart::where('user_id',$sessionId)->first();
        $allProduct = CartItem::where('cart_id',$getCartId->id)->get();
        $count = $allProduct->count();
        $url = '/pdf-summary/'.$getCartId->random_key;
        return response()->json([
            'body' => view('common.cartPanel',compact('allProduct','count','url'))->render(),
            'data' => ['product_info'=> $allProduct, 'all_selection' => $count,'url'=>$url],
            'success' => 'success'
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $item = CartItem::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        $item->delete();

        return response()->json([
            'data' => 1,
            'message' => 'Item deleted successfully.']);
    }

    public function removeAllItems(): JsonResponse
    {
        // Soft delete all cart items
        CartItem::query()->delete();
        return response()->json(['message' => 'All items have been removed from the pdf.']);
    }

    public function pdfSummary(Request $request , $randomKey): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $pincode = Session::get('pincode'); // Store the pincode temporarily

        Session::flush(); // Clears all session data

        Session::put('pincode', $pincode); // Restore the pincode session
            
        // Optionally, regenerate session ID for the user
        $request->session()->regenerate();  // This generates a new session ID

        $getCartId = Cart::where('random_key',$randomKey)->first();
        $allProduct = CartItem::where('cart_id',$getCartId->id)->get();
        $firstProduct = $allProduct->first(); // This returns the first CartItem model
        if ($firstProduct && $firstProduct->user_showroom_info) {
            $userShowroomInfo = json_decode($firstProduct->user_showroom_info, true);
        } else {
            $userShowroomInfo = [
                'user' => null,
                'showrooms' => [],
            ];
        }        // Retrieve the pincode from the session
        $pincode = session('pincode', null); // Default to null if not set


        $groupedTiles = $this->getProcessedTiles($allProduct);

        $upform_data = null;
        $isReadOnly = false;

        $savedUserpdfData = UserPdfData::where('unique_id',$randomKey)->get();

        if($savedUserpdfData->isNotEmpty()){
            $isReadOnly = true;
            $upform_data = $savedUserpdfData->first();
        }

        // if($isReadOnly){
        //     $upform_data = UserPdfData::where([['unique_id',$randomKey],['name',base64_decode(request()->query('name'))]])->get()->first();
        // }

        $cc_date = $getCartId->created_at->format('d-m-y');
        return view('pdf.cart_summary',compact('allProduct','randomKey','groupedTiles','upform_data','isReadOnly','cc_date','groupedTiles','pincode','userShowroomInfo'));
    }

    /**
     * @throws PdfTypeException
     * @throws CrossReferenceException
     * @throws MpdfException
     * @throws PdfReaderException
     * @throws PdfParserException
     * @throws FilterException
     */

//    public function downlaodPdf(Request $request): \Illuminate\Http\Response
//    {
//        $getCartId = Cart::where('random_key',$request->random_key)->first();
//        $allProduct = CartItem::where('cart_id',$getCartId->id)->get();
//        $basic_info = [
//            'first_name' => $request->firstName,
//            'last_name' => $request->lastName,
//            'contact_no' => $request->mobileNumber,
//        ];
//        // Data to be passed to the PDF
////        $data = [
////            'allProduct' => $allProduct,
////            'getCartId' => $getCartId,
////            'basic_info' => $basic_info
////        ];
//
//        $html = view('pdf.template',compact('allProduct','basic_info')); // Get HTML content for the PDF
//        $pdf = PDF::loadHTML($html);
//
//        // Load a Blade view into the PDF
//        //$pdf = PDF::loadView('pdf.template', $data);
//
//        // Return the PDF for viewing in a new tab
//        $fileName = 'tiles_selection_'.$request->random_key."_".Carbon::parse(now())->format('d-m-Y').'.pdf';
//        //return $pdf->stream($fileName);
//
//        return $pdf->download($fileName);
//    }

    public function downlaodPdf(Request $request): \Illuminate\Http\Response
    {
        // Step 2: Load the second (existing) PDF
        $existingPdfPath = storage_path('app/public/Tile_Visualizer_PDF_1_4.pdf'); // Path to the existing PDF
        $existingPdfContent = file_get_contents($existingPdfPath);


        $getCartId = Cart::where('random_key',$request->random_key)->first();
        $allProduct = CartItem::where('cart_id',$getCartId->id)->get();
        $basic_info = [
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'contact_no' => $request->mobileNumber,
            'state' => $request->state,
            'city' => $request->city,
            'pin_code' => session('pincode', null)// Default to null if not set
        ];

        $firstProduct = $allProduct->first(); // This returns the first CartItem model

        if ($firstProduct && $firstProduct->user_showroom_info) {
            $userShowroomInfo = json_decode($firstProduct->user_showroom_info, true);
        } else {
            $userShowroomInfo = [
                'user' => null,
                'showrooms' => [],
            ];
        }

        $groupedTiles = $this->getProcessedTiles($allProduct);

        $randomKey = $request->random_key;

        $userAccount = auth()->check() ? auth()->user()->name : 'Guest User';

        $userPdfData = UserPdfData::where('unique_id',$request->random_key)->get();

        if($userPdfData->isEmpty()){

            $savedPdf = UserPdfData::create([
                'name' => $request->firstName . ' ' . $request->lastName,
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'mobile' => $request->mobileNumber,
                'pincode' => $request->pincode ? $request->pincode : '-',
                'user_account' => $userAccount,
                'unique_id' => $request->random_key,
                'state' => $request->state,
                'city' => $request->city
            ]);

        }

        // Generate the second PDF (dynamic content) using mPDF (in landscape mode)
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 20,
            'margin_bottom' =>20
        ]);
        $mpdf->SetHeader('');
        $mpdf->SetFooter('');


        // Disable automatic page breaks
        // $mpdf->SetAutoPageBreak(false, 0);
        $html = view('pdf.template', compact('allProduct', 'basic_info', 'userShowroomInfo','randomKey','groupedTiles')); // Pass data to the Blade view
        $mpdf->WriteHTML($html);
        $mpdf->SetDisplayMode('real', 'default');
        $pdf2Content = $mpdf->Output('', 'S');  // Save the content as a string

        // Step 3: Use FPDI to combine PDFs
        $fpdi = new Fpdi();

        // Import the pages from the uploaded PDF and fit them into landscape mode
        $pageCountExternal = $fpdi->setSourceFile(StreamReader::createByString($existingPdfContent));

        for ($i = 1; $i <= $pageCountExternal; $i++) {
            if ($i == 1 ) { // Skip page 2 and 3 for now
                $templateId = $fpdi->importPage($i);
                $size = $fpdi->getTemplateSize($templateId);
                $width = $size['width'];
                $height = $size['height'];

                // Add a new page in landscape mode
                $fpdi->addPage('L');

                // Calculate scale factor to fit content into landscape page (A4-L: 297 x 210mm)
                $landscapeWidth = 297;
                $landscapeHeight = 210;
                $scale = min($landscapeWidth / $width, $landscapeHeight / $height);

                // Center the scaled content
                $x = ($landscapeWidth - ($width * $scale)) / 2;
                $y = ($landscapeHeight - ($height * $scale)) / 2;

                // Use template with scaling and positioning to fit content into landscape mode
                $fpdi->useTemplate($templateId, $x, $y, $width * $scale, $height * $scale);
            }
        }

        // Import the pages from the dynamic PDF content
        $pageCount2 = $fpdi->setSourceFile(StreamReader::createByString($pdf2Content));
        for ($i = 1; $i <= $pageCount2; $i++) {
            $templateId = $fpdi->importPage($i);
            $fpdi->addPage('L');  // Add each page in landscape mode
            $fpdi->useTemplate($templateId);
        }

        $pageCountExternalAnotherPage = $fpdi->setSourceFile(StreamReader::createByString($existingPdfContent));

        for ($i = 1; $i <= $pageCountExternalAnotherPage; $i++) {
            if( $i !== 1 ) {
                $templateId = $fpdi->importPage($i);
                $size = $fpdi->getTemplateSize($templateId);
                $width = $size['width'];
                $height = $size['height'];

                // Add a new page in landscape mode
                $fpdi->addPage('L');

                // Calculate scale factor to fit content into landscape page (A4-L: 297 x 210mm)
                $landscapeWidth = 297;
                $landscapeHeight = 210;
                $scale = min($landscapeWidth / $width, $landscapeHeight / $height);

                // Center the scaled content
                $x = ($landscapeWidth - ($width * $scale)) / 2;
                $y = ($landscapeHeight - ($height * $scale)) / 2;

                // Use template with scaling and positioning to fit content into landscape mode
                $fpdi->useTemplate($templateId, $x, $y, $width * $scale, $height * $scale);
            }
        }


        // Step 4: Set the new title for the combined PDF
        $fpdi->SetTitle('Somany Tiles Visualizer | PDF'); // Set the title for the new combined PDF

        // Output the final merged PDF
        $fileName = 'somany_tiles_selection_' . $request->random_key . "_" . Carbon::parse(now())->format('d-m-Y') . '.pdf';
        $pdfContent = $fpdi->Output('D' , $fileName);  // Output as string //change d to s
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    protected function getProcessedTiles($allProduct)
    {
        // Initialize an empty collection to store processed data
        $tilesCollection = collect();

        foreach ($allProduct as $item) {
            // Decode the JSON data from the 'tile_json' column
            $tiles = json_decode($item->tiles_json, true);
            foreach ($tiles as $tile) {
                $tiles_per_carton = Helper::getTilesParCarton($tile['id']);
                // Check if 'total_area' exists
                if (isset($tile['total_area'])) {
                    $box_coverage_area_sq_ft = Helper::getBoxCoverageAreaSqFt($tile['id']);
                    $mrp_price = ( $box_coverage_area_sq_ft * $tile['price'] );
                    $tilesCollection->push([
                        'id' => $tile['id'],
                        'name' => $tile['name'],
                        'size' => "{$tile['width']} x {$tile['height']}",
                        'finish' => $tile['finish'],
                        'apply_on' => $tile['surface'],
                        'area_sq_ft' => (int) $tile['total_area'],
                        'tiles_per_box' => ( isset($tile['tile_in_box']) ) ? $tile['tile_in_box'] : '-',
                        'box_coverage_area_sq_ft' => $box_coverage_area_sq_ft,
                        'box_required' => ( isset($tile['box_needed']) ) ? $tile['box_needed'] : '-',
                        'mrp_per_sq_ft' => ( isset($tile['price']) ) ? $tile['price'] : 0,
                        'mrp_price' => $mrp_price
                    ]);
                } else {
                    // Push default values or skip this tile
                    $tilesCollection->push([
                        'id' => $tile['id'],
                        'name' => $tile['name'],
                        'size' => "{$tile['width']} x {$tile['height']}",
                        'finish' => $tile['finish'],
                        'apply_on' => $tile['surface'],
                        'area_sq_ft' => '-',
                        'tiles_per_box' => ( $tiles_per_carton !== null ) ? $tiles_per_carton : ( ( isset($tile['tile_in_box']) ) ? $tile['tile_in_box'] : '-' ),
                        'box_coverage_area_sq_ft' => Helper::getBoxCoverageAreaSqFt($tile['id']),
                        'box_required' => '-',
                        'mrp_per_sq_ft' => '-',
                        'mrp_price' => '-',
                    ]);
                }
            }
        }

        // Filter out items where 'apply_on' is 'paint'
        $filteredTiles = $tilesCollection->reject(function ($item) {
            return strtolower($item['apply_on']) === 'paint';
        });

        // Group by 'name' and process to combine surfaces
        return $filteredTiles->groupBy('name')->map(function ($items) {
            return array_merge($items->first(), [
                'apply_on' => $items->pluck('apply_on')->unique()->implode(', '),
                'area_sq_ft' => $items->sum(fn($item) => (float) $item['area_sq_ft']),
                'box_required' => $items->sum(fn($item) => (int) $item['box_required']),
                'mrp_per_sq_ft' => $items->sum(fn($item) => (int) $item['mrp_per_sq_ft']),
            ]);
        });
    }

    public function updateTilePrice(Request $request): JsonResponse
    {
        // Validate the incoming request
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $cartItemId = $request->input('cartItemId');
        $tileId = $request->input('tile_id');
        $newPrice = $request->input('price');

        // Fetch the cart item
        $cartItem = DB::table('cart_items')->where('id', $cartItemId)->first();

        if ($cartItem) {
            // Decode the JSON data
            $tilesData = json_decode($cartItem->tiles_json, true);

            // Update the price for the specific tile
            foreach ($tilesData as &$tile) {
                if ($tile['id'] == $tileId) {
                    $tile['price'] = $newPrice;
                    break;
                }
            }

            // Re-encode the JSON data
            $updatedTilesData = json_encode($tilesData);

            // Update the database
            DB::table('cart_items')
                ->where('id', $cartItemId)
                ->update(['tiles_json' => $updatedTilesData]);

            return response()->json(['success' => true, 'message' => 'Tile price updated successfully!' , 'price' => $newPrice]);
        }

        // Return success response
        return response()->json(['success' => false, 'message' => 'Cart item not found.']);
    }

    public function updateTileCalculation(Request $request): JsonResponse
    {
        $cartItemId = $request->input('cart_item_id');
        $tileId = $request->input('tile_id');
        $surfaceTitle = str_replace("_"," ",$request->input('surfaceName')); // Ensure you pass the surface_title from the request

        if( $request->widthInFeet !== null ) {
            $newData = [
                'total_area_sq_meter' => $request->input('totalAreaSqMeter'),
                'total_area' => $request->input('totalArea'),
                'width_in_feet' => $request->input('widthInFeet'),
                'height_in_feet' => $request->input('heightInFeet'),
                'wastage' => $request->input('wastage'),
                'tile_in_box' => $request->input('tilesIn1Box'),
                'tiles_needed' => $request->input('tilesNeeded'),
                'box_needed' => $request->input('boxNeeded'),
            ];

            // Filter out empty values from $newData
            $filteredData = array_filter($newData, function ($value) {
                return $value !== null && $value !== ''; // Keep only non-null and non-empty values
            });

            // Fetch the cart item
            $cartItem = DB::table('cart_items')->where('id', $cartItemId)->first();

            if ($cartItem) {
                // Decode the JSON data
                $tilesData = json_decode($cartItem->tiles_json, true);

                $tileExists = false;

                // Check if tile exists and update if it does
                foreach ($tilesData as &$tile) {
                    if ($tile['id'] == $tileId && $tile['surface_title'] == $surfaceTitle) {
                        $tile = array_merge($tile, $filteredData); // Update existing tile with filtered data
                        $tileExists = true;
                        break;
                    }
                }

                // If the tile doesn't exist, insert it as a new tile
                if (!$tileExists) {
                    $newTile = array_merge([
                        'id' => $tileId,
                        'surface_title' => $surfaceTitle, // Include the surface title for uniqueness
                    ], $filteredData);
                    $tilesData[] = $newTile; // Add new tile to the tiles data
                }

                // Re-encode the JSON data
                $updatedTilesData = json_encode($tilesData);
                // Update the database
                DB::table('cart_items')
                    ->where('id', $cartItemId)
                    ->update(['tiles_json' => $updatedTilesData]);

                return response()->json(['success' => true, 'message' => 'Tile data added/updated successfully!']);
            }

            return response()->json(['success' => false, 'message' => 'Cart item not found.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Date not found.']);
        }
    }


    public function updatePreference(Request $request)
    {
        $showImage = $request->input('show_image');

        // Update the database
        CartItem::where('id', $request->input('cart_item_id'))->update(['show_main_image' => $showImage]);

        return response()->json(['success' => true]);
    }

    public function checkSelectionHasData(Request $request)
    {
        $cart = Cart::where('user_id',$request->input('session_id'))->get();
        if( $cart->count() === 0 ){
            return response()->json(['success' => false, 'message' => 'No selection in Cart Found.','count'=>$cart->count()]);
        } else {
            return response()->json(['success' => true,'message' => 'selection in Cart.','count'=>$cart->count()]);
        }
    }
}
