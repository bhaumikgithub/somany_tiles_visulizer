let alphabets = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P"];
let allSurfacesData = [];
let surfaceTypesDataTemp = [];
let clickedHTML = "";
let currentListId = "";
let themeData;
let wallAwallBwallCListing = '#selectd-data';
let wallFloorContent = '.withoutThemePanelWrapper';
let wallFloorThemeContentParent = '#slected-panel';
let themeContent = '#selected_panel_theme';

let url = new URL(window.location.href);
let pathSegments = url.pathname.split("/");
let surfaceUrl = "";

window.onload = function getRoomSurface() {
    if(pathSegments[1] === "panorama-studio"){
        surfaceUrl = '/get_room_surface_panorama';
    } else {
        surfaceUrl = '/get_room_surface';
    }
    $.ajax({
        url: surfaceUrl, // URL to the controller method for updating the price
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            room_id: $('#current_room_id').val(),
        },
        success: function (response) {
            $('.show_selected_surface_data div#selectd-data').html(response.body);
            loadThemeData();
            $('#selected_panel_theme').removeClass("withoutThemePanelWrapper");
            //showMainInfoPanel("MAINLISTING_SHOW", "theme");
        },
        error: function (error) {
            console.error('Error fetching room details', error);
        }


    });



}

//This function calling from the HTML of the wall A, wall B, Wall C, floor A, Floor B, Paint A, Counter, Theme etc
//surface_name = Clicked button from the right panel

function openTileSelectionPanel(surface_name) {


    /////////added by vikesha////////////////////////////
    let firstWord = surface_name.split('_')[0];
    // Hide or show .top-panel-search based on the first word
    var isCounterOrPaint = firstWord === "paint";
    $('.top-panel-search').toggle(!isCounterOrPaint);
    $('.partOfProductTabContent-wrap').toggle(!isCounterOrPaint);


    // Hide or show #btnGrout based on the first word
    $('#btnGrout').toggle(firstWord !== "counter");

    /////////code end////////////////////////

    let oldSurfaceName = surface_name;
    setCurrentListID(surface_name);//List_wall_a

    surface_name = getSurfaceNameForLabeling(surface_name);

    // Show the info panel
    showMainInfoPanel("MAINLISTING_HIDE", oldSurfaceName);

    if (oldSurfaceName != "theme") {
        $('#slected-panel .display_surface_name h5#optionText').text(surface_name);
    } else {
        $('#slected-panel .display_surface_name h5#optionText').text("Themes");
    }
    //top-panel-box
    if (String(surface_name).indexOf("Paint") > -1) {
        $(".serch-box-wrap").hide();
        $(".top-panel-box-first").hide();
        $(".radio-surface-rotation").hide();
        $("#topPanelContentSurfaceTabGroutSizeBody").hide();
        // row top-panel-box top-panel-box-first top-panel-box-first-btn-wrap top-panel-box-cmn-br
    }
    else {
        $(".serch-box-wrap").show();
        $(".top-panel-box-first").show();
    }

    if(isThisMobileDevice()==true){
        if (String(surface_name).indexOf("Paint") > -1 || String(surface_name).indexOf("Theme") > -1) {
            $(".title-area").show();
        }
        else{
            $(".title-area").hide();
        }
    }
    let clickedSurface = findRoomSurfaceUsingName(surface_name);

    if (clickedSurface != false) {
        //This function directly go to 2d.min.js and set the current surface
        currentRoom._onSurfaceClick(clickedSurface,true);
    }


}

function getSurfaceNameForLabeling(p_surfaceName){
    let newName = String(p_surfaceName).split("_");
    return convertFirstLetterCapital(newName[0] + " " + newName[1]);
}

let themeSurfaceUrl = "";
function loadThemeData() {
    if(pathSegments[1] === "panorama-studio"){
        themeSurfaceUrl = '/get/panorama-studio/';
    } else {
        themeSurfaceUrl = '/get/2d-studio/';
    }
    $.ajax({
        url: themeSurfaceUrl + $('#current_room_id').val(), // Replace with the actual endpoint for room2d
        success: function (themes) {
            $('#selected_panel_theme').show();
            // console.log("THEME DATA");
            // console.log(themes);
            // JSON data (you can replace this with data fetched from an AJAX call)
            themeData = [];
            for (let i = 0; i <= 5; i++) {
                if (themes["theme_thumbnail" + i] && themes["text" + i] && themes["theme" + i]) {
                    addThemeData({
                        theme_id: i,
                        theme_thumbnail: themes["theme_thumbnail" + i],
                        text: themes["text" + i],
                        theme_bigimage: themes["theme" + i]
                    });
                }
            }

            // Select the <ul> container
            const themeList = document.getElementById("topPanelThemeListUl");
            // Clear existing data
            themeList.innerHTML = "";
            // Iterate through theme data
            let themeExist = false;
            for (let i = 0; i < themeData.length; i++) {
                let themeObj = themeData[i];
                // Only add <li> if both thumbnail and text are present
                if (themeObj["theme_thumbnail"]) {
                    themeExist = true;
                    const thumbnail = themeObj.theme_thumbnail;
                    const text = themeObj.text;
                    const li = document.createElement("li");
                    li.id = "theme_thumb_" + i;
                    li.className = "top-panel-content-tiles-list-item";

                    // Add the inner HTML structure
                    li.innerHTML = `
                            <div  class="tile-list-thumbnail-image-holder" onclick="themeBtnPressed('`+ i + `')">
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
            if (themeExist == false) {
                $("#list_theme").hide();
            }
            themeBtnPressed(0, "IMAGE_LOAD_NOT_REQUIRED");
        },
        error: function (xhr, status, error) {
            console.error('Error fetching themes:', error);
        },
    });
}
function addThemeData(p_obj) {
    if (p_obj)
        themeData.push(p_obj)
}
function showMainInfoPanel(p_type, surface_name) {
    console.log("SHOW MAIN INFO TYPE = " + p_type + " SURFACE = " + surface_name);
    allRightPanelContentHide();

    if (p_type == "MAINLISTING_HIDE") {
        console.log("MAINLISTING HIDE");
        //  $('#selectd-data').hide(); //wall A, b , c hide
        $(wallFloorThemeContentParent).show();


        if (surface_name == "theme") {
            console.log("IN surface_name THEME");
            $(themeContent).show();
        }
        else {
            console.log("IN surface_name NOT THEME");
            hideOpenFilters();
            $(wallFloorContent).show();
        }
    }

    if (p_type == "MAINLISTING_SHOW") {
        console.log("IN MAINLISTING_SHOW");
        $(wallAwallBwallCListing).show();
    }
}
function themeBtnPressed(p_id, p_imageLoadByPass) {
    if (p_imageLoadByPass == "IMAGE_LOAD_NOT_REQUIRED") {

    }
    else {
        if(pathSegments[1] === "2d-studio"){
            currentRoom._engine2d.loadAndDrawForegroundImage(themeData[p_id].theme_bigimage);
        } else {
            //,currentRoom.currentTiledSurface._surfaceData.json
            currentRoom._view.loadAndDrawForegroundImage(p_id);
        }
    }
    $(".top-panel-content-tiles-list-item").removeClass("active_theme");
    $("#theme_thumb_" + p_id).addClass("active_theme");
    //list_theme
    clickedTiles(themeData[p_id], "theme");
    //added by vikesha (serchbox and grout check)
    showHideTabs();

}
function clickedTiles(p_tile, p_surfaceName) {
    let textForMainPanel = "";
    let thumbImage = "";

    if (p_surfaceName == "theme") {
        setCurrentListID("theme");
        if( p_tile ) {
            textForMainPanel = p_tile.text;
            thumbImage = p_tile.theme_thumbnail;
        }
    }
    else {
        //Wall A convert to wall_A
        let temp = p_surfaceName.split(" ");
        setCurrentListID(String(temp[0]).toLowerCase() + "_" + temp[1]);
        textForMainPanel = convertFirstLetterCapital(p_tile.name) + "<br><small>" + convertFirstLetterCapital(p_tile.finish) + "</small>"
        thumbImage = p_tile.icon;


    }
    //Wall A
    let detailDiv = currentListId.find(".detail")[0];
    let imageDiv = currentListId.find("img")[0];

    $(detailDiv).html(textForMainPanel);
    $(imageDiv).attr("src", thumbImage);



}

function setCurrentListID(p_surfaceType) {
    currentListId = $("#list_" + p_surfaceType);
}


function allRightPanelContentHide() {
    $(wallAwallBwallCListing).hide(); //wall A, b , c hide
    $(wallFloorThemeContentParent).hide(); //wall A's content show
    $(themeContent).hide();
    $(wallFloorContent).hide(); //
}
//This function clicked from 2d.min.js
//When user click on any of the wall, floor, counter, paint, it will call this function.
function surfaceClickedByUser(p_surface_name) {
    allRightPanelContentHide();

    $(wallFloorThemeContentParent).show();
    $(wallFloorContent).show();
    $('#topPanel h5').text(p_surface_name);
    $("#btnProduct").addClass("top-panel-button-active");
    $("#btnLayout").removeClass("top-panel-button-active");
    $("#btnGrout").removeClass("top-panel-button-active");


    activeTab = "PRODUCT";
    showHideTabs();
    hideOpenFilters();
    //updateTopPanelText(p_surface_name);
}
function hideOpenFilters(){
    showAllFilters(false);
    $("#topPanelFilter").hide();
    $("#btnRefine").removeClass("top-panel-button-active");
}
function findRoomSurfaceUsingName(p_name) {
    let allSurfaces = currentRoom.tiledSurfaces; // Array
    // console.log("allSurfaces.length = " + allSurfaces.length);
    //console.log("p_name = " + p_name);
    for (let i = 0; i < allSurfaces.length; i++) {
        //console.log("allSurfaces[i].custom_surface_name = " + allSurfaces[i]._surfaceData.custom_surface_name);
        if (allSurfaces[i]._surfaceData.custom_surface_name == p_name) {
            return allSurfaces[i]
        }
    }
    return false;
}
function getCustomNameOfSurfaceData(p_surfaceType) {
    let cnt = 0;
    for (let k = 0; k < surfaceTypesDataTemp.length; k++) {
        if (surfaceTypesDataTemp[k] == p_surfaceType) {
            cnt++;
        }
    }
    surfaceTypesDataTemp.push(p_surfaceType);
    if (p_surfaceType !== "Themes")
        return convertFirstLetterCapital(p_surfaceType + " " + alphabets[cnt]);
    else
        return "Themes";

}
function convertFirstLetterCapital(p_str) {
    p_str = p_str.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
    return p_str;
}
