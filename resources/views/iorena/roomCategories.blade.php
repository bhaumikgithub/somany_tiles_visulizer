<script type="text/javascript">
/*jslint browser: true */

function roomCategoriesSelect(category, categoryName) {
    'use strict';
    if (!category) return;

    document.getElementById('roomSubCategoriesTitle').innerText = categoryName;
    $('.row > .room-category').hide();
    $('.row > .room-category-' + category).show();
}
</script>

<div class="roomCategories">
    <div title="Room Menu" data-toggle="tooltip" class="roomBtn">
        <a class="btn btn-primary openContent bgDefaultBlue" data-toggle="collapse"
            href="#categoryCollapse" role="button" aria-expanded="false"
            aria-controls="categoryCollapse">
            <i class="fas fa-chevron-up"></i>
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
    <div class="collapse roomCategoryColor" id="categoryCollapse">
        <div id="main-category" class="card card-body">
            <div class="container-fluid">
                <div class="widthHead col-2 col-xl-2 col-lg-2 col-md-2 col-sm-2">
                    <h1 class="heading defaultBlue">Room Categories</h1>
                </div>
                <div class="row">
                    <?php
                    function addRoomCategory($key, $name) {
                        $room_img = [
                            'my' => '/img/iorena/category-2.png',
                            'livingroom' => '/img/iorena/category-4.png',
                            'kitchen' => '/img/iorena/category-3.png',
                            'bathroom' => '/img/iorena/category-2.png',
                            'bedroom' => '/img/iorena/category-1.png',
                            'outdoor' => '/img/iorena/category-5.png',
                            'other' => '/img/iorena/category-2.png',
                        ];

                        return '<div class="col-3 col-xl-3 col-lg-3 col-md-3 col-sm-3" onclick="roomCategoriesSelect(\'' . $key . '\', \'' . __($name) . '\')">'
                            . '<a class="overlay transition detail-category" href="javascript:">'
                            . '<span class="categoryName">' . __($name) . '</span>'
                            . '<img src="' . $room_img[$key] . '" alt="">'
                            . '</a>'
                            . '</div>';
                    }

                    $saved_rooms_present = isset($saved_rooms) && count($saved_rooms) > 0;
                    if ($saved_rooms_present) {
                        echo addRoomCategory('my', 'My rooms');
                    }

                    if (isset($rooms) && count($rooms) > 0) {
                        foreach ($rooms as $key => $sub_rooms) {
                            if ($key) {
                                if (array_key_exists($key, $room_types)) {
                                    $room_type = $room_types[$key];
                                } else {
                                    $room_type = $key;
                                }
                                echo addRoomCategory($key, $room_type);
                            } else {
                                echo addRoomCategory('other', 'Other');
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="card card-body sub-category">
            <div class="container-fluid">
                <div class="widthHead col-2 col-xl-2 col-lg-2 col-md-2 col-sm-2">
                    <h1 id="roomSubCategoriesTitle" class="heading defaultBlue mb-2">Room Categories</h1>
                    <a id="backButton" class="backToCategories bgDefaultBlue" href="javascript:" onclick="roomCategoriesSelect()">back</a>
                </div>
                <div class="row">
                    <?php
                    function addRoom($key, $url, $name, $icon, $engine) {
                        $engine_icon_img = '';
                        if (isset($engine) && !config('app.hide_engine_icon')) {
                            if ($engine == '2d' || $engine == '2d.room') {
                                $engine_icon = '/img/icons/2d.png';
                            } else if ($engine == '3d' || $engine == '3d.room') {
                                $engine_icon = '/img/icons/3d.png';
                            } else if ($engine == 'panorama' || $engine == 'panorama.room') {
                                $engine_icon = '/img/icons/panorama.png';
                            }
                            if (isset($engine_icon)) {
                                $engine_icon_img = '<img src="' . $engine_icon . '" alt="" class="room-image-engine-icon">';
                            }
                        }

                        return '<div class="col-3 col-xl-3 col-lg-3 col-md-3 col-sm-3 room-category room-category-' . $key . '">'
                            . '    <a class="overlay transition" href="' . $url . '" title="' . $name . '">'
                            . '        <span class="categoryName">' . $name . '</span>'
                            . '        <img class="w-100 room-image" src="' . $icon . '" alt="">'
                            . $engine_icon_img
                            . '    </a>'
                            . '</div>';
                    }

                    if ($saved_rooms_present) {
                        foreach ($saved_rooms as $saved_room) {
                            if ($saved_room->room) {
                                echo addRoom('my', '/room/url/' . $saved_room->urlWithParams, $saved_room->room->name, $saved_room->image, $saved_room->engine);
                            }
                        }
                    }

                    if (isset($rooms) && count($rooms) > 0) {
                        foreach ($rooms as $key => $sub_rooms) {
                            foreach ($sub_rooms as $sub_room) {
                                $room_link = '';
                                if ($view_name == '2d.room') {
                                    $room_link = '/room2d/' . $sub_room->id;
                                } else if ($view_name == '3d.room') {
                                    $room_link = '/room3d/' . $sub_room->id;
                                } else if ($view_name == 'panorama.room') {
                                    $room_link = '/panorama/' . $sub_room->id;
                                }

                                $room_category = $key ? $key : 'other';
                                echo addRoom($room_category, $room_link, $sub_room->name, $sub_room->icon, $view_name);
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
