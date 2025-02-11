<ul>
    @if(isset($surface))
        @php
            $nameCounts = [];
            $alphabets = range('A', 'Z');
        @endphp
        @foreach($surface as $aSurface)
            @php
                // Get the current name value
                $name = $aSurface['type'];

                // Increment the count for this name
                if (isset($nameCounts[$name])) {
                    $nameCounts[$name]++;
                } else {
                    $nameCounts[$name] = 0;
                }

                // Get the corresponding alphabet suffix
                $suffix = $alphabets[$nameCounts[$name]];
                 // Construct the display name with the suffix
                $displayName = $name . ' ' . $suffix;
                $wallId = "list_".str_replace(" ","_",$displayName);
            @endphp

            <li class="slected_tile choosen_tile_updated_data" id="{{$wallId}}" style="cursor: pointer" onclick="openTileSelectionPanel('{{str_replace(" ","_",$displayName)}}');">
                <div class="tile-list-thumbnail-image-holder">
                    <img src="{{asset('/storage/no_tile.png')}}">
                </div>
                <div class="tile-list-text">
                    <p class="-caption">{{$displayName}}</p>
                    <div class="selected tile detail"></div>
                </div>
                <button class="open-panel"><span class="glyphicon-menu-right glyphicon" aria-hidden="true"></span></button>
            </li>
        @endforeach
        <li class="slected_tile choosen_tile_updated_data" id="list_theme" onclick="openTileSelectionPanel('theme');" style="cursor: pointer">
            <div class="tile-list-thumbnail-image-holder">
                <img src="{{asset('/storage/no_tile.png')}}">
            </div>
            <div class="tile-list-text">
                <p class="-caption">Theme</p>
                <div class="selected tile detail"></div>
            </div>
            <button class="open-panel"><span class="glyphicon-menu-right glyphicon" aria-hidden="true"></span></button>
        </li>
    @endif
</ul>