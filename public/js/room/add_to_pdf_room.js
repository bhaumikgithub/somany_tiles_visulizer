function addToPDF(){
    'use strict';
    let current_room_id = $('#current_room_id').val();
    let current_room_name = $('#current_room_name').val();
    let current_room_type = $("#current_room_type").val();
    let selected_tiles_ids = $('#selected_tile_ids').val();

    let thumbnailData = generateAndDownloadThumbnail();
    let currentDesign = canvasImage();

    window.$.ajax({
        url: '/add-to-pdf-data-store', // Laravel route URL
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            data: {
                    'room_id':current_room_id,
                    'room_name':current_room_name,
                    'room_type':current_room_type,
                    'selected_tiles_ids':selected_tiles_ids,
                    'thumbnail':thumbnailData,
                    'currentDesign':currentDesign,
            },
        },
        success: function (response) {
            $('#dialogSaveModalBox').modal('hide');
            $('.productCount').text(response.data.all_selection);
            $('#addToCartInfoPanel').show();
            $('#addToCartInfoPanel #cartInfoTilesList').html(response.body);
        },
        error: function (xhr, status, error) {
            alert('Failed to stored!');
            console.error(error);
        },
    });

}

// Get the main canvas and its context
function generateAndDownloadThumbnail(){
    // Create an offscreen canvas for the thumbnail
    const mainCanvas = document.getElementById('roomCanvas');
    const thumbnailCanvas = document.createElement('canvas');
    const thumbnailSize = 200; // Thumbnail width
    thumbnailCanvas.width = thumbnailSize;
    thumbnailCanvas.height = thumbnailSize * (mainCanvas.height / mainCanvas.width);

    const thumbnailCtx = thumbnailCanvas.getContext('2d');
    // Scale and draw the main canvas content on the thumbnail canvas
    thumbnailCtx.drawImage(mainCanvas, 0, 0, thumbnailCanvas.width, thumbnailCanvas.height);
    return thumbnailCanvas.toDataURL('image/jpeg');
}


function canvasImage() {
    let canvas = document.getElementById('roomCanvas');
    let imageCanvas = document.createElement('canvas');
    imageCanvas.width = canvas.width;
    imageCanvas.height = canvas.height;
    let imageCanvasContext = imageCanvas.getContext('2d');
    imageCanvasContext.drawImage(canvas, 0, 0, canvas.width, canvas.height);
    return imageCanvas.toDataURL('image/jpeg');
}

// Initialize an empty array
let ids = [];

function getTileId(id){
    let current_room_type = $('#current_room_type').val();
    if( current_room_type !== "kitchen"){
        ids = [];
    }
    ids.push($('li#'+id).data('tile'));
    $('#selected_tile_ids').val(JSON.stringify(ids));

}

function removeProductFromCart(id) {
    window.$.ajax({
        url: `/add-to-pdf-data/${id}`, // Endpoint for deletion
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        success: function (response) {
            alert(response.message);
            $('.productCount').text(response.data);
            // Optionally remove the deleted item from the DOM
            $(`[data-prod-id="${id}"]`).closest('div').remove();
        },
        error: function (xhr) {
            alert('Something went wrong!');
        }
    });
}

function viewCartPdf()
{
    window.$.ajax({
        url: `/add-to-pdf-data`, // Endpoint for deletion,
        type: 'GET',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        success: function (response) {
            if( response.data.emptyCart === "unfilled" ){
                alert("Please choose tiles to add in PDF");
            } else {
                $('#addToCartInfoPanel').show();
                if( response.data.all_selection > 0 )
                    $('.productCount').text(response.data.all_selection);
                $('#addToCartInfoPanel #cartInfoTilesList').html(response.body);
            }
        },
        error: function (xhr) {
            alert('Something went wrong!');
        }
    });

}

function hideCart()
{
    $('#addToCartInfoPanel').hide();
}

function clearAllItems()
{
    window.$.ajax({
        url: `/clear-items`, // Endpoint for deletion
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        success: function (response) {
            alert(response.message); // Display success message
            // Optionally update the UI (e.g., empty the cart display)
            $('.productCount').text('');
            $('#addToCartInfoPanel #cartInfoTilesList').html(''); // Assuming cart items are listed in #cart-items
        },
        error: function (xhr) {
            alert('Something went wrong!');
        }
    });
}

// Function to check if all fields are filled
function validateForm() {
    let allFilled = true;
    $('.form-container input').each(function () {
        if ($(this).val().trim() === '') {
            allFilled = false;
            return false; // Exit the loop if any field is empty
        }
    });

    return allFilled;
}

// Enable or disable button based on form validation
$('.form-container input').on('keyup', function () {
    if (validateForm()) {
        $('#download_pdf').prop('disabled', false).addClass('enabled');
    } else {
        $('#download_pdf').prop('disabled', true).removeClass('enabled');
    }
});

$(function(){
    $('.hover_2').on("mouseover", function () {
        $('.hover_1').removeClass('first_width');
        $('.hover_2').removeClass('first_width');
        $(this).addClass('first_width');
        // setTimeout(function(){
        //   $(this).addClass('first_width');
        // },5000);

    });
});
$(function(){
    $('.mobile_hover_2').on("click", function () {
        $('.mobile_hover_1').removeClass('mobile_first_width');
        $('.mobile_hover_2').removeClass('mobile_first_width');
        $(this).addClass('mobile_first_width');
    });
});

//tile calc modal open
// Open modal and populate the fields with data attributes
$('#tilecal').on('show.bs.modal', function (event) {

});


$("#price").on("input", function(evt) {
    var self = $(this);
    self.val(self.val().replace(/[^0-9.]/g, ''));
    if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
    {
        evt.preventDefault();
    }
});

// Open modal and populate the fields with data attributes
$('#updateprice').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let tileId = button.data('tile-id'); // Extract tile ID
    let modal = $(this);
    const priceLabelText = $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"]').find('.price_lbl').text();
    const priceLabelText1 = priceLabelText.replace(/Rs\.|\/sq\.ft/g, '').trim();
    const priceInput = modal.find('input.set_price'); // Assuming there's an input field with class 'price_input'
    if (priceLabelText.trim() == 'Price not given') {
        priceInput.val(0);
    } else {
        priceInput.val(priceLabelText1);
    }
    modal.find('#tile_id').val(tileId); // Set the tile ID in the modal input
});


// Submit the form via AJAX
$('#submit_btn').on('click', function(e) {
    if(validationCheck()===false)
        return false;
    let tileId = $('#tile_id').val();
    let price = $('#price').val();

    $.ajax({
        url: '/update-tile-price', // URL to the controller method for updating the price
        type: 'POST',
        data: {
            tile_id: tileId,
            price: price,
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
        },
        success: function(response) {
            // On success, update the price in the table
            let row = $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"]');
            row.find('.price-update').text(parseFloat(price).toFixed(2)); // Update price in the table cell
            $('#updateprice').modal('hide');
            $('.modal-backdrop').remove();  // Remove the backdrop manually
            $('body').removeClass('modal-open');  // Remove the 'modal-open' class from body
        },
        error: function(xhr) {
            // When the response has errors, this block will be executed
            let response = xhr.responseJSON;  // Get the JSON response
            if (response.errors && response.errors.price) {
                // Show the error message for 'price'
                $('#price-error').text(response.errors.price[0]);  // Assuming you have a span or div with id="price-error"
            }
        }
    });
});