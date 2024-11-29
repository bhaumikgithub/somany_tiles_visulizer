<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Savedroom;
use App\Panorama;
use App\Tile;
use DB;

class HomeController extends Controller

{

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('auth');
    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {


        $user_id = Auth::id();

        $saved_rooms = Savedroom::getUserSavedRooms($user_id);
        foreach ($saved_rooms as $key => $value) {
            $saved_rooms[$key] = $value;
            $saved_rooms[$key]->tile_names = '';
            $tile_name = [];
            $surfaces = json_decode($value->roomsettings);
            if (!empty($surfaces)) {
                if (!empty($surfaces->surfaces) && isset($surfaces->surfaces)) {
                    foreach ($surfaces->surfaces as $surfaces_key => $surfaces_value) {
                        if (!empty($surfaces_value->freeDesignTiles)) {
                            $free_design_ids = [];
                            foreach ($surfaces_value->freeDesignTiles as $free_design_key => $free_design_value) {
                                $free_design_ids[] = $free_design_value->id;
                            }
                            $free_tile_query = Tile::getFreeTileNameByIds(array_unique($free_design_ids));
                            foreach ($free_tile_query as $free_tile_key => $free_tile_value) {
                                $tile_name[] = $free_tile_value->name;
                            }
                        }
                        if (!empty($surfaces_value->tileId)) {
                            $tile_query = Tile::getTileNameByIds($surfaces_value->tileId);
                            if (!empty($tile_query->name)) {
                                $tile_name[] = $tile_query->name;
                            }
                        }
                    }
                } else {
                    if (!empty($surfaces->products) && isset($surfaces->products)) {
                        foreach ($surfaces->products as $surfaces_key => $surfaces_value) {
                            $tile_query = Tile::getTileNameByIds($surfaces_value);
                            if (!empty($tile_query->name)) {
                                $tile_name[] = $tile_query->name;
                            }
                        }
                    }
                }
                $tile_names = array_unique($tile_name);
                $saved_rooms[$key]->tile_names = implode(',', $tile_names);
            }
        }
        // dd($saved_rooms);
        // $saved_rooms = Savedroom::where('userid', $user_id)->where('enabled', 1)->orderBy('updated_at', 'desc')->paginate(20);

        // $saved_rooms = Savedroom::where('userid', $user_id)->orderBy('updated_at', 'desc')->paginate(20);
        return view('home', ['savedRooms' => $saved_rooms]);
    }


    public function jsonData(Request $request)
    {
        $userId = Auth::id();
        // main query
        $saved_rooms = Savedroom::getUserSavedRooms($userId);
        foreach ($saved_rooms as $key => $value) {
            $saved_rooms[$key] = $value;
            $saved_rooms[$key]->tile_names = '';
            $saved_rooms[$key]->room_names = Panorama::where('id', $value->roomid)->pluck('name')->first();
            $tile_name = [];
            $surfaces = json_decode($value->roomsettings);
            if (!empty($surfaces)) {
                if (!empty($surfaces->surfaces) && isset($surfaces->surfaces)) {
                    foreach ($surfaces->surfaces as $surfaces_key => $surfaces_value) {
                        if (!empty($surfaces_value->freeDesignTiles)) {
                            $free_design_ids = [];
                            foreach ($surfaces_value->freeDesignTiles as $free_design_key => $free_design_value) {
                                $free_design_ids[] = $free_design_value->id;
                            }
                            $free_tile_query = Tile::getFreeTileNameByIds(array_unique($free_design_ids));
                            foreach ($free_tile_query as $free_tile_key => $free_tile_value) {
                                $tile_name[] = $free_tile_value->name;
                            }
                        }
                        if (!empty($surfaces_value->tileId)) {
                            $tile_query = Tile::getTileNameByIds($surfaces_value->tileId);
                            if (!empty($tile_query->name)) {
                                $tile_name[] = $tile_query->name;
                            }
                        }
                    }
                } else {
                    if (!empty($surfaces->products) && isset($surfaces->products)) {
                        foreach ($surfaces->products as $surfaces_key => $surfaces_value) {
                            $tile_query = Tile::getTileNameByIds($surfaces_value);
                            if (!empty($tile_query->name)) {
                                $tile_name[] = $tile_query->name;
                            }
                        }
                    }
                }
                $tile_names = array_unique($tile_name);
                $saved_rooms[$key]->tile_names = implode(',', $tile_names);
            }
        }
        //end main query
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page


        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');


        $columnIndex = $columnIndex_arr[0]['column']; // Column
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $engine_2d_enabled = config('app.engine_2d_enabled');
        $engine_3d_enabled = config('app.engine_3d_enabled');
        $engine_panorama_enabled = config('app.engine_panorama_enabled');

        $engines = [];
        if (config('app.engine_2d_enabled')) {
            array_push($engines, '2d');
        }
        if (config('app.engine_3d_enabled')) {
            array_push($engines, '3d');
        }
        if (config('app.engine_panorama_enabled')) {
            array_push($engines, 'panorama');
        }


        $totalRecords = Savedroom::where('userid', $userId)->where('enabled', 1)->whereIn('engine', $engines)->orderBy('updated_at', 'desc')->count();

        //  Savedroom::getUserSavedRooms($user_id)->count();
        $records1 = [];
        $records = [];
        if (!empty($searchValue)) {
            // $search_record = DB::table('panoramas')->where('name', 'like', '%' . $searchValue . '%')->pluck('id', 'name')->toArray();
            // $value_serach = implode(",", $search_record);
            // if (!empty($value_serach)) {
            //     $records1 =  Savedroom::where('userid', $userId)->where('enabled', 1)->whereRaw('roomid IN(' . $value_serach . ')')->whereIn('engine', $engines)->orderBy('updated_at', 'desc');
            // }
            
            // *********************
            $filter_data=[];
            foreach ($saved_rooms as $key => $value) {
                if (strpos(strtolower($value->tile_names), strtolower($searchValue)) !== FALSE || strpos(strtolower($value->room_names), strtolower($searchValue)) !== FALSE) {
                    $filter_data[$key] = $value;
                }
            }
            $records1 = $filter_data;
            $record_count = count($records1);
            // *********************
        } else {
            $records1 =  Savedroom::where('userid', $userId)->where('enabled', 1)->whereIn('engine', $engines)->orderBy('updated_at', 'desc');
            $record_count = $records1->count();
        }

        if (!empty($records1)) {
            $totalRecordswithFilter =  $record_count;
        } else {
            $totalRecordswithFilter = 0;
        }

        //all records
        if ($rowperpage == -1) {
            $rowperpage = $totalRecords;
        }
        // Fetch records

        if (!empty($searchValue)) {

            // $search_record = DB::table('panoramas')->where('name', 'like', '%' . $searchValue . '%')->pluck('id', 'name')->toArray();
            // $value_serach = implode(",", $search_record);
            // if (!empty($value_serach)) {
            //     $records =  Savedroom::where('userid', $userId)->where('enabled', 1)->whereRaw('roomid IN(' . $value_serach . ')')->whereIn('engine', $engines)->orderBy('updated_at', 'desc');
            // }
            $search_data=[];
            foreach ($saved_rooms as $key => $value) {
                if (strpos(strtolower($value->tile_names), strtolower($searchValue)) !== FALSE || strpos(strtolower($value->room_names), strtolower($searchValue)) !== FALSE) {
                    $search_data[$key] = $value;
                }
            }
            $saved_rooms = $search_data;
        } else {
            $records =  Savedroom::where('userid', $userId)->where('enabled', 1)->whereIn('engine', $engines)->orderBy('updated_at', 'desc');
            $saved_rooms = $records->skip($start)
                ->take($rowperpage)
                ->get();
        }


        // if (!empty($records)) {
        //     $saved_rooms = $records->skip($start)
        //         ->take($rowperpage)
        //         ->get();
        // } else {
        //     $saved_rooms = [];
        // }

// dd($saved_rooms);
        if (!empty($saved_rooms)) {
            foreach ($saved_rooms as $key => $value) {

                $saved_rooms[$key] = $value;
                $saved_rooms[$key]->tile_names = '';
                $tile_name = [];
                $surfaces = json_decode($value->roomsettings);
                if (!empty($surfaces)) {
                    if (!empty($surfaces->surfaces) && isset($surfaces->surfaces)) {
                        foreach ($surfaces->surfaces as $surfaces_key => $surfaces_value) {
                            if (!empty($surfaces_value->freeDesignTiles)) {
                                $free_design_ids = [];
                                foreach ($surfaces_value->freeDesignTiles as $free_design_key => $free_design_value) {
                                    $free_design_ids[] = $free_design_value->id;
                                }
                                $free_tile_query = Tile::getFreeTileNameByIds(array_unique($free_design_ids));
                                foreach ($free_tile_query as $free_tile_key => $free_tile_value) {
                                    $tile_name[] = $free_tile_value->name;
                                }
                            }
                            if (!empty($surfaces_value->tileId)) {
                                $tile_query = Tile::getTileNameByIds($surfaces_value->tileId);
                                if (!empty($tile_query->name)) {
                                    $tile_name[] = $tile_query->name;
                                }
                            }
                        }
                    } else {
                        if (!empty($surfaces->products) && isset($surfaces->products)) {
                            foreach ($surfaces->products as $surfaces_key => $surfaces_value) {
                                $tile_query = Tile::getTileNameByIds($surfaces_value);
                                if (!empty($tile_query->name)) {
                                    $tile_name[] = $tile_query->name;
                                }
                            }
                        }
                    }
                    $tile_names = array_unique($tile_name);
                    $saved_rooms[$key]->tile_names = implode(',', $tile_names);
                }
            }
        }



        // dd($saved_rooms);

        $data_arr = array();
        $html = '';

        foreach ($saved_rooms as $savedRoom) {
            if ($savedRoom->room) {


                //image start
                if (isset($savedRoom->image)) {
                    $images = $savedRoom->image;
                } else {
                    $images = $savedRoom->room->iconfile;
                }
                //image end

                //url start
                $url_image = '';
                $url_image_another_text = '';
                if (!config('app.hide_engine_icon') && isset($savedRoom->engine)) {
                    if ($savedRoom->engine == '2d') {
                        $url_image = '<img src="/img/icons/2d.png" alt="" width="32">';
                    }
                    if ($savedRoom->engine == '3d') {
                        $url_image = '<img src="/img/icons/3d.png" alt="" width="32">';
                    }
                    if ($savedRoom->engine == 'panorama') {
                        $url_image = '<img src="/img/icons/panorama.png" alt="" width="32">';
                    }
                }

                if ($savedRoom->engine == 'panorama' && $savedRoom->note == 'backed') {
                    $url_image_another_text = '(backed)';
                }
                //url end


                $room_name = $savedRoom->room->name;
                $room_url = '/room/url/' . $savedRoom->urlWithParams;
                $room_image = $images;
                $url_url = '/room/url/' . $savedRoom->url;
                $url = $savedRoom->url;
                $tilename = $savedRoom->tile_names;
                $id = $savedRoom->id;


                $data_arr[] = array(
                    "room_name" => $room_name,
                    "room_url" => $room_url,
                    'room_image' => $room_image,
                    "url_url" => $url_url,
                    "url" => $url,
                    "id" => $id,
                    'tilename' => $tilename,
                    'url_image' => $url_image,
                    'url_image_another_text' => $url_image_another_text

                );


                $html .= '<tr>' . '<td class="table-text">' . $room_name . '</td>'
                    . '<td class="table-text"> <a href="' . $room_url . '"><img src="' . $room_image . '" alt="" style="max-width: 128px; max-height: 100px;"></a></td>'
                    . '<td class="table-text">
                <a href="' . $room_url . '" title="' . $url_url . '" target="_blank">' . $url_image . '' . $url . '' . $url_image_another_text . '</a></td>'
                    . '<td class="table-text">' . $tilename . '</td>'
                    . ' <td class="table-text">
                <input type="checkbox" name="" value="' . $id . '" onchange="HomePage.addCheckedSavedRoom(this.value, this.checked);">
                <button type="button" class="close" onclick="HomePage.deleteSavedRoom(' . $id . ')" title="Remove Room">&times;</button>
              </td></tr>';
            }
        }
        $html = $html;



        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            // "aaData" => $data_arr
            "aaData" => $html
        );

        return response()->json($response);
    }
}
