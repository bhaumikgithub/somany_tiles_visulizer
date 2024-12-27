<!-- <div id="addToCartInfoPanel" class="top-panel" style="display: none">
    <div class="top-panel-header">@lang('All Selection ')
        <span class="productCount"></span>
        <button class="close-panel" style="float: right;cursor: pointer;" onclick="hideCart();">&times;</button>
    </div>
    <div id="cartInfoTilesList" class="top-panel-box">
        @include('common.cartPanel')
    </div>
</div> -->

 <!-- update price modal start -->
 <div class="modal fade" id="addtocart" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                   <div id="addToCartInfoPanel" >
    <div class="top-modal-header">@lang('All Selection ')
        <span class="productCount"></span>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       
    </div>
    <div id="cartInfoTilesList" class="top-panel-box">
        @include('common.cartPanel')
    </div>
</div>
                </div>
            </div>
        </div>
    </div>