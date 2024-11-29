<div class="tilesCategory">
    <div id="mySidebar" class="sidebar">
        <div class="tilesCategoryRes">

                @include('common.logo')

                <h1 class="modalHTitle">Tiles Collection</h1>
        </div>
        <div class="tilesContent text-center">
            <form class="form-inline justify-content-between">
                <div class="form-group">
                    <input class="form-control" id="inputSearch" placeholder="Search">
                    <div class="search-lens-icon"><i class="fas fa-search"></i></div>
                </div>
                <a type="button" class="btn btn-primary openFilters" data-toggle="collapse" href="#topPanelFilter" role="button" aria-expanded="false" aria-controls="topPanelFilter">
                    Filters
                </a>
                <div id="topPanelSearchResult"></div>
                <div class="collapse filtersContent" id="topPanelFilter"></div>
            </form>
            <div id="topPanelTilesListBox" class="tilesPicturesMain">
                <div id="loadTilesAnimationContainer" class="cube-wrapper">
                    <div class="cube-folding">
                        <span class="leaf1"></span>
                        <span class="leaf2"></span>
                        <span class="leaf3"></span>
                        <span class="leaf4"></span>
                    </div>
                    <span class="loading" data-name="Loading">Loading Tiles</span>
                </div>

                <div id="topPanelTilesListUl" class="row padding-row"></div>

            </div>
        </div>
        <div class="groupTilesFooter">
            <h1 class="modalHTitle">Grout Selection</h1>
            <div class="groupSelectionContent">
                <div id="grout-predefined-color" class="groupSelection">
                    <div data-color="#f3ac6e" data-toggle="tooltip" title="Inca Gold" class="colourBox -btn" style="background: #f3ac6e;"></div>
                    <div data-color="#8e3f3d" data-toggle="tooltip" title="Blood Red" class="colourBox colourBox-2 -btn" style="background: #8e3f3d;"></div>
                    <div data-color="#403e4e" data-toggle="tooltip" title="Midnight Black" class="colourBox colourBox-3 -btn" style="background: #403e4e;"></div>
                    <div data-color="#7a747f" data-toggle="tooltip" title="Dark Grey" class="colourBox colourBox-4 -btn" style="background: #7a747f;"></div>
                    <div data-color="#98c09c" data-toggle="tooltip" title="Green" class="colourBox colourBox-5 -btn" style="background: #98c09c;"></div>
                    <div data-color="#9c6059" data-toggle="tooltip" title="Canyon Red" class="colourBox colourBox-6 -btn" style="background: #9c6059;"></div>
                    <div data-color="#a9e0dd" data-toggle="tooltip" title="Sky blue" class="colourBox colourBox-7 -btn" style="background: #a9e0dd;"></div>
                    <div data-color="#8f7b6b" data-toggle="tooltip" title="Sand Beige" class="colourBox colourBox-8 -btn" style="background: #8f7b6b;"></div>
                    <div data-color="#faf8c0" data-toggle="tooltip" title="Almond" class="colourBox colourBox-9 -btn" style="background: #faf8c0;"></div>
                    <div data-color="#8f7760" data-toggle="tooltip" title="Brown" class="colourBox colourBox-10 -btn" style="background: #8f7760;"></div>
                    <div data-color="#f6f9e6" data-toggle="tooltip" title="White" class="colourBox colourBox-10 -btn" style="background: #f6f9e6;"></div>
                    <div data-color="#dd908f" data-toggle="tooltip" title="Sunrise" class="colourBox colourBox-10 -btn" style="background: #dd908f;"></div>
                    <div data-color="#bbd6bb" data-toggle="tooltip" title="Sea Green" class="colourBox colourBox-10 -btn" style="background: #bbd6bb;"></div>
                    <div data-color="#eeb1a4" data-toggle="tooltip" title="Moove" class="colourBox colourBox-10 -btn" style="background: #eeb1a4;"></div>
                    <div data-color="#96a28c" data-toggle="tooltip" title="Sage" class="colourBox colourBox-10 -btn" style="background: #96a28c;"></div>
                    <div data-color="#fbdaba" data-toggle="tooltip" title="Marble Beige" class="colourBox colourBox-10 -btn" style="background: #fbdaba;"></div>
                    <div data-color="#504448" data-toggle="tooltip" title="Dark Brown" class="colourBox colourBox-10 -btn" style="background: #504448;"></div>
                    <div data-color="#27b0e7" data-toggle="tooltip" title="Exotic Blue" class="colourBox colourBox-10 -btn" style="background: #27b0e7;"></div>
                    <div data-color="#c29473" data-toggle="tooltip" title="Saltillo" class="colourBox colourBox-10 -btn" style="background: #c29473;"></div>
                    <div data-color="#aaa5ab" data-toggle="tooltip" title="Smoke Grey" class="colourBox colourBox-10 -btn" style="background: #aaa5ab;"></div>
                    <div data-color="#a59faa" data-toggle="tooltip" title="Slate Grey" class="colourBox colourBox-10 -btn" style="background: #a59faa;"></div>
                </div>
            </div>

            <h1 class="bgDefaultBlue tilesFooter text-center">Developed by <a class="text-white" href="https://www.iorena.com">iorena</a></h1>
        </div>
    </div>
    <div class="openBtn" title="Tile Menu" data-toggle="tooltip">☰</div>
</div>
