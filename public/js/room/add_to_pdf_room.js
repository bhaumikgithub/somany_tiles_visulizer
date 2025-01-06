function addToPDF(){
    'ue strict';
    let current_room_id = $('#current_room_id').val();
    let current_room_name = $('#current_room_name').val();
    let current_room_type = $("#current_room_type").val();
    let selected_tiles_ids = $('#selected_tile_ids').val();
    let thumbnailData = generateAndDownloadThumbnail();
    let currentDesign = canvasImage();

    if( selected_tiles_ids.length == 0 ) {
        alert("Please select any tiles first");
    } else {
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
                $('#addToCartInfoPanel').modal('show');
                $('#addToCartInfoPanel #cartInfoTilesList').html(response.body);
                $("body").css('overflow', "hidden");
                // Update the href attribute of the link in the modal
                $('#continue-modal a#cart_url').attr('href', data.url);
            },
            error: function (xhr, status, error) {
                alert('Failed to stored!');
                console.error(error);
            },
        });
    }
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

function viewCartPdf() {
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
                $("body").css('overflow', "hidden");
                $('#addToCartInfoPanel').css('overflow', "hidden");
                $('#addToCartInfoPanel').modal('show');
              
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

function hideCart() {
    $('#addToCartInfoPanel').modal('hide');
}

function clearAllItems() {
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
          ;
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
    let cartItemId = button.data('cart-item-id'); // Extract tile ID
    let modal = $(this);
    $('#price').val(''); // Clear the input field
    const priceLabelText = $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"]').find('.price_lbl').text();
    const priceLabelText1 = priceLabelText.replace(/Rs\.|\/sq\.ft/g, '').trim();
    const priceInput = modal.find('input.set_price'); // Assuming there's an input field with class 'price_input'
    if (priceLabelText.trim() == 'Price not given') {
        priceInput.val(0);
    } else {
        priceInput.val(priceLabelText1);
    }
    modal.find('#tile_id').val(tileId); // Set the tile ID in the modal input
    modal.find('#cart_item_id').val(cartItemId);
});

$('#submit_btn').on('click', function(e) {
    let tileId = $('#tile_id').val();
    let price = $('#price').val();
    $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"] .price_lbl').text(`Rs. ${price}/sq.ft`);
    $('#updateprice').modal('hide');
    $('.modal-backdrop').remove();  // Remove the backdrop manually
    $('body').removeClass('modal-open');  // Remove the 'modal-open' class from body
    $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"] input#confirm_price').val(price);
});

// Submit the form via AJAX
$('.confirm_update').on('click', function() {
    const tileId = $(this).data('confirm-tile-id'); // Get the ID of the clicked tile
    const cartItemId = $(this).data('confirm-cart-item-id'); // Get the ID of the clicked tile
    let price = $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"] input#confirm_price').val();
    if (price === ""){
        alert("- Please enter Price\n");
    } else{
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
                row.find('.price-update').text(price); // Update price in the table cell
                alert("Price Updated Successfully!")
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
    }
});

$('.cartpanelclose').on('click', function(e) {
    $("body").css('overflow', "auto");
});

// Open modal and populate the fields with data attributes
$('#tilecal').on('show.bs.modal', function (event) {
    clearForm();

    // Get the button that triggered the modal
    const button = $(event.relatedTarget);

    // Get the tile ID from the button's data attribute
    let tile = button.data('tile-id');
    let height = $('#tile'+tile+' input#tiles_height').val();
    let width = $('#tile'+tile+' input#tiles_width').val();
    $('#tiles_size').val(`${width} x ${height} mm`);
    // Set the modal content
    $('#sizes').val(`${width}x${height}`);
    $('#calc_tile_id').val(tile);

    let tile_par_carton = $('#tile'+tile+' input#tiles_par_carton').val();
    $('#calc_tiles_par_carton').val(tile_par_carton);
    $('#calc_cart_item_id').val($('#cart_item_id').val());
});

//Tiles calc

$("#calculate_btn").click(function () {
    if(validationCheck()===false){
        return false;
    }

    let tilesIn1Box = $('#calc_tiles_par_carton').val(); //pieces this should come from DB

    let tile_id = $('#calc_tile_id').val();
    let widthInFeet = $("#width_feet").val();
    let heightInFeet = $("#length_feet").val();

    let wastage = $("#wast_per").val();

    let totalArea =  widthInFeet * heightInFeet;

    let totalAreaSqMeter = totalArea/10.764;

    let wastageOfTilesArea = (totalArea * wastage)/100;
    let actualWallFloorArea = Number(totalArea + wastageOfTilesArea);

    let tileWidthInFeet = getSizeOfTiles("#sizes","LEFT");
    let tileHeightInFeet = getSizeOfTiles("#sizes","RIGHT");

    let tilesArea =  (tileWidthInFeet * tileHeightInFeet );

    let tilesNeeded =  Math.ceil(actualWallFloorArea/tilesArea);
    let boxNeeded = Math.ceil(tilesNeeded/tilesIn1Box);

    $('div#tile' + tile_id + ' div.tiles_calculation_wrapper').css('display','block');
    $('div#tile'+tile_id+' div.tiles_calculation_wrapper span.total_area_covered_meter').text(totalAreaSqMeter.toFixed(2));
    $('div#tile'+tile_id+' div.tiles_calculation_wrapper span.total_area_covered_feet').text(totalArea.toFixed(2));
    $('div#tile'+tile_id+' div.tiles_calculation_wrapper span.tiles_wastage').text(wastageOfTilesArea);
    $('div#tile'+ tile_id + ' div.tiles_calculation_wrapper span.tiles_needed').text(tilesNeeded);

    if( tilesIn1Box !== "" ) {
        console.log("here");
        $('div#tile' + tile_id + ' div.tiles_carton_wrapper span.require_box').text(boxNeeded);
        displayResult("#required_box","Required Boxes : <b>" + boxNeeded+"</b> <small>(1 box have "+tilesIn1Box+" Tiles)</small>");
    }

    //Save data into db
    $.ajax({
        url: '/update-tile-calc', // URL to the controller method for updating the price
        type: 'POST',
        data: {
            tile_id: tile_id,
            totalAreaSqMeter: totalAreaSqMeter.toFixed(2),
            totalArea: totalArea.toFixed(2),
            wastage: wastageOfTilesArea,
            tilesIn1Box:( tilesIn1Box !== null ) ? tilesIn1Box : 0,
            tilesNeeded:tilesNeeded,
            boxNeeded:boxNeeded,
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
        },
        success: function(response) {

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

    displayResult("#area_covered_meter","Total Area covered : <b>" + totalAreaSqMeter.toFixed(2)+"</b> Sq. Meter");
    displayResult("#area_covered_feet","Total Area covered : <b>" + totalArea.toFixed(2)+"</b> Sq. Feet");
    displayResult("#required_tiles","Required Tiles : <b>" + tilesNeeded+"</b> Tiles");

    // $('#tilecal').modal('hide');
    // $('.modal-backdrop').remove();  // Remove the backdrop manually
    // $('body').removeClass('modal-open');  // Remove the 'modal-open' class from body


})

$("#reset_btn").click(function(){
    clearForm();
});

function clearForm() {
    $("#width_feet").val("");
    $("#length_feet").val("");
    $("#wast_per").val("");

    displayResult("#area_covered_meter","");
    displayResult("#area_covered_feet","");
    displayResult("#required_tiles","");
    displayResult("#required_box","");
}

function getSizeOfTiles(p_sizeId,p_side){
    let sizeString = $(p_sizeId).val();
    let arr = sizeString.split("x");
    if(p_side==="LEFT"){
        return (arr[0]/10)*0.0328;//mm to feet
    }
    if(p_side==="RIGHT"){
        return (arr[1]/10)*0.0328;//mm to feet
    }
}

function displayResult(p_displayid,p_message){
    let html = $(p_displayid).html();
    $(p_displayid).html(p_message);
}

function validationCheck(){
    var errorMessage = "";
    if ($("#width_feet").val() == "") {
        errorMessage += "- Please enter floor/wall width\n";
    }
    if ($("#length_feet").val() == "") {
        errorMessage += "- Please enter floor/wall length/height\n";
    }

    if ($("#tiles_size").val() == "") {
        errorMessage += "- Please select tiles size\n";
    }

    if ($("#wast_per").val() == "") {
        errorMessage += "- Please enter wastage percentage\n";
    }
    if(errorMessage == ""){
        return true;
    }
    else{
        alert(errorMessage);
        return false;
    }
}

function downloadImage() {

    let canvas = document.getElementById('roomCanvas');

    let imageCanvas = document.createElement('canvas');

    imageCanvas.width = canvas.width;

    imageCanvas.height = canvas.height;



    var imageCanvasContext = imageCanvas.getContext('2d');

    imageCanvasContext.drawImage(canvas, 0, 0, canvas.width, canvas.height);



    var companyLogo = document.getElementById('companyLogo');

    imageCanvasContext.drawImage(companyLogo, 20, 20, companyLogo.clientWidth, companyLogo.clientHeight);



    if (imageCanvas.msToBlob) {

        // for IE

        var blob = imageCanvas.msToBlob();

        window.navigator.msSaveBlob(blob, document.title + '.png');

    } else {

        var imgDataUrl = imageCanvas.toDataURL('image/jpeg');

        var link = document.createElement('a');

        if (typeof link.download === 'string') {

            document.body.appendChild(link);

            link.href = changeDpiDataUrl(imgDataUrl, 300);

            link.download = document.title + '.jpg';

            link.click();

            document.body.removeChild(link);

        }

    }

}

var JPEG = 'image/jpeg';



function changeDpiOnArray(dataArray, dpi, format) {

    if (format === JPEG) {

        dataArray[13] = 1; // 1 pixel per inch or 2 pixel per cm

        dataArray[14] = dpi >> 8; // dpiX high byte

        dataArray[15] = dpi & 0xff; // dpiX low byte

        dataArray[16] = dpi >> 8; // dpiY high byte

        dataArray[17] = dpi & 0xff; // dpiY low byte

        return dataArray;

    }

}



function changeDpiDataUrl(base64Image, dpi) {

    var dataSplitted = base64Image.split(',');

    var format = dataSplitted[0];



    if (format.indexOf(JPEG) !== -1) {

        var type = JPEG;

        var headerLength = 18 / 3 * 4;

        var body = dataSplitted[1];



        var stringHeader = body.substring(0, headerLength);

        var restOfData = body.substring(headerLength);

        var headerBytes = atob(stringHeader);

        var dataArray = new Uint8Array(headerBytes.length);

        for (var i = 0; i < dataArray.length; i += 1) {

            dataArray[i] = headerBytes.charCodeAt(i);

        }

        var finalArray = changeDpiOnArray(dataArray, dpi, type);

        var base64Header = btoa(String.fromCharCode.apply(String, _toConsumableArray(finalArray)));

        return [format, ',', base64Header, restOfData].join('');

    }



    return base64Image;

}