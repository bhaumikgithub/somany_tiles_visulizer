function addToPDFAI(){
    'use strict';
    let current_room_type = $("#current_room_type_ai").val();
    let selected_tiles_ids = $('#selected_tile_ids_ai').val();
    let thumbnailData = generateAndDownloadThumbnail();
    let currentDesign = canvasImage();

    if( selected_tiles_ids.length === 0 ) {
        alert("Please select any tiles first");
    } else { // Show the loading message
        // Show the loading message
        $('.loadingMessage').show();
        window.$.ajax({
            url: '/add-to-pdf-data-store-ai', // Laravel route URL
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
                data: {
                    'room_type':current_room_type,
                    'selected_tiles_ids':selected_tiles_ids,
                    'thumbnail':thumbnailData,
                    'currentDesign':currentDesign,
                },
            },
            success: function (response) {
                // Hide the loading message
                $('.loadingMessage').hide();

                $('#dialogSaveModalBox').modal('hide');
                $('.product_title').text('Selected Rooms');
                $('.braces').css('display','inline');
                $('.productCount').text(response.data.all_selection);
                $('#addToCartInfoPanelModalAI').modal('show');
                $('#addToCartInfoPanelModalAI #cartInfoTilesListAI').html(response.body);
                $("body").css('overflow', "hidden");
                if( $('.continue-modal').length > 0 ) {
                    $('.continue-modal').modal('hide');
                }
                if( $('.no-data-in-cart-selection-modal').length > 0 ){
                    $('.no-data-in-cart-selection-modal').modal('hide');
                }
                $('div.modal-backdrop').each(function () {
                    if (!$(this).attr('id')) {
                        // Hide the div
                        $(this).hide();
                    }
                });

                // Update the href attribute of the link in the modal
                //$('.continue-modal a#cart_url').attr('href', response.url);
            },
            error: function (xhr, status, error) {
                // Hide the loading message on error
                $('#loadingMessage').remove();
                alert('Failed to stored!');
                console.error(error);
            },
        });
    }
}

// Get the main canvas and its context
function generateAndDownloadThumbnail(){
    // Create an offscreen canvas for the thumbnail
    let mainCanvas ;
    mainCanvas = document.getElementById('roomCanvas');
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
    let canvas;
    canvas = document.getElementById('roomCanvas');
    let imageCanvas = document.createElement('canvas');
    imageCanvas.width = canvas.width;
    imageCanvas.height = canvas.height;
    let imageCanvasContext = imageCanvas.getContext('2d');
    imageCanvasContext.drawImage(canvas, 0, 0, canvas.width, canvas.height);
    return imageCanvas.toDataURL('image/jpeg');
}

// Initialize an empty array
let ids = [];

function getTileId(id) {
    // Retrieve and parse the existing array from the hidden field
    let ids = JSON.parse($('#selected_tile_ids_ai').val() || '[]');

    // Get the selected tile ID and surface from the clicked <li>
    let selectedTileId = $('li#' + id).data('tile');
    let selectedTileSurface = $('li#' + id).data('surface');

    viewdTilesIds(selectedTileId,selectedTileSurface)

    // Check if the free tile checkbox is checked
    let isFreeTileEnabled = $('#free_tile_checkbox_value_ai').val() === "on";

    // Track whether a free tile was added
    let freeTileAdded = false;

    if (isFreeTileEnabled) {
        // Ensure only one free tile exists per surface
        let existingFreeTileIndex = ids.findIndex(tile => tile.surface === selectedTileSurface && tile.isFreeTile);
        if (existingFreeTileIndex !== -1) {
            // Replace the previous free tile for the same surface
            ids[existingFreeTileIndex] = {
                tileId: selectedTileId,
                surface: selectedTileSurface,
                isFreeTile: true
            };
        } else {
            // Add a new free tile if not present for this surface
            ids.push({
                tileId: selectedTileId,
                surface: selectedTileSurface,
                isFreeTile: true
            });
        }
        freeTileAdded = true;
    } else {
        // Ensure only one regular tile per surface
        let existingRegularTileIndex = ids.findIndex(tile => tile.surface === selectedTileSurface && !tile.isFreeTile);
        if (existingRegularTileIndex !== -1) {
            // Replace the previous tile for the same surface
            ids[existingRegularTileIndex] = {
                tileId: selectedTileId,
                surface: selectedTileSurface,
                isFreeTile: false
            };
        } else {
            // Add new tile if no tile exists for this surface
            ids.push({
                tileId: selectedTileId,
                surface: selectedTileSurface,
                isFreeTile: false
            });
        }
    }

    // Store the updated array in the hidden field
    $('#selected_tile_ids_ai').val(JSON.stringify(ids));

    // If a free tile was added, reset the checkbox value to "off"
    if (freeTileAdded) {
        $('#free_tile_checkbox_value_ai').val("off");
    }
}



function removeProductFromCart(id) {
    let totalProductCount = $('.productCount').text();
    window.$.ajax({
        url: `/add-to-pdf-data/${id}`, // Endpoint for deletion
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        success: function (response) {
            alert(response.message);
            let finalCount = parseInt(totalProductCount) - parseInt(response.data);
            if( finalCount > 0 ){
                $('.product_title').text('Selected Rooms');
                $('.braces').css('display','inline');
                $('.productCount').text(finalCount);
            } else {
                $('.braces').css('display','none');
                $('.product_hr').hide();
                $('.button-row').hide();
                $('.product_title').text('No Selected Rooms');
                $('.loadCartItems').addClass('no-data-text');
                $('.no-data-text').html("There is no Selection , Please add some tiles and click on Add to Selection");
            }
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
                if( $('.continue-modal').length > 0 ) {
                    $('.continue-modal').modal('hide');
                }
                if( $('.no-data-in-cart-selection-modal').length > 0 ){
                    $('.no-data-in-cart-selection-modal').modal('hide');
                }
                $('#addToCartInfoPanelModal').modal('show');

                if( response.data.all_selection > 0 ) {
                    $('.productCount').text(response.data.all_selection);
                }

                $('div.modal-backdrop').each(function () {
                    // Check if it has exactly "modal-backdrop", "fade", and "in" classes
                    if ($(this).hasClass('modal-backdrop') &&
                        $(this).hasClass('fade') &&
                        $(this).hasClass('in') &&
                        this.classList.length === 3) {
                        // Hide the element
                        $(this).hide();
                    }
                });


                $('#addToCartInfoPanelModal #cartInfoTilesList').html(response.body);

            }
        },
        error: function (xhr) {
            alert('Something went wrong!');
        }
    });

}

function hideCart() {
    $('#addToCartInfoPanelModal').modal('hide');
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
            $('.braces').css('display','none');
            $('.product_hr').hide();
            $('.button-row').hide();
            $('.product_title').text('No Selected Rooms');
            $('.loadCartItems').addClass('no-data-text');
            $('.loadCartItems').html("There is no Selection , Please add some tiles and click on Add to Selection");
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

$('.update_price_btn').click(function() {
    $('#updateprice').modal('show');
    let tileId = $(this).data('tile-id'); // Extract tile ID
    let cartItemId = $(this).data('price-update-cart-item-id'); // Extract tile ID
    $('#price').val(''); // Clear the input field
    const priceLabelText = $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"][data-cart-item-id="' + cartItemId + '"]').find('.price_lbl').text();
    const priceLabelText1 = priceLabelText.replace(/Rs\.|\/sq\.ft/g, '').trim();
    const priceInput = $('#updateprice').find('input.set_price'); // Assuming there's an input field with class 'price_input'
    if (priceLabelText.trim() === 'Price not given') {
        priceInput.val();
    } else {
        priceInput.val(priceLabelText1);
    }
    $('#updateprice #tile_id').val(tileId); // Set the tile ID in the modal input
    $('#updateprice #cart_item_id').val(cartItemId);
});

$('#submit_btn').on('click', function(e) {
    e.preventDefault();
    let tileId = $('#tile_id').val();
    let cartItemId = $('#cart_item_id').val();
    let price = $('#price').val();
    let priceError = $('#price-error');

    // Clear any previous error message
    priceError.text('');
    if (price === "" || isNaN(price) || price <= 0) {
        priceError.text('Please enter a valid price.');
    } else{
        $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"][data-cart-item-id="' + cartItemId + '"] .price_lbl').text(`Rs. ${price}/sq.ft`);
        $('#updateprice').modal('hide');
        // $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"][data-cart-item-id="' + cartItemId + '"] button.confirm_update').show();
        $('.modal-backdrop').remove();
        $('#price-error').text(''); // Clear any error messages
        $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"][data-cart-item-id="' + cartItemId + '"] input#confirm_price').val(price);
    }

});

// Handle dynamic validation on input field
$('#price').on('input', function() {
    let price = $(this).val().trim();
    let priceError = $('#price-error');

    // Clear the error message if input is valid
    if (price !== "" && !isNaN(price) && price > 0) {
        priceError.text('');
    } else {
        priceError.text('Please enter a valid price.');
    }
});

// Submit the form via AJAX
$('.confirm_update').on('click', function() {
    const tileId = $(this).data('confirm-tile-id'); // Get the ID of the clicked tile
    const cartItemId = $(this).data('confirm-cart-item-id'); // Get the ID of the clicked tile
    let price = $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"][data-cart-item-id="' + cartItemId + '"] input#confirm_price').val();
    if (price === ""){
        alert("- Please enter Price\n");
    } else{
        $.ajax({
            url: '/update-tile-price', // URL to the controller method for updating the price
            type: 'POST',
            data: {
                tile_id: tileId,
                cartItemId:cartItemId,
                price: price,
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            },
            success: function(response) {
                // On success, update the price in the table
                let row = $('div.update_price_wrapper[data-price-tile-id="' + tileId + '"][data-cart-item-id="' + cartItemId + '"]');
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

$('.tile_calculation').click(function() {
    $('#tilecal').modal('show');
    clearForm();
    // Get the tile ID from the button's data attribute
    let tile = $(this).data('tile-id');

    let blockId = $(this).data("unique-id");
    let surface_title = $(this).data("surface-name");
    let cart_item_id = $(this).data('calculate-cart-item-id');
    let height = $('#tile'+tile+' input#tiles_height').val();
    let width = $('#tile'+tile+' input#tiles_width').val();

    let width_in_feet = $('div#tile' + tile + ' div.tiles_calculation_wrapper_'+cart_item_id+'_'+blockId).find('input#width_in_feet').val();
    let height_in_feet = $('div#tile' + tile + ' div.tiles_calculation_wrapper_'+cart_item_id+'_'+blockId).find('input#height_in_feet').val();
    $('#tiles_size').val(`${width} x ${height} mm`);

    // Set the modal content
    $('#sizes').val(`${width}x${height}`);
    $('#calc_tile_id').val(tile);
    $("#width_feet").val(width_in_feet);
    $("#length_feet").val(height_in_feet);
    $('#unique_block_id').val(blockId);

    let tile_par_carton = $('#tile'+tile+' input#tiles_par_carton').val();
    $('#calc_tiles_par_carton').val(tile_par_carton);
    $('#calc_cart_item_id').val(cart_item_id);
    $('#surface_title').val(surface_title);
});

//Tiles calc

$("#calculate_btn").click(function () {
    if(validationCheck()===false){
        return false;
    }
    // Get the block ID from the clicked button's data-id
    let blockId = $('#unique_block_id').val();
    let cart_item_id=$('#calc_cart_item_id').val();
    let tilesIn1Box = $('#calc_tiles_par_carton').val(); // this should come from DB
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

    let blockSelector = $('div#tile' + tile_id + ' div.tiles_calculation_wrapper_'+cart_item_id+'_'+blockId);

    blockSelector.css('display','block');
    blockSelector.find('span.total_area_covered_meter').text(totalAreaSqMeter.toFixed(2));
    blockSelector.find('span.total_area_covered_feet').text(totalArea.toFixed(2));
    blockSelector.find('span.tiles_wastage').text(wastage);
    blockSelector.find('span.tiles_needed').text(tilesNeeded);
    $('#calc_area_covered_meter').val(totalAreaSqMeter.toFixed(2));
    $('#calc_area_covered').val(totalArea.toFixed(2));
    $('#calc_wastage').val(wastage);
    $('#calc_tiles_needed').val(tilesNeeded);

    if( tilesIn1Box !== "" ) {
        let tileSelector = $('div#tile' + tile_id + ' div.tiles_carton_wrapper_'+cart_item_id+'_'+blockId);
        tileSelector.css('display','block');
        tileSelector.find('input#require_box').val(boxNeeded);
        tileSelector.find('p.textBoxWrap').css('display','block');
        tileSelector.find('span.require_box').text(boxNeeded);
        $('#required_box').show();
        displayResult("#required_box","Required Boxes : <b>" + boxNeeded+"</b> <small>(1 box have "+tilesIn1Box+" Tiles)</small>");
    }


    displayResult("#area_covered_meter","Total Area covered : <b>" + totalAreaSqMeter.toFixed(2)+"</b> Sq. Meter");
    displayResult("#area_covered_feet","Total Area covered : <b>" + totalArea.toFixed(2)+"</b> Sq. Feet");
    displayResult("#required_tiles","Required Tiles : <b>" + tilesNeeded+"</b> Tiles");

});

function updateSummaryTable(tiles) {
    let tilesArray = Object.values(tiles); // ✅ Convert object to array

    let totalArea = 0, totalBoxes = 0, totalPrice = 0;

    tilesArray.forEach((tile) => { // ✅ Now forEach will work
        let existingRow = $(`#summary-table tbody tr[data-tile-id="${tile.id}"]`);
        let mrpPrice = 0;
        if (existingRow.length > 0) {
            // Update values in the existing row
            existingRow.find('.summary-total-area').text(tile.area_sq_ft);
            existingRow.find('.summary-box-needed').text(tile.box_required);
            if( tile.mrp_price !== "-"){
                existingRow.find('.summary-mrp-price').text(tile.mrp_price);
                totalPrice += parseInt(tile.mrp_price);
            }
            //Accumulate total values
            // totalArea += parseFloat(tile.area_sq_ft);
            // totalBoxes += parseInt(tile.box_required);

        } else {
            console.warn("Tile ID not found in summary table:", tile.id);
        }
    });

    // Update total row
    // $("#summary-total-area").text(totalArea.toFixed(2));
    // $("#summary-total-boxes").text(totalBoxes);
    if(totalPrice !==0 )
        $("#summary-total-mrp-price").text("Rs. " + totalPrice);
}



//0 = Sr. No
//1 = Name
//2 = Size
//3 = Finish

function calculateBoxCoverageArea(){
    var rows = $(".summary-page-table").find("tr");
    for(var i=0;i<rows.length;i++){
        var tileSizeInString= String(findDataFromTable(i,2)); // Size
        if(tileSizeInString.length>2){
            var value = tileSizeInString.split("x");
            var tilesWidthinSqFt = (Number(value[0])/30)/10;
            var tilesHeightinSqFt = (Number(value[1])/30)/10;
            findDataFromTable(i,7,Number(tilesWidthinSqFt*tilesHeightinSqFt));//7 = Box Coverage Area in Sq ft
        }
    }
}

function findDataFromTable(p_row,p_col,p_value){
    var rows = $(".summary-page-table").find("tr");
    var row = $(rows).eq(p_row);
    var foundedRowCol =  $(row).find("td")[p_col];//Size
    if(p_value){
        $(foundedRowCol).html(p_value);
    }
    else{
        return $(foundedRowCol).html();
    }
}

$('#closeTileCalcModal').click(function() {
    let tilesIn1Box = $('#calc_tiles_par_carton').val(); //pieces this should come from DB
    let blockId = $('#unique_block_id').val();
    let tile_id = $('#calc_tile_id').val();
    let cart_item_id = $('#calc_cart_item_id').val();
    let widthInFeet = $("#width_feet").val();
    let heightInFeet = $("#length_feet").val();
    let totalAreaSqMeter = $('#calc_area_covered_meter').val();
    let totalArea = $('#calc_area_covered').val();
    let wastageOfTilesArea = $('#calc_wastage').val();
    let tilesNeeded = $('#calc_tiles_needed').val();
    let boxNeeded = $('div#tile' + tile_id + ' div.tiles_carton_wrapper_'+cart_item_id+'_'+blockId).find('input#require_box').val();

    //Save data into db
    $.ajax({
        url: '/update-tile-calc', // URL to the controller method for updating the price
        type: 'POST',
        data: {
            tile_id: tile_id,
            cart_item_id:cart_item_id,
            widthInFeet:widthInFeet,
            heightInFeet:heightInFeet,
            totalAreaSqMeter: totalAreaSqMeter,
            totalArea: totalArea,
            wastage: wastageOfTilesArea,
            tilesIn1Box:( tilesIn1Box !== null ) ? tilesIn1Box : 0,
            tilesNeeded:tilesNeeded,
            boxNeeded:boxNeeded,
            surfaceName : $('#surface_title').val(),
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
        },
        success: function(response) {
            if( response.success === true) {
                $('div#tile' + tile_id + ' div.tiles_calculation_wrapper_'+cart_item_id+'_'+blockId).css('display', 'block');
                //Call ajax to update summary table
                $.ajax({
                    url: "/get-tile-summary", // Your Laravel route
                    type: "POST",
                    data: {
                        tile_id: tile_id,
                        random_key : $('#random_key').val(),
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF protection
                    },
                    success: function (response) {
                        if (response.success) {
                            //Update Summary Table with the new values
                            updateSummaryTable(response.tiles);
                        } else {
                            console.error("Error fetching summary data.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX error:", error);
                    }
                });
            }
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

$("#reset_btn").click(function(){
    clearForm();
});

function clearForm() {
    $("#width_feet").val("");
    $("#length_feet").val("");

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

    if(errorMessage == ""){
        return true;
    }
    else{
        alert(errorMessage);
        return false;
    }
}

function downloadImage() {
    let category_type = $('#current_room_type_ai').val();

    let key = `${category_type}`;

    // Retrieve count from sessionStorage
    let count = sessionStorage.getItem(key) ? parseInt(sessionStorage.getItem(key)) : 0;
    count++;
    sessionStorage.setItem(key, count);

    let fileName = `${category_type}_img_${count}.jpg`;

    let canvas;
    canvas = document.getElementById('roomCanvas');

    let imageCanvas = document.createElement('canvas');
    imageCanvas.width = canvas.width;
    imageCanvas.height = canvas.height;
    let imageCanvasContext = imageCanvas.getContext('2d');
    imageCanvasContext.drawImage(canvas, 0, 0, canvas.width, canvas.height);
    let companyLogo = document.getElementById('companyLogo');
    imageCanvasContext.drawImage(companyLogo, 20, 20, companyLogo.clientWidth, companyLogo.clientHeight);
    if (imageCanvas.msToBlob) {
        let blob = imageCanvas.msToBlob();
        window.navigator.msSaveBlob(blob, fileName);
    } else {
        let imgDataUrl = imageCanvas.toDataURL('image/jpeg');
        let link = document.createElement('a');
        if (typeof link.download === 'string') {
            document.body.appendChild(link);
            link.href = changeDpiDataUrl(imgDataUrl, 300);
            link.download = fileName;
            link.click();
            document.body.removeChild(link);
        }
    }
}

function formatRoomName(roomName) {
    return roomName.toLowerCase().replace(/\s+/g, '_');
}

let JPEG = 'image/jpeg';

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

if( $('.pdf-summary-container').length > 0 ) {
    $('input[type="checkbox"]').change(function () {
        const $checkbox = $(this);
        const itemId = $checkbox.data('cart-item-id');
        const $imageWrapper = $('#imageWrapper_' + itemId);
        const isChecked = $checkbox.is(':checked');
        const showImage = isChecked ? 'yes' : 'no';

        $imageWrapper.toggle(isChecked);
        $checkbox.val(showImage);

        // You can call updatePreference here if needed
        updatePreference(isChecked, itemId);
    });

    function updatePreference(showImage, cart_item_id) {
        $.ajax({
            url: '/update-preference', // URL to the controller method for updating the price
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
                show_image: (showImage === false) ? "no" : "yes",
                cart_item_id: cart_item_id,
            },
            success: function (response) {
                console.log('Preference updated successfully');
            },
            error: function (error) {
                console.error('Error updating preference:', error);
            }
        });
    }
} else {
    $(document).on('change', '.topPanelCheckFreeDesign', function () {
        let isChecked = $(this).is(':checked');
        $('#free_tile_checkbox_value_ai').val(isChecked ? "on" : "off");
    });
}

function checkCartHasData(){
    let selection_tile_id = $('#selected_tile_ids_ai').val();
    if ( selection_tile_id.length <= 0 ){
        alert("Please select any tiles first");
    } else {
        window.$.ajax({
            url: `/check-selection-has-data`, // Endpoint for deletion,
            type: 'POST',
            data: {
                session_id: $('#currentSessionIdAI').val(),
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
            },
            success: function (response) {
                if (response.success === true && response.count === 1) {
                    $('.continue-modal').modal('show');
                } else {
                    $('.no-data-in-cart-selection-modal').modal('show');
                }
            }
        });
    }
}

///////// added by vikesha ///////////////////

function tableresize() {
    var pageWidth = $(window).width(); // Get the current page width

    // If the page width is less than or equal to 768px, apply custom width
    if (pageWidth <= 768) {
        var newWidth = pageWidth - 40; // Subtract 40px for padding/margins
        // Set the new width for the row and the table, and apply margin-right
        $('.summary-page-table-row').css({
            'width': newWidth,  // Set the new width for the row
            'margin': '0px 0px 0px 4px'  // Add margin to the right of the row
        });
        $('.summary-page-table').css({
            'width': newWidth - 20,  // Set the new width for the table
        });
    } else {
        // If the page width is greater than 768px, set the width to 100% (full width)
        $('.summary-page-table-row').css({
            'width': '100%',  // Full width for row
            'margin': '0'     // No margin needed for full width
        });
        $('.summary-page-table').css({
            'width': '100%',  // Full width for table
        });
    }
}

tableresize(); // Call once on page load

// Debounce window resize event to optimize performance
var resizeTimeout;
$(window).on('resize', function () {
    clearTimeout(resizeTimeout);  // Clear the previous timeout
    resizeTimeout = setTimeout(function () {
        tableresize();  // Call after resizing has stopped
    }, 200);  // Adjust 200ms as needed for your preference
});


/** This function stored data under analytics table where check how many tiles applied in which room */
function viewdTilesIds(userSelectedTileId,selectedSurface)
{   
    $.ajax({
        url:`/user-viewd-tiles`, // Endpoint for deletion,
        type:'POST',
        data:{
            userSelectedTileId:userSelectedTileId,
            current_room_type:"ai_room",
            surface:selectedSurface,
            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        success: function (response) {
            console.log(response);
        }
    });
}


