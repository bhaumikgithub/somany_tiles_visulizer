<div class="modal fade" id="addToCartInfoPanel" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div id="addToCartInfoPanel" >
                    <div class="top-modal-header total-product-title">@lang('Selected Rooms') <span>&#40;</span><span class="productCount"></span><span>&#41;</span>
                        <button type="button" class="close cartpanelclose" data-dismiss="modal">&times;</button>
                    </div>
                    <div id="cartInfoTilesList" class="top-panel-box product-top-panel-box">
                        @include('common.cartPanel')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>