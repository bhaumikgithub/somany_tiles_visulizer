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