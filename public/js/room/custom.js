var isInitialLoad = true; // Flag to track the initial load
var interval;
var topPanelCustomVisible = false;
var activeTab = "PRODUCT";
var layoutMode = "";
var topPanelTopPosition = 10;
var firstTime = true;

document.getElementById("roomLoaderBackground").style.visibility = "hidden";

function AdjustCanvasWidthHeight() {

    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var newWidth = windowWidth;
    var newHeight = windowHeight;
    var topPanelHeight = newHeight - 20;


    if (windowWidth > windowHeight) {//landscape
        newWidth = windowHeight * 1.78;
        layoutMode = "LANDSCAPE";
        topPanelTopPosition = 10;
        $("#topPanelHideBtn").css({ "left": "auto", "top": "auto" });

    }
    else {//Portrait
        newHeight = windowWidth / 1.78;
        topPanelHeight = windowHeight - newHeight - 20;
        topPanelTopPosition = newHeight + 20;
        layoutMode = "PORTRAIT";
        $("#topPanelHideBtn").css({ "left": windowWidth / 2 + 50, "top": topPanelTopPosition - 50 });
        if (firstTime == true) {
            firstTime = false;
            hideTopPanelMainPanel();
        }
        console.log("topPanelCustomVisible = " + topPanelCustomVisible);
    }

    //row top-panel-box top-panel-box-first top-panel-box-first-btn-wrap top-panel-box-cmn-br

    $("#selectd-data").css("max-height", Math.round(topPanelHeight - 20));
    $("#topPanelTilesListBox").height(topPanelHeight - 220);
    /*$(".top-panel-box-cmn-br").height(topPanelHeight-220);*/
    /*$(".radio-surface-pattern").height(topPanelHeight-220);*/
    $("#topPanelThemeListBox").height(topPanelHeight - 220);

    var newLeft = Math.round((windowWidth - newWidth) / 2);
    var newRight = Math.round((windowWidth - newLeft - newWidth));  // Calculate new right position

    $("#roomCanvas").height(newHeight);
    $("#roomCanvas").width(newWidth);

    $("#container").css({ left: newLeft });

    $(".back-btn").css({ left: newLeft });
    $(".cn-btn").css({ right: newRight });
    $(".share-btn-img").css({ right: newRight });
    $(".share-div").css({ right: newRight });

    //$("#topPanel").css('top',newHeight + 'px');

    $('.top-panel').css('top', topPanelTopPosition + 'px');
    $('.top-panel').css('height', topPanelHeight + 'px'); // Set height dynamically
    $("#productInfoPanel").hide();

    if (isInitialLoad) {
        if (windowWidth > 1300) {
            $(".cn-btn").css("margin-right", "26px");
            $(".share-btn-img").css("margin-right", "32px");
            $(".share-div").css("margin-right", "26px");

        }
        isInitialLoad = false; // Set flag to false after initial load
    } else {
        $(".cn-btn").css("margin-right", "15px"); // Remove margin-right for resize
        $(".share-btn-img").css("margin-right", "21px");
        $(".share-div").css("margin-right", "26px");
    }

    if(topPanelCustomVisible==true){
        setTopPanelOpenPosition(false);
    }
    else{
        setTopPanelClosedPosition(false);
    }
    //This function calling from 2d.min.js

    $(".cn-btn").css("margin-right", "15px"); // Remove margin-right for resize
    $(".share-btn-img").css("margin-right", "15px");
    $(".share-div").css("margin-right", "14px");


    /* if (isInitialLoad) {
       if (windowWidth > 1300) {
         $(".cn-btn").css("margin-right", "26px");
         $(".share-btn-img").css("margin-right", "26px");
         $(".share-div").css("margin-right", "26px");

       }
       isInitialLoad = false; // Set flag to false after initial load
     } else {
       $(".cn-btn").css("margin-right", "15px"); // Remove margin-right for resize
       $(".share-btn-img").css("margin-right", "15px");
       $(".share-div").css("margin-right", "14px");

     }*/
}

function allLoadCompleted() {
    $(".cmn-room-btn").css('visibility', 'visible');
    $("#topPanelmainpanel").css('visibility', 'visible');
    $(".share-div").css('visibility', 'visible');
    $(".room-canvas").css('visibility', 'visible');

}


$(window).on('load', function () {

    interval = setInterval(function () {
        if ($("#sourceLoadProgressBarContainer").length > 0) {
        }
        else {
            clearInterval(interval);
            AdjustCanvasWidthHeight();

            setTimeout(function () {
                allLoadCompleted()
            }, 19);
        }
    }, 500);


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

function setTopPanelOpenPosition(p_animation_required){
    if (layoutMode == "PORTRAIT") {
        if(p_animation_required==true){
            window.$('#topPanel').animate({ 'right': 0, 'top': topPanelTopPosition }, 'fast');
            $("#topPanelHideBtn").animate({ "top": topPanelTopPosition - 50 });
        }
        else{
            window.$('#topPanel').css({ 'right': 0, 'top': topPanelTopPosition });
            $("#topPanelHideBtn").css({ "top": topPanelTopPosition - 50 });
        }
    }
    else {
        if(p_animation_required==true){
            window.$('#topPanel').animate({ 'right': 0 }, 'fast');
        }
        else{
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
        else{
            window.$('#topPanel').css({ 'top': (height - 20) });
            $("#topPanelHideBtn").css({ "top": height - 80 });
        }
    }
    else { //landscape
        if (p_animation_required == true) {
            window.$('#topPanel').animate({ 'right': -(width + 10) }, 'fast');
        }
        else{
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


let wallCount = 0;
let floorCount = 0;
let counterCount = 0;  // Counter count
let ceilingCount = 0; // Ceiling count
let themeCount = 0;   // Theme count
let paintCount = 0;   // Theme count

let wallSelections = [];   // Array to store wall selections
let floorSelections = [];  // Array to store floor selections
let counterSelections = []; // Array to store counter selections
let ceilingSelections = []; // Array to store ceiling selections
let themeSelections = [];   // Array to store theme selections
let paintSelections = [];   // Array to store theme selections




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

});

function showHideTabs() {
    $('#topPanelTilesListBox').hide();
    $('#topPanelLayout').hide();
    $('#topPanelGrout').hide();
    $(".serch-box-wrap").hide();
    $('.radio-surface-rotation').hide();

    switch (activeTab) {
        case "PRODUCT":
            $(".serch-box-wrap").show();
            $('.radio-surface-rotation').show();
            $('#topPanelTilesListBox').show();

            break;
        case "LAYOUT":
            $('#topPanelLayout').show();
            break;
        case "GROUT":
            $('#topPanelGrout').show();
            $("#topPanelContentSurfaceTabGroutSizeBody").show();
            break;
    }

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
    // Show the info panel
    $('#selectd-data').hide();
    $('#slected-panel').show();

});

$('.selcte-data-btn').on('click', function () {
    // Show the info panel
    $('#selectd-data').show();
    $('#slected-panel').hide();

});

$('.cartpanelclose').on('click', function () {
    $("body").css('overflow', "hidden");

});
