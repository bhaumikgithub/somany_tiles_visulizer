<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('common.head')
    @include('js_constants.ConfigTileVisualizer')

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    @if (config('app.sub_css'))<link href="/css/{{ config('app.sub_css') }}" type="text/css" rel="stylesheet">@endIf

    @if (config('app.room_font_family'))
    <style>
    body {
        font-family: {{ config('app.room_font_family') }};
    }
    </style>
    @endif

    <!-- https://pannellum.org/ -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/js/pannellum/pannellum.css"/>
    <script type="text/javascript" src="/js/pannellum/pannellum.js"></script>
</head>
<body>
    @include('common.logo')

    @if (config('app.copyright_text') || config('app.copyright_app_developer_text'))
    <div class="copyright">
        ©
        @if (config('app.copyright_text'))
        <a href="{{ config('app.copyright_link') }}" target="blank">{{ config('app.copyright_text') }}</a>
        @endif
        @if (config('app.copyright_app_developer_text'))
        <a href="{{ config('app.copyright_app_developer_link') }}" target="blank" class="black-text">{{ config('app.copyright_app_developer_text') }}</a>
        @endif
    </div>
    @endif


    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/jquery-ui.min.js"></script>

    <div id="panorama"></div>
    <script>
        pannellum.viewer("panorama", {
            "type": "cubemap",
            "autoLoad": true,
            "autoRotate": -2.2,
            "hfov": window.innerWidth / window.innerHeight * 120,
            "pitch": -10,
            "cubeMap": [
                @for ($i = 0; $i < 6; $i++)
                    "/storage/savedrooms/{{ $room_url }}/{{ $i }}.jpg",
                @endfor
            ]
        });


        (async () => {
            var getExtraOptionsText = function (tile) {
                var htmlString = '';
                var options = window.JsConstants.config.tileExtraOptions;
                if (Array.isArray(options) && tile && tile.expProps) {
                    var expProps = JSON.parse(tile.expProps)
                    options.forEach(function (option) {
                        if (option && expProps.hasOwnProperty(option) && expProps[option]) {
                            htmlString += '<p>' + option + ': ' + expProps[option] + '</p>';
                        }
                    });
                }
                return htmlString;
            };

            var getPriceText = function (tile) {
                if (window.JsConstants.config.ProductInfo.price && tile.price) {
                    return '<p class="-price">' + 'Price'+ ': ' + tile.price + '</p>';
                }
                return '';
            };

            var getInfoText = function (tile) {
                var config = window.JsConstants.config.ProductInfo;

                var size = config.size ? '<p>' + 'Size' + ': ' + tile.width + 'mm x ' + tile.height + 'mm</p>' : '';

                var finish = (config.finish && tile.finish) ? '<p>' + 'Finish' + ': ' + tile.finish + '</p>' : '';

                var url = config.url && tile.url ? '<p><a href="' + tile.url + '" target="blank">' + 'PRODUCT_URL' + '</a></p>' : '';

                var usedColors = config.colors && tile.usedColors ? '<p>' + 'COLORS_USED' + ': ' + tile.usedColors + '</p>' : '';

                var shape = config.shape ? '<p>' + 'Shape' + ': ' + tile.shape + '</p>' : '';

                var rotoPrintSet = config.rotoPrintSet && tile.rotoPrintSetName ? '<p>' + 'Roto Print Set' + ': ' + tile.rotoPrintSetName + '</p>' : '';

                return '<div class="tile-list-text"><p class="-caption">' + tile.name + '</p>' + size + ' ' + shape + ' ' + finish + ' ' + rotoPrintSet + ' ' + getExtraOptionsText(tile) + ' ' + getPriceText(tile) + ' ' + url + ' ' + usedColors + '</div>';
            };

            var savedRoom = await (await fetch('/get/room/url/{{ $room_url }}')).json()

            var roomSettings = JSON.parse(savedRoom?.roomsettings)

            var products = roomSettings?.products
                ? roomSettings.products.join(',')
                : [...new Set(roomSettings?.surfaces?.map(surface => [surface.tileId, surface.tile2Id, ...(surface.freeDesignTiles?.map(tile => tile.id) || [])].filter(item => item)).flat())]
            if (!products?.length) return

            var tiles = await (await fetch('/get/tiles?ids='+products)).json()
            if (!tiles) return

            var productInfoPanel = document.getElementById('productInfoPanel')

            var bottomMenuRoomInfo = document.getElementById('bottomMenuRoomInfo');
            bottomMenuRoomInfo.addEventListener('click', () => {
                productInfoPanel.style.display = ''
            })

            var panorama = document.getElementById('panorama');
            panorama.addEventListener('click', () => {
                productInfoPanel.style.display = 'none'
            })

            var productInfoTilesList = (document.getElementById('productInfoTilesList'));
            var surfaces = [...new Set(tiles.map(tile => tile.surface))]

            surfaces.forEach(surface => {
                var surfaceProductInfo = document.createElement('p');
                surfaceProductInfo.className = 'top-panel-label';
                surfaceProductInfo.textContent = surface[0].toUpperCase() + surface.slice(1);
                productInfoTilesList.appendChild(surfaceProductInfo);

                tiles.filter(tile => tile.surface === surface).forEach(function (tile, index) {
                    var tileItem = document.createElement('div');
                    tileItem.className = 'top-panel-content-tiles-list-item';
                    tileItem.innerHTML = '<div class="tile-list-thumbnail-image-holder"><img src="' + tile.file + '" class="tile-list-thumbnail"></div>' + getInfoText(tile);
                    productInfoTilesList.appendChild(tileItem)
                });
            })
        })()
    </script>

    @include('common.productInfoPanel')

    <div id="bottomPanelMenu">
        <button id="bottomMenuRoomInfo" title="@lang('Room Info')">
            <img src="/img/icons/info.png" alt="">
        </button>
    </div>
</body>
</html>
