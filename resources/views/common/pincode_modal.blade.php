@if(session('show_pincode_modal') && !session()->has('pincode'))
    <script>
        $('#pincode').modal('show');
    </script>
@endif

<!-- Pin code modal start -->
<div class="modal fade" id="pincode" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Please enter your Pincode Number</h4>
            </div>
            <div class="modal-body">
                <form id="pincodeForm">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 cmn-form-data">
                            <div class="row">
                                <div class="col-sm-9 col-xs-9">
                                    <div class="form-group">
                                        <label for="pin_code">Please enter your Pincode Number</label>
                                        <input type="text" class="form-control pin_code" id="pin_code" name="pin_code"
                                               placeholder="Enter Pincode" minlength="6" maxlength="6" pattern="\d{6}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="btn-div d-flex flex-wrap ">
                                <button type="submit" class="btn btn-danger modify-btn tile-cal-btn mt-0"
                                        id="pincode_submit_btn">Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>