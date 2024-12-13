@isset($allProduct)
    @foreach($allProduct as $productInfo)
        <div class="top-panel-content-tiles-list-item" data-prod-id="{{$productInfo->id}}" style="cursor: default">
            <div class="tile-list-thumbnail-image-holder">
                <img src="{{asset('/storage/'.$productInfo->current_room_thumbnail)}}" class="tile-list-thumbnail">
            </div>
            <div class="tile-list-text">
                <p>{{$productInfo->room_name}} > </p>
                <p>{{$productInfo->room_type}}</p>
                <p><a href="javascript:void(0);" onclick="removeProductFromCart({{$productInfo->id}})">Remove</a></p>
            </div>
        </div>
    @endforeach
    <hr>
    @if( $count > 0 )
        <button class="btn btn-info" style="float:left;" onclick="clearAllItems();">Clear All</button>
        <a href="#" target="_blank" class="btn btn-danger" style="float:right;" onclick="window.location.href='{{@$url}}';">Continue to Summary Page</a>
    @endif
@endisset