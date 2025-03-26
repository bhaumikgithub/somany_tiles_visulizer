
<script type="text/javascript">
/*jslint browser: true */
function showRoomsType(type) {
    'use strict';
    window.$('.rooms-list-by-type').hide();
    window.$('.rooms-types').removeClass('active');
    if (type) {
        window.$('#roomsList_' + type).show();
        window.$('#roomsType_' + type).addClass('active');
    } else {
        window.$('#userRoomsList').show();
        window.$('#userRooms').addClass('active');
    }
}
</script>

<div id="dialogRoomSelect" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h3 class="modal-title">@lang('Select Room')</h3>
            </div>

            <div class="modal-body">
                <div class="modal-body">
                    <div class="upload" style="padding-bottom: 15px;" >
                        <button id="upload-image" type="button" class="btn btn-default" style="display: inline-flex; align-items: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="rgba(0, 0, 0, 0.87)" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M208,208H48a16,16,0,0,1-16-16V80A16,16,0,0,1,48,64H80L96,40h64l16,24h32a16,16,0,0,1,16,16V192A16,16,0,0,1,208,208Z" fill="none" stroke="rgba(0, 0, 0, 0.87)" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></path><circle cx="128" cy="132" r="36" fill="none" stroke="rgba(0, 0, 0, 0.87)" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></circle></svg>
                            <span style="padding-left: 5px">Upload</span>
                        </button>
                        <p style="display: inline-block; padding-left: 10px">Upload a picture of your room</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Cancel')</button> -->
            </div>
        </div>
    </div>
</div>
