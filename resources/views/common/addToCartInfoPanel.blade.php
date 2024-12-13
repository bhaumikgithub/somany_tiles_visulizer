<div id="addToCartInfoPanel" class="top-panel" style="display: none">
    <div class="top-panel-header">@lang('All Selection ')
        <span class="productCount"></span>
        <button class="close-panel" style="float: right;cursor: pointer;" onclick="hideCart();">&times;</button>
    </div>
    <div id="cartInfoTilesList" class="top-panel-box">
        @include('common.cartPanel')
    </div>
</div>