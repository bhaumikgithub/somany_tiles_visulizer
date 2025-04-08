<table class="table">
    <tbody>
        @if(isset($topFiveTiles))
            @foreach($topFiveTiles as $aTopTiles)
                <tr>
                    <td>
                        <div class="d-flex">
                            <img class="img-lg mb-md-0 mr-2" src="{{$aTopTiles['photo']}}" alt="profile image" style="border-radius: 10px;">
                            <div>
                                <div class="font-weight-bold ">{{$aTopTiles['name']}}</div>
                                <div class="mt-1">{{str_replace(" MM","",$aTopTiles['size'])}}</div>
                                <div class="mt-1">{{ucfirst($aTopTiles['finish'])}}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        Times
                        <div class="font-weight-bold  mt-1">{{$aTopTiles['used_count']}} </div>
                    </td>
                    <td>
                        Percentage
                        <div class="font-weight-bold text-success  mt-1">{{$aTopTiles['percentage']}}</div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="3">No Top tiles Found</td></tr>
        @endif
    </tbody>
</table>