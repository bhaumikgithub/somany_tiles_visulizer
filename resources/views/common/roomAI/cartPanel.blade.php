@isset($allProduct)
    <div class="loadCartItems">
        @foreach ($allProduct as $productInfo)
            <div class="top-panel-content-tiles-list-item top-panel-content-tiles-list-item-product"
                data-prod-id="{{ $productInfo->id }}" style="cursor: default">
                <div class="tile-list-thumbnail-image-holder tile-list-thumbnail-image-holder-product cart-summary-img-holder ">
                    <img src="{{ asset('/storage/' . $productInfo->current_room_thumbnail) }}"
                        class="tile-list-thumbnail tile-list-thumbnail-product">
                </div>
                <div class="tile-list-text">
                    <p>{{ $productInfo->room_type }}</p>
                    <p><a href="javascript:void(0);" class="cart-summary-link"
                            onclick="removeProductFromCart({{ $productInfo->id }})">Remove</a></p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="product_hr">
        <hr>
    </div>
    @if ($count > 0)
        <div class="row button-row">
            <div class="col-md-4 col-sm-4 col-xs-3 xs-text-center">
                <button class="btn btn-info clear_all_btn modify-btn reset_btn" onclick="clearAllItems();">Clear
                    All</button>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-9 text-right">
                <a href='{{ @$url }}' class="btn modify-btn  csp-btn"
                   onclick="window.location.href='{{ @$url }}';">Continue to Summary Page</a>
            </div>
        </div>
    @endif
@endisset
