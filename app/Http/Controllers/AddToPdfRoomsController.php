<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
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
        if (!Storage::exists('public\thumbnails')) {
            Storage::makeDirectory('public\thumbnails',0777, true);
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
        if (!Storage::exists('public\largeImages')) {
            Storage::makeDirectory('public\largeImages',0777, true);
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

        //Get tiles data
        $tiles = Tile::select('id','name','width','height','surface','finish','file','price')->whereIn('id', json_decode($request->data['selected_tiles_ids']))->get();

        // Save the file path to the database
        $productInfo = new CartItem();
        $productInfo->room_id = $request->data['room_id'];
        $productInfo->room_name = $request->data['room_name'];
        $productInfo->room_type = $request->data['room_type'];
        $productInfo->current_room_design = $filePath1;
        $productInfo->current_room_thumbnail = $filePath;
        $productInfo->tiles_json = $tiles->toJson();
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
            'success' => 'success']);
    }

    public function destroy($id): JsonResponse
    {
        $item = CartItem::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        $item->delete();

        return response()->json([
            'data' => CartItem::count(),
            'message' => 'Item deleted successfully.']);
    }

    public function removeAllItems(): JsonResponse
    {
        // Soft delete all cart items
        CartItem::query()->delete();
        return response()->json(['message' => 'All items have been removed from the pdf.']);
    }

    public function pdfSummary($randomKey): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $getCartId = Cart::where('random_key',$randomKey)->first();
        $allProduct = CartItem::where('cart_id',$getCartId->id)->get();
        return view('pdf.cart_summary',compact('allProduct','randomKey'));
    }

    public function downlaodPdf(Request $request): \Illuminate\Http\Response
    {
        $getCartId = Cart::where('random_key',$request->random_key)->first();
        $allProduct = CartItem::where('cart_id',$getCartId->id)->get();
        $basic_info = [
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'contact_no' => $request->mobileNumber,
        ];
        // Data to be passed to the PDF
//        $data = [
//            'allProduct' => $allProduct,
//            'getCartId' => $getCartId,
//            'basic_info' => $basic_info
//        ];

        $html = view('pdf.template',compact('allProduct','basic_info')); // Get HTML content for the PDF
        $pdf = PDF::loadHTML($html);

        // Load a Blade view into the PDF
        //$pdf = PDF::loadView('pdf.template', $data);

        // Return the PDF for viewing in a new tab
        $fileName = 'tiles_selection_'.$request->random_key."_".Carbon::parse(now())->format('d-m-Y').'.pdf';
        //return $pdf->stream($fileName);

        return $pdf->download($fileName);
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
        $requestData = $request->except('_token');
        dd($requestData);
    }
}
