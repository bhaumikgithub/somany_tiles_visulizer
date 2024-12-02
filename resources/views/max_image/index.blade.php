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
            // if (parseFloat(key) >= 0 && parseFloat(key) <= 10000) {
            //     return true;
            // } else {
            //     alert('Enter value between 1 to 10000');
            //     return false;
            // }
        }

        function saveMaximumTiles(){
            'use strict';
            let maximum_tiles = $('#maximum_tiles').val();
            if (maximum_tiles != "" && (parseInt(maximum_tiles) > 0 && parseInt(maximum_tiles) <= 10000)) {
                $('#maximum_tiles_count_form').submit();
                return true;
            } else {
                alert('Enter the value between 1 to 10000');
                return false;
            }
        }

    </script>
    <div class="container">
        <div class="row">
            @include('common.alerts')
            @include('common.errors')
            <div id="maximum_image_upload_count" class="panel-body">
                <form action="/maximum_images/update" method="POST" enctype="multipart/form-data" class="form-horizontal" id="maximum_tiles_count_form">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{$get_max_tiles->id}}" name="company_id">
                    <div class="form-group required">
                        <label for="form-update-user-id" class="col-sm-3 control-label">Maximum Tiles allowed</label>
                        <div class="col-sm-6">
                            <input type="text" name="maximum_tiles" id="maximum_tiles" class="form-control numbers" required onkeypress="onlyNumbersAllowed(event);"
                                   value={{$get_max_tiles->maximum_tiles}} maxlength="10000">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary" onclick="saveMaximumTiles()">Save</button>
                                <button type="button" class="btn btn-default" onclick="window.history.back()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection