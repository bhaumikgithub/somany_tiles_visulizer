var alphabets = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P"];
var allSurfacesData = [];
var surfaceTypesDataTemp = [];
var clickedHTML = "";
var currentListId = "";

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
        },
        error: function(error) {
            console.error('Error fetching room details', error);
        }
    });



}

//This function calling from the HTML of the wall A, wall B, Wall C, floor A, Floor B etc
function openTileSelectionPanel(surface_name) {
    let oldSurfaceName = surface_name;
    setCurrentListID(surface_name);//List_wall_a

    var newName = String(surface_name).split("_");
    surface_name = convertFirstLetterCapital(newName[0] + " " + newName[1]);

    // Show the info panel
    showMainInfoPanel("MAINLISTING_HIDE",oldSurfaceName);
    if( oldSurfaceName != "theme") {
        $('#slected-panel .display_surface_name h5#optionText').text(surface_name);
    } else {
        $('#slected-panel .display_surface_name h5#optionText').text("Themes");
    }

    var clickedSurface = findRoomSurfaceUsingName(surface_name);

    if(clickedSurface!=false){
        //This function directly go to 2d.min.js and set the current surface
        currentRoom._onSurfaceClick(clickedSurface);
    }


}
function showMainInfoPanel(p_type,surface_name){
    console.log("showMainInfoPanel = " + p_type);
    if( surface_name === "theme"){
        if(p_type=="MAINLISTING_HIDE"){
            $('#selectd-data').hide();
            $('#slected-panel').show();
            $('.withoutThemePanelWrapper').hide();
            $('#selected_panel_theme').show();

            //get theme data
            let current_room_id = $('#current_room_id').val();
            // Fetch data from room2d endpoint
            $.ajax({
                url: '/get/room2d/'+$('#current_room_id').val(), // Replace with the actual endpoint for room2d
                success: function (themes) {
                    // JSON data (you can replace this with data fetched from an AJAX call)
                    const themeData = {
                        theme_thumbnail1: themes.theme_thumbnail1,
                        text1: themes.text1,
                        theme_thumbnail2: themes.theme_thumbnail2,
                        text2: themes.text2,
                        theme_thumbnail3: themes.theme_thumbnail3,
                        text3: themes.text3,
                        theme_thumbnail4: themes.theme_thumbnail4,
                        text4: themes.text4,
                        theme_thumbnail5: themes.theme_thumbnail5,
                        text5: themes.text5,
                    };

                    // Select the <ul> container
                    const themeList = document.getElementById("topPanelThemeListUl");

                    // Iterate through theme data
                    for (let i = 1; i <= 5; i++) {
                        const thumbnail = themeData[`theme_thumbnail${i}`];
                        const text = themeData[`text${i}`];

                        // Only add <li> if both thumbnail and text are present
                        if (thumbnail) {
                            const li = document.createElement("li");
                            li.className = "top-panel-content-tiles-list-item";

                            // Add the inner HTML structure
                            li.innerHTML = `
                                    <div class="tile-list-thumbnail-image-holder">
                                      <img src="${thumbnail}" alt="Theme Thumbnail ${i}">
                                    </div>
                                    <div class="tile-list-text">
                                      <p class="-caption">${text || `Theme ${i}`}</p>
                                    </div>
                                  `;

                            // Append the <li> to the <ul>
                            themeList.appendChild(li);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching themes:', error);
                },
            });
        }
        else if(p_type=="MAINLISTING_SHOW"){
            $('#selectd-data').show();
            $('#slected-panel').hide();
            $('.withoutThemePanelWrapper').show();
            $('#selected_panel_theme').hide();
        }
    } else {
        if(p_type=="MAINLISTING_HIDE"){
            $('#selectd-data').hide();
            $('#slected-panel').show();
        }
        else if(p_type=="MAINLISTING_SHOW"){
            $('#selectd-data').show();
            $('#slected-panel').hide();
        }
    }

}

function clickedTiles(p_tile,p_surfaceName){
    //Wall A convert to wall_A
    var temp = p_surfaceName.split(" ");
    setCurrentListID(String(temp[0]).toLowerCase() + "_" + temp[1]);

    console.log("p_tile.name = " + p_tile.name);
    console.log("p_surfaceName = " + p_surfaceName);
    //Wall A
    var detailDiv = currentListId.find(".detail")[0];
    var imageDiv = currentListId.find("img")[0];
    $(detailDiv).html( convertFirstLetterCapital(p_tile.name) + "<br><small>"+convertFirstLetterCapital(p_tile.finish)+"</small>");
    $(imageDiv).attr("src",p_tile.icon);
}

function setCurrentListID(p_surfaceType){
    currentListId = $("#list_" + p_surfaceType);
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
    if( p_surfaceType !== "Themes")
        return convertFirstLetterCapital(p_surfaceType + " " + alphabets[cnt]);
    else
         return "Themes";

}
function convertFirstLetterCapital(p_str){
    p_str = p_str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return p_str;
}
