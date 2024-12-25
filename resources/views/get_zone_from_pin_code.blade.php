@extends('layouts.app')

@section('content')
    <script type="text/javascript" charset="utf-8" async defer>
        function onlyNumbersAllowed(evt){
            'use strict';
            let key ;
            let theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            let regex = /[\d]+/;
            if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        }

        function findZone()
        {
            'use strict';
            $('.show_result, .error_message').hide();
            let pin_code = $('#pin_code').val();
            window.$.ajax({
                url: '/pincode_zone/get_zone_by_pincode',
                method : "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "pin_code": pin_code,
                },
                success: function (result) {
                    $('.show_result').show();
                    $('.error_message').hide();
                    $('.pinCode').text(pin_code);
                    $('.area').text(result.area);
                    $('.state').text(result.state);
                    $('.zone').text(result.getZoneFromState);
                },
                error: function (request, status, error) {
                    let jsonValue = jQuery.parseJSON(request.responseText);
                    $('.error_message').show();
                    $('.error_message span').text(jsonValue.message);
                }
            });
        }

    </script>
    <div class="container">
        <div class="row">
            @include('common.alerts')
            @include('common.errors')
            <div id="maximum_image_upload_count" class="panel-body">
                <form action="#" method="POST" enctype="multipart/form-data" class="form-horizontal" id="get_zone_from_zip">
                    {{ csrf_field() }}
                    <div class="form-group required">
                        <label for="form-update-user-id" class="col-sm-3 control-label">Enter Pin Code</label>
                        <div class="col-sm-6">
                            <input type="text" name="pin_code" id="pin_code" class="form-control numbers" required onkeypress="onlyNumbersAllowed(event);">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary" onclick="findZone();">Find Zone</button>
                                <button type="button" class="btn btn-default" onclick="window.history.back()">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display: none" class="show_result">
                            <h3>Result : </h3>
                            <ul>
                                <li><b>Pin Code: </b><span class="pinCode"></span></li>
                                <li><b>Area: </b><span class="area"></span></li>
                                <li><b>State: </b><span class="state"></span></li>
                                <li><b>Zone: </b><span class="zone"></span></li>
                            </ul>
                        </div>
                        <div style="display: none" class="error_message">
                            <span style="color:red"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection