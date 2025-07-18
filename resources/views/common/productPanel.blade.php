<div id="topPanel" class="top-panel top-panel-product" style="display: none;">
    <div id="topPanelHideBtn" class="top-panel-hide-button top-panel-hide-button-product">
        <span id="topPanelHideIcon" class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
    </div>

    <div class="row">
        <div class="col-md-12 show_selected_surface_data">
            <div id="selectd-data">
                @include('common.exists_surface_area')
            </div>
            <div id="slected-panel">
                <div class="top-panel-box hideOnMobile title-area">
                    <div class="h5-wrapper display_surface_name">
                        <button class="selcte-data-btn">
                            <span class="glyphicon-menu-right glyphicon" aria-hidden="true"></span>
                        </button>
                        <h5 class="text-center panel-title" id="optionText"></h5>
                    </div>
                </div>
                <div class="withoutThemePanelWrapper">


                    <div class="row top-panel-box top-panel-box-first top-panel-box-first-btn-wrap top-panel-box-cmn-br">
                        <div class="col-md-12 col-xs-12">
                            <div class="d-flex flex-wrap w-100">
                                <button class="selcte-data-btn smallBackArrowForMobile showOnMobile">
                                    <span class="glyphicon-menu-right glyphicon" aria-hidden="true"></span>
                                </button>
                                <button id="btnProduct"
                                        class="top-panel-button">@lang('Tiles')</button>
                                <button id="btnLayout"
                                        class="top-panel-button">@lang('Layout')</button>
                                <button id="btnGrout"
                                        class="top-panel-button">@lang('Grout')</button>
                                <button id="searchIconToggle"
                                        class="top-panel-button smallBackArrowForMobile showOnMobile partOfProductTabButtons"  style="padding-left:8px;">
                                    <i class="fa-solid fa-search"></i>
                                </button>
                                <button id="sliderIconToggle" class="top-panel-button smallBackArrowForMobile showOnMobile partOfProductTabButtons" style="padding-left:8px;">
                                    <i class="fa-solid fa-sliders"></i>
                                </button>
                            </div>
                        </div>


                    </div>
                    <div class="partOfProductTabContent-wrap" id="hideForPanorama">
                        <div class="top-panel-box search-filter-panel-box partOfProductTabContent">
                            <div class="input-box d-flex flex-wrap serch-box-wrap">
                                <div class="serach-pad-set d-flex flex-wrap w-100">
                                    <div class="input-text-box-wrap">
                                        <input id="inputSearch" type="search" value="" placeholder="@lang('Search Product')"
                                               class="input-search product-input-serch"><button id="btnSearchIcon"
                                                                                                class="search-icon-button">
                                            <svg class="svg-inline--fa fa-magnifying-glass form-control-feedback" aria-hidden="true"
                                                 focusable="false" data-prefix="fas" data-icon="magnifying-glass" role="img"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor"
                                                      d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="fliter-btn-wrap hideOnMobile">
                                        <button id="btnRefine"
                                                class="top-panel-button top-panel-btn-filter">@lang('
                                            <i class="fa-solid fa-sliders"></i>
                                            <span class="filterText">Filters</span>
                                    ')</button>
                                    </div>
                                </div>


                                <div id="topPanelNavFilter" class="filterContentPanel"></div>
                                <div id="topPanelFilter"
                                     class="top-panel-box top-panel-option-box top-panel-box-overflow-y filter-top-panel filterContentPanel"
                                     style="display: none;">
                                </div>
                                <div id="topPanelSearchResult" style="display: none"></div>
                            </div>
                        </div>
                    </div>
                    <div class="radio-surface-rotation-wrap">
                        <div class="top-panel-box row radio-surface-rotation top-panel-box-cmn-br mt-0 d-flex flex-wrap partOfProductTabContent">

                            <div class="col-md-4">
                                <span class="top-panel-label rotate-font-title">@lang('Rotate'):</span>
                            </div>
                            <div class="col-md-8 text-right">
                                <input id="topPanelSurfaceRotation_0" type="radio" name="radioSurfaceRotation" value="0"
                                       checked="checked">
                                <label for="topPanelSurfaceRotation_0">0°</label>
                                <input id="topPanelSurfaceRotation_45" type="radio" name="radioSurfaceRotation"
                                       value="45">
                                <label id="topPanelSurfaceRotationLabel_45" for="topPanelSurfaceRotation_45">45°</label>
                                <input id="topPanelSurfaceRotation_90" type="radio" name="radioSurfaceRotation"
                                       value="90">
                                <label for="topPanelSurfaceRotation_90">90°</label>
                                <input id="topPanelSurfaceRotation_135" type="radio" name="radioSurfaceRotation"
                                       value="135">
                                <label id="topPanelSurfaceRotationLabel_135" for="topPanelSurfaceRotation_135">135°</label>
                                <input id="topPanelSurfaceRotation_180" type="radio" name="radioSurfaceRotation"
                                       value="180">
                                <label for="topPanelSurfaceRotation_180">180°</label>
                            </div>
                        </div>
                    </div>

                    <div id="topPanelLayout" class="top-panel-option-box top-panel-box-overflow-y partOfLayoutTabContent" style="display: none;">
                        @if ($view_name == '3d.room')
                            <div class="top-panel-box">
                                <span class="top-panel-label stiled-checkbox-text">@lang('Surface Color')</span>
                                <div id="surface-color-picker" class="top-panel-select-color" data-color="#ffffff"
                                     title="Surface Color"></div>
                            </div>
                        @endif
                        <div class="toggle-wrap-main">
                            <div id="topPanelContentFreeDesign" class="top-panel-box row mb-10 landscap-toggle1">
                                <div class="col-md-12 col-xs-12 ">
                                    <div class="d-flex flex-wrap row align-items-center">
                                        <div class="lbl-fd col-md-6 col-sm-6 col-xs-8 free-design-lbl">

                                            <label for="topPanelCheckFreeDesign"
                                                   class="top-panel-label stiled-checkbox-text ">@lang('Free Design')</label>
                                        </div>
                                        <div class="stiled-checkbox-wrap col-md-6 col-sm-6  col-xs-4 text-right free-design-toggle ">

                                            <div class="stiled-checkbox">
                                                <input type="checkbox" id="topPanelCheckFreeDesign" />
                                                <label for="topPanelCheckFreeDesign"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="topPanelCheckFreeDesignRotateBox" class="top-panel-box row mb-10 landscap-toggle2">
                                <div class="col-md-12 col-xs-12 ">
                                    <div class="d-flex flex-wrap row align-items-center ">
                                        <div class="lbl-fd col-md-6 col-sm-6 col-xs-8 rotate-lbl">

                                            <label for="topPanelCheckFreeDesign"
                                                   class="top-panel-label stiled-checkbox-text ">@lang('Rotate By Click')</label>
                                        </div>
                                        <div class="stiled-checkbox-wrap col-md-6 col-sm-6  col-xs-4 text-right rotate-toggle ">

                                            <div class="stiled-checkbox">
                                                <input type="checkbox" id="topPanelCheckFreeDesignRotate" />
                                                <label for="topPanelCheckFreeDesignRotate"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div id="layout-list" class="top-panel-box radio-surface-pattern top-panel-box-cmn-br">
                            <input id="topPanelSurfacePattern_0" type="radio" name="radioSurfacePattern"
                                   value="0">
                            <label for="topPanelSurfacePattern_0">
                                <img src="/img/square.png" alt="" class="pattern-image-icon">
                                <p>@lang('Standard')</p>
                            </label>
                            <input id="topPanelSurfacePattern_1" type="radio" name="radioSurfacePattern"
                                   value="1">
                            <label for="topPanelSurfacePattern_1">
                                <img src="/img/chess.png" alt="" class="pattern-image-icon">
                                <p>@lang('Chess')</p>
                            </label>
                            <input id="topPanelSurfacePattern_2" type="radio" name="radioSurfacePattern"
                                   value="2">
                            <label for="topPanelSurfacePattern_2">
                                <img src="/img/skew.png" alt="" class="pattern-image-icon">
                                <p>@lang('Horizontal Skew')</p>
                            </label>
                            <input id="topPanelSurfacePattern_3" type="radio" name="radioSurfacePattern"
                                   value="3">
                            <label for="topPanelSurfacePattern_3">
                                <img src="/img/skewVert.png" alt="" class="pattern-image-icon">
                                <p>@lang('Vertical Skew')</p>
                            </label>

                            <?php
                            $skew_sizes = config('app.tiles_skew_sizes');
                            $skew_count = count($skew_sizes);
                            if ($skew_count > 0 && $skew_sizes[0]) {
                                echo '<div class="radio-skew-size">';
                                for ($i = $skew_count - 1; $i >= 0; $i--) {
                                    $size = explode('=', $skew_sizes[$i]);
                                    echo "<input id=\"topPanelSurfacePatternSkewSize_$i\" type=\"radio\" name=\"radioSkewSize\" value=\"{$size[1]}\">", "<label for=\"topPanelSurfacePatternSkewSize_$i\">{$size[0]}</label>";
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="topPanelGrout-wrap">
                        <div id="topPanelGrout" class="top-panel-option-box top-panel-box-overflow-y partOfGroutTabContent" style="display: none;">
                            <div id="topPanelContentSurfaceTabGroutSizeBody" class="top-panel-box top-panel-box-cmn-br row partOfGroutTabContent">
                                <div class="col-md-12">
                                    <div class="row d-flex flex-wrap align-items-center">
                                        <div class="col-md-4 col-xs-2">
                                            <span class="top-panel-label stiled-checkbox-text">@lang('Size')</span>
                                        </div>
                                        <div class="col-md-8 col-xs-10 text-right xs-left range-slider">
                                            <span id="" class="span-width">0</span>
                                            <input id="topPanelGroutSizeRange" type="range" min="0" max="24"
                                                   value="4" class="slider">
                                            <span id="topPanelGroutSizeText" class="top-panel-label stiled-checkbox-text">4
                                        mm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="grout-list" class="top-panel-box top-panel-box-cmn-br row">
                                <!-- <span class="top-panel-label stiled-checkbox-text">@lang('Grout Color')</span> -->

                                <div class="col-md-12">
                                    <?php
                                    $grout_colors = config('app.grout_colors');
                                    if (count($grout_colors) > 0 && $grout_colors[0]) {
                                        echo '<div id="grout-predefined-color">';
                                        foreach ($grout_colors as $color) {
                                            echo "<button data-color=\"$color\" style=\"background-color: $color;\" class=\"-btn\"></button>";
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                <div class="col-md-12">
                                    <div id="grout-color-picker" class="top-panel-select-color" data-color="#ffffff"
                                         title="Grout Color"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="topPanelAccordionFilter" class="top-panel-box" style="display: none;">
                        <span class="top-panel-label">@lang('Sorting tiles'):</span>
                        <div class="accordion-filter">
                            <div class="filter-accordion accordion-menu">
                                <h3 class="accordion-header">Select...</h3>
                                <div class="filter-accordion accordion-body"></div>
                            </div>
                        </div>
                    </div>

                    {{--    @if (!config('app.hide_top_panel_sort')) --}}
                    {{--    <div class="row top-panel-box dropdown-tiles-sort top-panel-box-cmn-br mt-0 d-flex flex-wrap"> --}}
                    {{--    <div class="col-md-12"> --}}
                    {{--        <span class="top-panel-label rotate-font-title">@lang('Sort tiles'):</span> --}}
                    {{--        <select id="topPanelTilesSort" name="topPanelTilesSort" class="tile-sort-select"> --}}
                    {{--            <option value="a-z">A-Z</option> --}}
                    {{--            <option value="z-a">Z-A</option> --}}
                    {{--            <option value="newest first">@lang('Newest first')</option> --}}
                    {{--            <option value="oldest first">@lang('Oldest first')</option> --}}
                    {{--        </select> --}}
                    {{--    </div> --}}
                    {{--    </div> --}}
                    {{--    @endif --}}
                    <div class="topPanelTilesListBox-wrap">
                        <div id="topPanelTilesListBox" class="top-panel-box partOfProductTabContent">
                            <div id="loadTilesAnimationContainer">
                                <p>Loading Tiles</p>
                                <div class="circles marginLeft">
                                    <span class="circle_1 circle"></span>
                                    <span class="circle_2 circle"></span>
                                    <span class="circle_3 circle"></span>
                                </div>
                            </div>

                            <ul id="topPanelTilesListUl" class="panel_list"></ul>
                        </div>
                    </div>
                </div>

                <!-- Added for themes -->
                <div id="selected_panel_theme" class="withoutThemePanelWrapper" style="display: none;">
                    <div id="topPanelThemeListBox" class="top-panel-box">
                        <ul id="topPanelThemeListUl" class="panel_list"></ul>
                    </div>
                </div>
            </div>
        </div>
