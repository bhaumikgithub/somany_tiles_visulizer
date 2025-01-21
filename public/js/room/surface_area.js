var alphabets = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P"];
var allSurfacesData = [];
var surfaceTypesDataTemp = [];
var clickedHTML = "";
window.onload = function getRoomSurface() {
    $.ajax({
        url: '/get_room_surface', // URL to the controller method for updating the price
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            room_id: $('#current_room_id').val(),
        },
        success: function(response) {

            $('.show_selected_surface_data div#selectd-data').html(response.body);
            //setTimeout(function(){$('.show_selected_surface_data div#selectd-data').html(loadAllFloorWallData());},3000);
        },
        error: function(error) {
            console.error('Error fetching room details', error);
        }
    });



}
function loadAllFloorWallData(){
    var allSurfaces = currentRoom.tiledSurfaces; // Array
    var htmlStr = '<ul>';

    for(var i = 0;i<allSurfaces.length;i++){
        var surfaceName = allSurfaces[i]._surfaceData.custom_surface_name;

        htmlStr += '<li class="slected_tile" id="li_'+surfaceName+'">';
        htmlStr += '<div class="tile-list-thumbnail-image-holder">';
        htmlStr += '<img src="https://somany.easytrials.in/storage/no_tile.png">';
        htmlStr += '</div>';
        htmlStr += '<div class="tile-list-text">';
        htmlStr += '<p class="-caption">'+ surfaceName +'</p>';
        htmlStr += '<div class="tile_details"></div>';
        htmlStr += '</div>';
        htmlStr += '<button class="open-panel")><span class="glyphicon-menu-right glyphicon" aria-hidden="true" onclick="openTileSelectionPanel(\''+surfaceName+'\') ></span></button>';
        htmlStr += '</li>';

        // console.log("allSurfaces[i].custom_surface_name = " + allSurfaces[i]._surfaceData.custom_surface_name);


    }
    htmlStr+="</ul>";
    return htmlStr;
}
//This function calling from the HTML of the wall A, wall B, Wall C, floor A, Floor B etc
function openTileSelectionPanel(surface_name) {
    console.log("openTileSelectionPanel");
    console.log(surface_name);
    //clickedHTML  = $("#li_"+surface_name);
    //clickedHTML.find(".tile_details")[0].html("Test");
    //$("#selectd-data").find(":contains('"+(surface_name)+"')").parent(".choosen_tile_updated_data");
    console.log($("#li_"+surface_name));
    console.log("look");
    // Show the info panel
    $('#selectd-data').hide();
    $('#slected-panel').show();
    $('#slected-panel .display_surface_name h5#optionText').text(convertFirstLetterCapital(surface_name));

    var clickedSurface = findRoomSurfaceUsingName(convertFirstLetterCapital(surface_name));
    //findRoomSurfaceNameFromAlphabet(surface_name);

    if(clickedSurface!=false){
        //This function directly go to 2d.min.js and set the current surface
        console.log("_onSurfaceClick clickedSurface = ");
        console.log(clickedSurface);
        currentRoom._onSurfaceClick(clickedSurface);
    }


}
function clickedTiles(p_tile,p_surfaceName){
    console.log("Clicked Tiles");
    console.log(p_tile);
    console.log("p_surfaceName = " + p_surfaceName);
}


//This function clicked from 2d.min.js
//When user click on any of the wall, floor, counter, paint, it will call this function.
function surfaceClickedByUser(p_surface_name){
    $('#selectd-data').hide();
    $('#slected-panel').show();
    $('#topPanel h5').text(p_surface_name);
    //updateTopPanelText(p_surface_name);
}

function findRoomSurfaceUsingName(p_name){
    var allSurfaces = currentRoom.tiledSurfaces; // Array
    console.log("allSurfaces.length = " + allSurfaces.length);
    console.log("p_name = " + p_name);
    for(var i = 0;i<allSurfaces.length;i++){
        console.log("allSurfaces[i].custom_surface_name = " + allSurfaces[i]._surfaceData.custom_surface_name);

        if(allSurfaces[i]._surfaceData.custom_surface_name == p_name){
            return allSurfaces[i]
        }
    }
    return false;
}
function getCustomNameOfSurfaceData(p_surfaceType){
    var cnt = 0;
    for(var k=0;k<surfaceTypesDataTemp.length;k++){
        if(surfaceTypesDataTemp[k] == p_surfaceType){
            cnt++;
        }
    }
    surfaceTypesDataTemp.push(p_surfaceType);
    return convertFirstLetterCapital(p_surfaceType + " " + alphabets[cnt]);

}
function convertFirstLetterCapital(p_str){
    p_str = p_str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return p_str;
}
