<div class="modal fade" id="addToCartInfoPanelModalAI" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered cart-modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div id="addToCartInfoPanel" >
                    <div class="top-modal-header total-product-title">
                        <span class="product_title">@lang('Selected Rooms')</span>
                        <span class="braces">
                            <span>&#40;</span><span class="productCount"></span><span>&#41;</span>
                        </span>
                        <button type="button" class="close cartpanelclose" data-dismiss="modal">&times;</button>
                    </div>
                    <div id="cartInfoTilesListAI" class="top-panel-box product-top-panel-box">
                        @include('common.roomAI.cartPanel')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>