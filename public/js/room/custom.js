//var isInitialLoad = true; // Flag to track the initial load
var interval;
var topPanelCustomVisible = false;
var activeTab = "PRODUCT";
var layoutMode = "LANDSCAPE";
var topPanelTopPosition = 10;
var firstTime = true;
var searchPanelOpen = true;
var searchButtonPressed = false;
var filterButtonPressed = false;
var newLeft;
var newRight;
var panelStatusManager;
var isTablet = false;
//var searchPanelOpen = true;

document.getElementById("roomLoaderBackground").style.visibility = "hidden";

function AdjustCanvasWidthHeight() {

    var windowWidth = $(window).width();
    var windowHeight = $(window).height();

    var newWidth = windowWidth;
    var newHeight = windowHeight;

    var topPanelHeight = newHeight - 20;

    var newCanvasHeight = windowHeight;
    var newCanvasWidth = windowWidth;

    //Check if mode is landscape or portrait

    //FOR LANDSCAPE
    if (windowWidth > windowHeight) {
        layoutMode = "LANDSCAPE";

        newWidth = windowHeight * 1.78;
        newCanvasWidth = newWidth;

        topPanelTopPosition = 10;
        $("#topPanelHideBtn").css({ "top": "auto" });
        $("#container").width(windowWidth);
    }
    else {//FOR PORTRAIT
        layoutMode = "PORTRAIT";

        newHeight = windowHeight / 2;
        newWidth = windowWidth;

        newCanvasHeight = newHeight;
        newCanvasWidth = newHeight * 1.78;

        topPanelHeight = newHeight;
        topPanelTopPosition = newHeight + 20;

        //Center up / down button pressed
        $("#topPanelHideBtn").css({ "top": topPanelTopPosition - 50 });

        if (firstTime == true) {
            firstTime = false;
            hideTopPanelMainPanel();
        }
        $("#container").width(windowWidth);
        $("#container").css("overflow-x", "auto");

        setTimeout(function () {
            $("#container").scrollLeft((newCanvasWidth - windowWidth) / 2);

        }, 300);
    }

    //row top-panel-box top-panel-box-first top-panel-box-first-btn-wrap top-panel-box-cmn-br

    $("#selectd-data").css("max-height", Math.round(topPanelHeight - 20));



    $("#topPanelTilesListBox").height(topPanelHeight - 130);
    $("#grout-list").height(topPanelHeight - 250 + 120);
    $("#layout-list").height(topPanelHeight - 130 - 35);
    /*$(".top-panel-box-cmn-br").height(topPanelHeight-220);*/
    /*$(".radio-surface-pattern").height(topPanelHeight-220);*/
    $("#topPanelThemeListBox").height(topPanelHeight - 220);



    newLeft = Math.round((windowWidth - newWidth) / 2);
    newRight = Math.round((windowWidth - newLeft - newWidth)) + 6;  // Calculate new right position

    $("#roomCanvas").height(newCanvasHeight);
    $("#roomCanvas").width(newCanvasWidth);

    $("#container").css({ left: newLeft });

    //$("#topPanel").css('top',newHeight + 'px');

    $('.top-panel').css('top', topPanelTopPosition + 'px');
    $('.top-panel').css('height', topPanelHeight + 'px'); // Set height dynamically
    $("#productInfoPanel").hide();
    heightAdjust();
    $("#roomCanvas").height(newCanvasHeight);
    $("#roomCanvas").width(newCanvasWidth);

    if (topPanelCustomVisible == true) {
        setTopPanelOpenPosition(false);
    }
    else {
        setTopPanelClosedPosition(false);
    }
    //This function calling from 2d.min.js




    //$(".cn-btn").css({ right: newRight });
    //$(".share-btn-img").css({ right: newRight });
    //$(".share-div").css({ right: newRight });
    if (layoutMode == "PORTRAIT") {
        $(".back-btn").css({ left: newLeft });
        $(".cn-btn").css("right", "0px");
        $(".share-btn-img").css("right", "18px");
        $(".share-div").css("right", "18px");
    }
    else {
        
        $(".back-btn").css({ left: newLeft });
        $(".cn-btn").css({ right: newRight });
        $(".share-btn-img").css({ right: newRight + 21 });
        $(".share-div").css({ right: newRight + 21 });
       
    }
    if(currentRoom._ui.isMobileDevice()==true){
        $('.share-btn-img').show();
        $('.share-div').hide();
    }
    else{
        $('.share-btn-img').hide();
        $('.share-div').show();
    }

    showHideTabs();

}

function allLoadCompleted() {
    $(".cmn-room-btn").css('visibility', 'visible');
    $("#topPanelmainpanel").css('visibility', 'visible');
    $(".share-div").css('visibility', 'visible');
    $(".room-canvas").css('visibility', 'visible');

    showProductContent();

}

function isCanvasFullscreen() {

    return $('#container').hasClass('canvas-fullscreen'); // Replace with actual check for fullscreen

}

// function checkCanvasVisibility() {
//     var canvas = document.querySelector('.canvas-fullscreen canvas');
//     var container = document.querySelector('.canvas-fullscreen');

//     // Check
//     if (canvas && container.contains(canvas) && canvas.offsetHeight > 0 && canvas.offsetWidth > 0) {

//         $(".cmn-room-btn").css('visibility', 'visible');
//         $(".share-div").css('visibility', 'visible');
//     } else {

//         $(".cmn-room-btn").css('visibility', 'hidden');
//         $(".share-div").css('visibility', 'hidden');
//     }
// }


$(window).on('load', function () {

    if (isCanvasFullscreen()) {
        $(".cmn-room-btn").css('visibility', 'visible');
        $(".share-div").css('visibility', 'visible');

        AdjustCanvasWidthHeight();
    }
    // checkCanvasVisibility();

    interval = setInterval(function () {

        if ($("#sourceLoadProgressBarContainer").length > 0) {
        }
        else {
            //alert($("#sourceLoadProgressBarContainer").length);
            clearInterval(interval);
            AdjustCanvasWidthHeight();

            setTimeout(function () {
                allLoadCompleted();
                $(".partOfProductTabContent-wrap").show(); // Show the content wrapper
                $(".back-btn").css({ left: newLeft });
                $(".cn-btn").css("right", layoutMode === "PORTRAIT" ? "0px" : newRight);
                $(".share-btn-img").css("right", layoutMode === "PORTRAIT" ? "18px" : newRight + 21);
                $(".share-div").css("right", layoutMode === "PORTRAIT" ? "26px" : newRight + 21);




            }, 19);
        }
    }, 500);

    $("body").on("click", '.filter-click', function () {
        alert('hello from binded function call');
    });



    $(document).on('click', '.filter-click', function () {
        alert("Click");
        //console.log($(this).attr("id"));
    });
});

$(window).on('resize', function () {
    AdjustCanvasWidthHeight();
});

//This function calling from 2d.min.js
function openTopPanel() {
    topPanelCustomVisible = true;
    setTopPanelOpenPosition(true);
}
function closeTopPanel() {
    topPanelCustomVisible = false;
    setTopPanelClosedPosition(true);
}

function setTopPanelOpenPosition(p_animation_required) {
    if (layoutMode == "PORTRAIT") {
        if (p_animation_required == true) {
            window.$('#topPanel').animate({ 'right': 0, 'top': topPanelTopPosition }, 'fast');
            $("#topPanelHideBtn").animate({ "top": topPanelTopPosition - 50 });
        }
        else {
            window.$('#topPanel').css({ 'right': 0, 'top': topPanelTopPosition });
            $("#topPanelHideBtn").css({ "top": topPanelTopPosition - 50 });
        }
    }
    else {
        if (p_animation_required == true) {
            window.$('#topPanel').animate({ 'right': 0 }, 'fast');
        }
        else {
            window.$('#topPanel').css({ 'right': 0 });
        }
    }
    window.$('#topPanelHideIcon').removeClass('glyphicon glyphicon-menu-left').addClass('glyphicon glyphicon-menu-right');
}
function setTopPanelClosedPosition(p_animation_required,) {
    var width = window.$('#topPanel').width();
    var height = $(window).height();

    if (layoutMode == "PORTRAIT") {
        if (p_animation_required == true) {
            window.$('#topPanel').animate({ 'top': (height - 20) }, 'fast');
            $("#topPanelHideBtn").animate({ "top": height - 80 });
        }
        else {
            window.$('#topPanel').css({ 'top': (height - 20) });
            $("#topPanelHideBtn").css({ "top": height - 80 });
        }
    }
    else { //landscape
        if (p_animation_required == true) {
            window.$('#topPanel').animate({ 'right': -(width + 10) }, 'fast');
        }
        else {
            window.$('#topPanel').css({ 'right': -(width + 10) });
        }
    }
    window.$('#topPanelHideIcon').removeClass('glyphicon glyphicon-menu-right').addClass('glyphicon glyphicon-menu-left');
}
//END

// $("#topPanelHideBtn").on('click', function () {
//   var topPanel = $("#topPanel");

//   // Check if the right property is 0
//   if (topPanel.css("right") === "0px") {
//     topPanel.addClass("panelclose"); // Add the class if right is 0
//   } else {
//     topPanel.removeClass("panelclose"); // Remove the class if right is not 0
//   }
// });
$('#topPanelmainpanel').on('click', function () {
    hideTopPanelMainPanel();
});

function hideTopPanelMainPanel() {
    $('#topPanel').show(); // Toggle visibility of the topPanel
    $('#topPanelmainpanel').hide();
}




$("#btnProduct").addClass("top-panel-button-active");

$('#btnProduct').on('click', function () {
    activeTab = "PRODUCT";
    showHideTabs();
});

$('#btnLayout').on('click', function () {
    activeTab = "LAYOUT";
    showHideTabs();
});

$('#btnGrout').on('click', function () {
    activeTab = "GROUT";
    showHideTabs();
    setTimeout(function () {

        AdjustCanvasWidthHeight();
    }, 0);
});

function setPanelToggleStatus(p_panelIdorClass, p_buttonIdorClass) {
    if ($(p_panelIdorClass).css('display') == 'none') {
        if (p_buttonIdorClass) {
            $(p_panelIdorClass).show();
            $(p_buttonIdorClass).addClass('top-panel-button-active');
            heightAdjust();
        }
        return false;
    }
    if (p_buttonIdorClass) {
        $(p_panelIdorClass).hide();
        $(p_buttonIdorClass).removeClass('top-panel-button-active');
        heightAdjust();
    }
    return true;
}
function heightAdjust() {
    setTimeout(function () {
        if ($(".search-filter-panel-box").height() < 10) {
            $(".search-filter-panel-box").hide();
        }
        else {
            $(".search-filter-panel-box").show();
        }

        $(".search-filter-panel-box").animate({ "top": $('#topPanel').offset().top - $(".search-filter-panel-box").height() - 15 });
    }, 10);

}

function showHideTabs() {

    //IF responsive and search icon pressed then
    //This function calling from 2d.min.js


    switch (activeTab) {
        case "PRODUCT":
            showProductContent();
            break;
        case "LAYOUT":
            showLayoutContent();
            break;
        case "GROUT":
            showGroutContent();
            break;
    }
    if (layoutMode == "LANDSCAPE") {
        $(".partOfProductTabContent").show();
    }


    // setTimeout(function() {
    //     AdjustCanvasWidthHeight();  // Recalculate canvas width/height
    // }, 100);
}





function showProductContent() {
    $('#topPanelGrout').hide();
    $('#topPanelLayout').hide();
    $('.radio-surface-rotation-wrap').show();


    $(".partOfProductTabContent").show();
    $('.partOfProductTabContent-wrap').show();

    if(currentRoom._ui.isMobileDevice()==true){
        $(".partOfProductTabButtons").show();
        setPanelToggleStatus('.serach-pad-set', '#searchIconToggle');
        setPanelToggleStatus('.filterContentPanel', '#sliderIconToggle');
    }
    else{
        $(".partOfProductTabButtons").hide();
    }

}
//this._room.currentTiledSurface
function showLayoutContent() {
    $('#topPanelGrout').hide();
    $(".partOfProductTabButtons").hide();
    $(".partOfProductTabContent").hide();
    $('.radio-surface-rotation-wrap').hide();
    $('.partOfProductTabContent-wrap').hide();

    //$("#topPanelContentSurfaceTabGroutSizeBody").hide();
    $('.search-pad-set').hide();
    $('#topPanelLayout').show();
}
function showGroutContent() {
    $('#topPanelLayout').hide();
    $(".partOfProductTabButtons").hide();
    $(".partOfProductTabContent").hide();
    $('#topPanelGrout').css('display', 'block');
    $('#topPanelGrout').show();
    $('.radio-surface-rotation-wrap').hide();
    $('.partOfProductTabContent-wrap').hide();
    //$("#otpPanelContentSurfaceTabGroutSizeBody").show();
}

$('#grout-predefined-color .-btn').on('click', function () {

    // Remove 'active' class from all buttons
    $('#grout-predefined-color .-btn').removeClass('active');

    // Add 'active' class to the clicked button
    $(this).addClass('active');
});


$('.share-btn-img').on('click', function () {
    $('.share-div').css('display', 'flex');
    $(this).hide();
    $('.share-btn-close').show();
});
$('.share-btn-close').on('click', function () {

    $('.share-div').css('display', 'none');
    $(this).hide();
    $('.share-btn-img').show();
});


$('.open-panel').on('click', function () {

    $('#selectd-data').hide();
    $('#slected-panel').show();

});

$('.selcte-data-btn').on('click', function () {

    $('#selectd-data').show();
    $('#slected-panel').hide();


});

$('.cartpanelclose').on('click', function () {
    $("body").css('overflow', "hidden");

});


$('.top-panel-search').on('click', function () {
    // Toggle the class .top-panel-search-active
    $(this).toggleClass('top-panel-search-active');

    if ($('.search-filter-panel-box').css('display') === 'none') {
        $('.search-filter-panel-box').show();

    } else {
        $('.search-filter-panel-box').hide();
    }
});

$('.search-filter-panel-box').on('click', function (e) {
    e.stopPropagation();
});

$('#btnProduct').on('click', function () {
    activeTab = "PRODUCT";
    showHideTabs();

    var optionText = $('#optionText').text().trim();
    var firstWord = optionText.split(' ')[0].toLowerCase();

    if (firstWord === "counter" || firstWord === "paint") {
        $('.top-panel-search').hide();
    } else {
        $('.top-panel-search').show();
    }
});

/**********************************************************************

 This function is calling from 2d.min.js when user press Filter button

 This function's role
 - This function call when user press filter button on front side
 - Find the available filters Category
 - Create dummy navigation and click the first one

 This function do following
 - Get the filter block craeated by original code in 2d.min.js
 - Create separate dummy navigation HTML to overcome existing structure issue
 - Add html into the new navigation panel
 - Show navigation panel

 *********************************************************************/

var allnavLinks = [];
function customFilterManagement() {
    $("#topPanelNavFilter").empty();
    allnavLinks = [];

    var filterBlocks = $("#topPanelFilter").find(".filter-block");

    var htmlStr = "<ul>";
    var clickedCategoryName = "";

    for (var i = 0; i < filterBlocks.length; i++) {

        var filterBlock = filterBlocks.eq(i);

        if ($(filterBlock).css('display') == 'none') {
            /*
            There are many object which are hidden if those tiles are not present.
            We do not consider those titles and remain display none as it is
            */
        }
        else {

            //Get the title of the Category
            var titleHeader = filterBlock.find(".-header-title");
            var filterNameID = titleHeader.attr("id");
            var splitter = filterNameID.split("_");
            //-headerId_wall_Finishes
            var filterName = String(splitter[1] + "_" + splitter[2]).toLowerCase();

            if (clickedCategoryName == "") {
                clickedCategoryName = filterName;
            }
            allnavLinks.push({ "navName": filterName });
            $(filterBlock).attr("id", filterName);
            //Create list (clickable horizontal category list)
            htmlStr += "<li onclick=clickFilterCategory('" + filterName + "',this) id='filterclick_" + filterName + "' class='filter-click' style='text-transform:capitalize;'>" + splitter[2] + "</li>"; ``
        }

    }
    htmlStr += "</ul>";

    $("#topPanelNavFilter").html(htmlStr);

    clickFilterCategory(clickedCategoryName);

    showAllFilters(true);
}

/***********************************************************************
 This function is calling from customFilterManagement

 This function do following
 - Hide original Category listing (made by owner of code)
 - Open the content of the clicked category
 - Remove existing active class and add newly clicked category
 - Keep height of the remaining div to 0 to avoid height movement during clicking
 - Reset height and adjust height of newly added content
 ***********************************************************************/

function clickFilterCategory(p_filterName) {
    //this._body.id = '-body_' + this.surface + '_' + Locale.lang(this.name);

    //console.log("currentTiledSurface = " + currentRoom.currentTiledSurface);

    $('.-body').hide();
    $('#-body_' + p_filterName).css({ "display": "flex" });

    $(".filter-click").removeClass("filter-click-active");
    $("#filterclick_" + p_filterName).addClass("filter-click-active");

    $("#filterclick_" + p_filterName).data("allUnChecked")

    $(".filter-block").css({ "height": "0px", "margin": "0px" });


    $('#-body_' + p_filterName).parent(".filter-block").css({ "height": "auto", "margin": "null" });
    $("#topPanelFilter").show();

    heightAdjust();
}

/***********************************************************************
 This function is calling from 2d.min.js

 This function Role
 - User click on the checkboxes of category
 - This function count how many checkboxes checked and count total number

 This function do following
 - Total length of the checked checkboxes
 - Count number and return
 ***********************************************************************/
function totalFilterCheckboxesChecked() {
    var checked = $(".checkboxClass");
    var totalCheckboxes = checked.length;
    var count = 0;
    for (var i = 0; i < totalCheckboxes; i++) {
        var obj = $(checked).eq(i);
        if ($(obj).prop("checked")) {
            count++;
        }
    }
    //checkAllBlankCategories();
    return count;
}
function checkAllBlankCategories(p_category,p_wallorfloor) {

    //-body_wall_Finishes
    var checkBoxes =$("#"+p_wallorfloor+"_"+p_category).find(".checkboxClass");

    var totalCheckboxes = checkBoxes.length;
    var count = 0;
    for (var j = 0; j < totalCheckboxes; j++) {
        var checkBoxIndividual = $(checkBoxes).eq(j);
        if ($(checkBoxIndividual).prop("checked")) {
            count++;
        }
    }
    return count;
}


$('#btnRefine').on('click', function () {
    //$('#topPanelNavFilter').toggle(); // Toggle visibility of the element

});

function showAllFilters(p_show) {
    if (p_show == true) {
        $("#topPanelNavFilter").show();
    }
    else {
        $("#topPanelNavFilter").hide();
    }
}

/*this._body.id =
*/

$('#roomCanvas').on('click', function () {
    var roomCanvasTitle = $('#roomCanvas').attr('title').trim();  // Get the title and trim any extra spaces
    var titleWords = roomCanvasTitle.split(' ');  // Split the title by spaces
    var firstTwoWords = titleWords.slice(0, 2).join(' ');  // Take the first two words and join them with a space

    // Check if the title contains "change counter" or "change paint"
    if (firstTwoWords.toLowerCase() === "change counter" || firstTwoWords.toLowerCase() === "change paint") {
        $('.serach-pad-set').hide();  // Hide the search panel
    } else {
        $('.serach-pad-set').show();  // Show the search panel
    }
});
