<?php

use App\Http\Controllers\PincodeController;
use App\Http\Controllers\ZipcodeController;
use App\Http\Controllers\UserPdfController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ShowroomController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

View::composer('*', function($view){
    View::share('view_name', $view->getName());
});


$engine_2d_enabled = config('app.engine_2d_enabled');
$engine_3d_enabled = config('app.engine_3d_enabled');
$engine_panorama_enabled = config('app.engine_panorama_enabled');

if ($engine_3d_enabled) {
    Route::get('/', function () { return redirect('/room3d'); });
} else if ($engine_2d_enabled) {
    Route::get('/', function () { return redirect('/room2d'); });
} else if ($engine_panorama_enabled) {
    Route::get('/', function () { return redirect('/panorama'); });
}

Route::get('/home', 'App\Http\Controllers\HomeController@index');
Route::get('/search-tiles', 'App\Http\Controllers\HomeController@search')->name('search-tiles');
Route::get('/admin', 'App\Http\Controllers\HomeController@index');

Route::get('login/{authProvider}', 'App\Http\Controllers\Auth\LoginController@redirectToProvider');
Route::get('login/{authProvider}/callback', 'App\Http\Controllers\Auth\LoginController@handleProviderCallback');


Route::get('/room/url/{url}', 'App\Http\Controllers\Controller@roomUlr');
Route::get('/room/test_url/{url}', 'App\Http\Controllers\Controller@test_roomUlr');
Route::get('/get/room/url/{url}', 'App\Http\Controllers\AjaxController@getSavedRoomByUrl');


Route::get('/get/tiles', 'App\Http\Controllers\AjaxController@getTiles');
Route::get('/get/filters', 'App\Http\Controllers\AjaxController@getFilters');


Route::get('/lang/{locale}', 'App\Http\Controllers\Controller@changeLocale');


Route::post('/userRoom/save', 'App\Http\Controllers\AjaxController@saveUserRoom');
Route::post('/userRoom/save/specular-lights', 'App\Http\Controllers\AjaxController@saveUserRoomSpecularLights');
Route::group(['middleware' => 'role:guest'], function () {
    Route::post('/home/rooms/delete', 'App\Http\Controllers\Controller@userRoomsDelete');
});


if ($engine_2d_enabled) {
//    Route::get('/room2d', 'App\Http\Controllers\Controller2d@roomDefault');
    Route::middleware(['check.pincode'])->group(function () {
        Route::get('/room2d', 'App\Http\Controllers\Controller2d@index');
        Route::get('/listing/{roomType}', 'App\Http\Controllers\Controller2d@roomListing');
        Route::get('/room2d/{id}', 'App\Http\Controllers\Controller2d@room');
        Route::get('/get/room2d/{id}', 'App\Http\Controllers\Controller2d@getRoom');
    });

    Route::get('/check-pincode', function() {
        return response()->json([
            'pincode_saved' => session()->has('pincode')
        ]);
    });

    // Route to save the pincode
    Route::post('/save-pincode', [PincodeController::class, 'store'])->name('save-pincode');
    Route::get('/room2d/{id}', 'App\Http\Controllers\Controller2d@room');
    Route::get('/get/room2d/{id}', 'App\Http\Controllers\Controller2d@getRoom');
    Route::get('/listing/{roomType}', 'App\Http\Controllers\Controller2d@roomListing');
    Route::post('/get_room_surface','App\Http\Controllers\Controller2d@getRoomSurface');
}

if ($engine_panorama_enabled) {
    Route::get('/panorama', 'App\Http\Controllers\ControllerPanorama@roomDefault');
    Route::get('/panorama/{id}', 'App\Http\Controllers\ControllerPanorama@room');
    Route::get('/get/panorama/{id}', 'App\Http\Controllers\ControllerPanorama@getRoom');
}

if ($engine_3d_enabled) {
    Route::get('/room3d', 'App\Http\Controllers\Controller3d@roomDefault');
    Route::get('/room3d/{id}', 'App\Http\Controllers\Controller3d@room');
    Route::get('/get/room/{id}', 'App\Http\Controllers\Controller3d@getRoom');
}

$engine_room_planner_enabled = config('app.engine_room_planner_enabled');
if ($engine_room_planner_enabled) {
    Route::get('/room-planner', 'App\Http\Controllers\ControllerRoomPlanner@roomDefault');
}

$engine_blueprint3d_enabled = config('app.engine_blueprint3d_enabled');
if ($engine_blueprint3d_enabled) {
    Route::get('/blueprint3d', 'App\Http\Controllers\ControllerBlueprint3d@roomDefault');
}



Route::group(['middleware' => 'role:registered'], function () {
    Route::get('/profile', 'App\Http\Controllers\Controller@profile');
    Route::post('/profile/update/name', 'App\Http\Controllers\Controller@userUpdateName');
    Route::post('/profile/update/avatar', 'App\Http\Controllers\Controller@userUpdateAvatar');
    Route::post('/profile/update/password', 'App\Http\Controllers\Controller@userUpdatePassword');
});


Route::group(['middleware' => 'role:editor'], function () {
    Route::get('/get/tile/{id}', 'App\Http\Controllers\AjaxController@getTile');
    Route::get('/get/filter/{id}', 'App\Http\Controllers\AjaxController@getFilter');

    Route::get('/tiles', 'App\Http\Controllers\Controller@tiles');
    Route::post('/tiles', 'App\Http\Controllers\Controller@tilesFilter');
    Route::post('/tiles/upload', 'App\Http\Controllers\Controller@tilesUpload');
    Route::post('/tiles/upload/confirm', 'App\Http\Controllers\Controller@tilesUploadConfirm');
    Route::post('/tile/update', 'App\Http\Controllers\Controller@+');
    Route::post('/tiles/delete', 'App\Http\Controllers\Controller@tilesDelete');
    Route::post('/tiles/enable', 'App\Http\Controllers\Controller@tilesEnable');
    Route::post('/tiles/disable', 'App\Http\Controllers\Controller@tilesDisable');
    // Route::post('/tiles/copy', 'App\Http\Controllers\Controller@tilesCopy');
    Route::post('/tiles/batch', 'App\Http\Controllers\Controller@tilesBatch');

    Route::get('/filters', 'App\Http\Controllers\Controller@filters');
    Route::post('/filter/add', 'App\Http\Controllers\Controller@filterAdd');
    Route::post('/filter/update', 'App\Http\Controllers\Controller@filterUpdate');
    Route::post('/filters/delete', 'App\Http\Controllers\Controller@filtersDelete');
    Route::post('/filters/enable', 'App\Http\Controllers\Controller@filtersEnable');
    Route::post('/filters/disable', 'App\Http\Controllers\Controller@filtersDisable');

    if (config('app.engine_2d_enabled')) {
        Route::get('/rooms2d', 'App\Http\Controllers\Controller2d@rooms');
        Route::post('/room2d/add', 'App\Http\Controllers\Controller2d@roomAdd');
        Route::post('/room2d/update', 'App\Http\Controllers\Controller2d@roomUpdate');
        Route::post('/rooms2d/delete', 'App\Http\Controllers\Controller2d@roomsDelete');
        Route::post('/rooms2d/enable', 'App\Http\Controllers\Controller2d@roomsEnable');
        Route::post('/rooms2d/disable', 'App\Http\Controllers\Controller2d@roomsDisable');

        Route::get('/room2d/{id}/surfaces', 'App\Http\Controllers\Controller2d@roomSurfaces');
        Route::post('/room2d/surfaces/update', 'App\Http\Controllers\Controller2d@roomSurfacesUpdate');

        Route::post('/add-to-pdf-data','App\Http\Controllers\AddToPdfRoomsController@addToPdf');
        Route::post('/room2d/clear-theme', 'App\Http\Controllers\Controller2d@clearTheme');
    }

    if (config('app.engine_panorama_enabled')) {
        Route::get('/panoramas', 'App\Http\Controllers\ControllerPanorama@rooms');
        Route::post('/panorama/add', 'App\Http\Controllers\ControllerPanorama@roomAdd');
        Route::post('/panorama/update', 'App\Http\Controllers\ControllerPanorama@roomUpdate');
        Route::post('/panoramas/delete', 'App\Http\Controllers\ControllerPanorama@roomsDelete');
        Route::post('/panoramas/enable', 'App\Http\Controllers\ControllerPanorama@roomsEnable');
        Route::post('/panoramas/disable', 'App\Http\Controllers\ControllerPanorama@roomsDisable');

//        Route::get('/panorama/{id}/surfaces', 'App\Http\Controllers\ControllerPanorama@roomSurfaces');
//        Route::post('/panorama/surfaces/update', 'App\Http\Controllers\ControllerPanorama@roomSurfacesUpdate');
    }

    if (config('app.engine_3d_enabled')) {
        Route::get('/rooms', 'App\Http\Controllers\Controller3d@rooms');
        Route::post('/room/add', 'App\Http\Controllers\Controller3d@roomAdd');
        Route::post('/room/update', 'App\Http\Controllers\Controller3d@roomUpdate');
        Route::post('/rooms/delete', 'App\Http\Controllers\Controller3d@roomsDelete');
        Route::post('/rooms/enable', 'App\Http\Controllers\Controller3d@roomsEnable');
        Route::post('/rooms/disable', 'App\Http\Controllers\Controller3d@roomsDisable');
    }

    if (config('app.use_product_category')) {
        Route::get('/get/category/{id}', 'App\Http\Controllers\ControllerCategory@getById');
        Route::get('/categories', 'App\Http\Controllers\ControllerCategory@categories');
        Route::post('/category/add', 'App\Http\Controllers\ControllerCategory@add');
        Route::post('/category/update', 'App\Http\Controllers\ControllerCategory@update');
        Route::post('/categories/delete', 'App\Http\Controllers\ControllerCategory@delete');
        Route::post('/categories/enable', 'App\Http\Controllers\ControllerCategory@enable');
        Route::post('/categories/disable', 'App\Http\Controllers\ControllerCategory@disable');
    }
});



Route::group(['middleware' => 'role:administrator'], function () {
    Route::get('/get/user/{id}', 'App\Http\Controllers\AjaxController@getUser');

    Route::get('/users', 'App\Http\Controllers\Controller@users');
    Route::post('/user/update', 'App\Http\Controllers\Controller@userUpdate');
    Route::post('/user/reset/password', 'App\Http\Controllers\Controller@userResetPassword');
    Route::delete('/user/delete/{id}', 'App\Http\Controllers\Controller@userDelete');
    Route::post('/users/delete', 'App\Http\Controllers\Controller@usersDelete');
    Route::post('/users/enable', 'App\Http\Controllers\Controller@usersEnable');
    Route::post('/users/disable', 'App\Http\Controllers\Controller@usersDisable');

    Route::get('/appsettings', 'App\Http\Controllers\AjaxController@appSettings');
    Route::post('/appsettings/update', 'App\Http\Controllers\AjaxController@appSettingsUpdateLogo');

    Route::get('/get/surfacetype/{id}', 'App\Http\Controllers\AjaxController@getSurfaceType');
    Route::get('/get/surfacetypes', 'App\Http\Controllers\AjaxController@getSurfaceTypes');
    Route::get('/surfacetypes', 'App\Http\Controllers\Controller@surfaceTypes');
    Route::post('/surfacetype/add', 'App\Http\Controllers\Controller@surfaceTypeAdd');
    Route::post('/surfacetype/update', 'App\Http\Controllers\Controller@surfaceTypeUpdate');
    Route::post('/surfacetypes/delete', 'App\Http\Controllers\Controller@surfaceTypesDelete');
    Route::post('/surfacetypes/enable', 'App\Http\Controllers\Controller@surfaceTypesEnable');
    Route::post('/surfacetypes/disable', 'App\Http\Controllers\Controller@surfaceTypesDisable');

    Route::get('/get/roomtype/{id}', 'App\Http\Controllers\AjaxController@getRoomType');
    Route::get('/roomtypes', 'App\Http\Controllers\Controller@roomTypes');
    Route::post('/roomtype/add', 'App\Http\Controllers\Controller@roomTypeAdd');
    Route::post('/roomtype/update', 'App\Http\Controllers\Controller@roomTypeUpdate');
    Route::post('/roomtypes/delete', 'App\Http\Controllers\Controller@roomTypesDelete');
    Route::post('/roomtypes/enable', 'App\Http\Controllers\Controller@roomTypesEnable');
    Route::post('/roomtypes/disable', 'App\Http\Controllers\Controller@roomTypesDisable');

    Route::get('/storage-link', 'App\Http\Controllers\ControllerSystem@storageLink');

    Route::get('/fetch_tiles', 'App\Http\Controllers\FetchTilesController@index')->name('fetch_tiles');
    Route::get('/maximum_images', 'App\Http\Controllers\MaxImageController@index');

    Route::get('/pincode_zone', 'App\Http\Controllers\ZipcodeController@index')->name('pincode_zone');
    Route::post('/pincode_zone/get_zone_by_pincode', 'App\Http\Controllers\ZipcodeController@getZoneByPincode');


    Route::post('/maximum_images/update', 'App\Http\Controllers\MaxImageController@update');
    Route::post('/fetch-data', 'App\Http\Controllers\FetchTilesController@fetchData')->name('fetch.data');


    Route::resource('fetch_showroom', ShowroomController::class);
    Route::post('/showrooms/enable', [ShowroomController::class, 'showroomsEnable']);
    Route::post('/showrooms/disable', [ShowroomController::class, 'showroomsdisable']);
    Route::post('/showrooms/delete', [ShowroomController::class, 'showroomsDelete']);
    Route::get('/user_pdf_list', [UserPdfController::class , 'viewUserPdfList'])->name(name: 'user_pdf-summary'); //yash changes
    Route::get('/filter_pdf_list', [UserPdfController::class , 'filteredPdfList'])->name(name: 'filter_pdf_list'); //yash changes
});



if (config('app.tiles_designer')) {
    Route::get('/tilesdesigner/blanktiles', 'App\Http\Controllers\ControllerCustomTile@getBlankTiles');
    Route::get('/customtiles', 'App\Http\Controllers\ControllerCustomTile@getUserTiles');
    Route::get('/customtile/remove/{id}', 'App\Http\Controllers\ControllerCustomTile@remove');
    Route::post('/customtile/save', 'App\Http\Controllers\ControllerCustomTile@save');
    Route::post('/customtile/save-suggestion', 'App\Http\Controllers\ControllerCustomTile@saveSuggestion');
    Route::get('/get/room-custom-tiles', 'App\Http\Controllers\ControllerCustomTile@getTilesById');
}


if (config('app.api_user_rooms')) {
    Route::get('/user/external-link', 'App\Http\Controllers\ControllerExternalUser@fromLink'); // TODO use different config option

    Route::get('/api/user/rooms', 'App\Http\Controllers\ControllerSavedRoom@getUserRooms');
}



Route::get('json-data','App\Http\Controllers\HomeController@jsonData')->middleware(['auth'])->name('json.data');

Route::get('/add-to-pdf-data', 'App\Http\Controllers\AddToPdfRoomsController@index')->name('add-to-pdf-data.index');
Route::get('/pdf-summary/{randomKey}','App\Http\Controllers\AddToPdfRoomsController@pdfSummary');
Route::post('/generate-pdf', 'App\Http\Controllers\AddToPdfRoomsController@downlaodPdf')->name('generate-pdf');
Route::post('/add-to-pdf-data-store','App\Http\Controllers\AddToPdfRoomsController@store');
Route::post('/pdf-summary','App\Http\Controllers\AddToPdfRoomsController@pdf-summary');
Route::delete('/add-to-pdf-data/{id}', 'App\Http\Controllers\AddToPdfRoomsController@destroy')->name('add-to-pdf-data.destroy');
Route::delete('/clear-items', 'App\Http\Controllers\AddToPdfRoomsController@removeAllItems')->name('add-to-pdf-data.remove-all-items');
Route::post('/update-tile-price','App\Http\Controllers\AddToPdfRoomsController@updateTilePrice');
Route::post('/update-tile-calc','App\Http\Controllers\AddToPdfRoomsController@updateTileCalculation');
Route::post('/update-preference', 'App\Http\Controllers\AddToPdfRoomsController@updatePreference')->name('update-preference');


Route::post('check-selection-has-data','App\Http\Controllers\AddToPdfRoomsController@checkSelectionHasData');
Route::post('/get-tile-summary', 'App\Http\Controllers\AddToPdfRoomsController@getTileSummary');
// Route::get('/test', 'App\Http\Controllers\HomeController@index');
// Route::get('/test', function () {
//     return response($_SERVER['SERVER_NAME']);
// });
