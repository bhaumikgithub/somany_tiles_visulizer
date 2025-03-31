
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

<div id="dialogRoomSelect" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            
           <div class="modal-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-center">
                        <div class="upload-room-img">
                            <h2 class="mt-0 f-bold room_data text-center ">See Products in your room</h2>
                            <img src="/img/room-product-demo.gif" alt="room-product-demo" class="img-responsive mt-2">

                        </div>
                        <div class="upload-data text-center">
                            <div class="d-flex flex-wrap align-items-center justify-content-center upload-data-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M208,208H48a16,16,0,0,1-16-16V80A16,16,0,0,1,48,64H80L96,40h64l16,24h32a16,16,0,0,1,16,16V192A16,16,0,0,1,208,208Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></path><circle cx="128" cy="132" r="36" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></circle></svg>
                             <span>Upload a picture of your room</span>
                              </div>
                              <div class="d-flex flex-wrap align-items-center justify-content-center upload-data-box">
                              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M224,177.3V78.7a8.1,8.1,0,0,0-4.1-7l-88-49.5a7.8,7.8,0,0,0-7.8,0l-88,49.5a8.1,8.1,0,0,0-4.1,7v98.6a8.1,8.1,0,0,0,4.1,7l88,49.5a7.8,7.8,0,0,0,7.8,0l88-49.5A8.1,8.1,0,0,0,224,177.3Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></path><polyline points="222.9 74.6 128.9 128 33.1 74.6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></polyline><line x1="128.9" y1="128" x2="128" y2="234.8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"></line></svg>
                             <span>Try our products in your room</span>
                              </div>
                              <button id="upload-image" type="button" class="btn btn-default upload-img-btn" style="display: inline-flex; align-items: center;">
                              <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;"><path d="M4.5 12C4.5 12.825 5.175 13.5 6 13.5C6.825 13.5 7.5 12.825 7.5 12V9H10.5C11.325 9 12 8.325 12 7.5C12 6.675 11.325 6 10.5 6H7.5V3C7.5 2.175 6.825 1.5 6 1.5C5.175 1.5 4.5 2.175 4.5 3V6H1.5C0.675 6 0 6.675 0 7.5C0 8.325 0.675 9 1.5 9H4.5V12Z" fill="currentColor"></path><circle cx="19.5" cy="21" r="4.5" fill="currentColor"></circle><path fill-rule="evenodd" clip-rule="evenodd" d="M26.745 9H31.5C33.15 9 34.5 10.35 34.5 12V30C34.5 31.65 33.15 33 31.5 33H7.5C5.85 33 4.5 31.65 4.5 30V14.58C4.95 14.835 5.445 15 6 15C7.65 15 9 13.65 9 12V10.5H10.5C12.15 10.5 13.5 9.15 13.5 7.5C13.5 6.945 13.335 6.45 13.08 6H22.68C23.52 6 24.33 6.36 24.885 6.975L26.745 9ZM12 21C12 25.14 15.36 28.5 19.5 28.5C23.64 28.5 27 25.14 27 21C27 16.86 23.64 13.5 19.5 13.5C15.36 13.5 12 16.86 12 21Z" fill="currentColor"></path></svg>
                              <span>Upload</span>
                              </button>
                        </div>
                     </div>

                     <div class="d-flex flex-wrap w-100 step-data">
                         <div class="step-data-wrap">
                                <h3 >Step 1</h3>
                                <p>Upload a photo of your room.</p>
                            </div>
                            <div class="step-data-wrap">
                                <h3>Step 2</h3>
                                <p>Try different tiles in various sizes & patterns.</p>
                            </div>
                            <div class="step-data-wrap">
                                <h3>Step 3</h3>
                                <p>Finalize the design with confidence.</p>
                            </div>
                        </div>
                    



                   
                </div>
             </div>
           

            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Cancel')</button> -->
            </div>
        </div>
    </div>
</div>
