var isInitialLoad = true; // Flag to track the initial load
var interval;
var topPanelCustomVisible = false;
var activeTab = "PRODUCT";
var layoutMode = "";
var topPanelTopPosition = 10;
var firstTime = true;
var searchPanelOpen = true;
$(".cmn-room-btn").css('visibility', 'hidden');
document.getElementById("roomLoaderBackground").style.visibility = "hidden";

function AdjustCanvasWidthHeight() {

    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var newWidth = windowWidth;
    var newHeight = windowHeight;
    var topPanelHeight = newHeight - 20;
    var newCanvasHeight = windowHeight;
    var newCanvasWidth = windowWidth;

    if (windowWidth > windowHeight) {//landscape
        layoutMode = "LANDSCAPE";

        newWidth = windowHeight * 1.78;
        newCanvasWidth = newWidth;

        topPanelTopPosition = 10;
        $("#topPanelHideBtn").css({ "left": "auto", "top": "auto" });
        $("#container").width(windowWidth);
    }
    else {//Portrait
        layoutMode = "PORTRAIT";

        newHeight = windowHeight / 2;
        newWidth = windowWidth;

        newCanvasHeight = newHeight;
        newCanvasWidth = newHeight * 1.78;

        topPanelHeight = newHeight;
        topPanelTopPosition = newHeight + 20;

        $("#topPanelHideBtn").css({ "left": windowWidth / 2 + 50, "top": topPanelTopPosition - 50 });
        if (firstTime == true) {
            firstTime = false;
            hideTopPanelMainPanel();
        }
        $("#container").width(windowWidth);
        $("#container").css("overflow-x", "auto");
        setTimeout(setTimeout(function () {
            $("#container").scrollLeft((newCanvasWidth - windowWidth) / 2);
        }, 300));
    }

    //row top-panel-box top-panel-box-first top-panel-box-first-btn-wrap top-panel-box-cmn-br

    $("#selectd-data").css("max-height", Math.round(topPanelHeight - 20));

    $("#topPanelTilesListBox").height(topPanelHeight - 220);
    /*$(".top-panel-box-cmn-br").height(topPanelHeight-220);*/
    /*$(".radio-surface-pattern").height(topPanelHeight-220);*/
    $("#topPanelThemeListBox").height(topPanelHeight - 220);

    var newLeft = Math.round((windowWidth - newWidth) / 2);
    var newRight = Math.round((windowWidth - newLeft - newWidth)) + 6;  // Calculate new right position

    $("#roomCanvas").height(newCanvasHeight);
    $("#roomCanvas").width(newCanvasWidth);

    $("#container").css({ left: newLeft });

    //$("#topPanel").css('top',newHeight + 'px');

    $('.top-panel').css('top', topPanelTopPosition + 'px');
    $('.top-panel').css('height', topPanelHeight + 'px'); // Set height dynamically
    $("#productInfoPanel").hide();

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
        $(".share-div").css("right", "26px");
    }
    else {
        $(".back-btn").css({ left: newLeft });
        $(".cn-btn").css({ right: newRight });
        $(".share-btn-img").css({ right: newRight + 6 });
        $(".share-div").css({ right: newRight });
    }

}

function allLoadCompleted() {
    $(".cmn-room-btn").css('visibility', 'visible');
    $("#topPanelmainpanel").css('visibility', 'visible');
    $(".share-div").css('visibility', 'visible');
    $(".room-canvas").css('visibility', 'visible');

}


$(window).on('load', function () {

    $(".cmn-room-btn").css('visibility', 'hidden');
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

function showHideSearchPanel() {

    if ($('.serach-pad-set').css('display') == 'none') {
        console.log("diplay none");
        $(".serach-pad-set").show();
    }
    else {
        console.log("diplay block");
        $(".serach-pad-set").hide();
    }
}
function showHideFilterPanel(){
    if ($('#topPanelFilter').css('display') == 'none') {
        console.log("diplay none");
        $("#topPanelFilter").show();
        return true;
    }
    else {
        console.log("diplay block");
        $("#topPanelFilter").hide();
        return false;
    } 
}
function showHideTabs(p_pressedType) {

    //IF responsive and search icon pressed then
    //This function calling from 2d.min.js

     var roomCanvasTitle = $('#roomCanvas').attr('title').trim();  // Get the title and trim any extra spaces
     var titleWords = roomCanvasTitle.split(' ');  // Split the title by spaces
    var firstTwoWords = titleWords.slice(0, 2).join(' ');  // Take the first two words and join them with a space

    switch (activeTab) {
        case "PRODUCT":
            $('#topPanelGrout').hide();
            $('#topPanelLayout').hide();
            $("#topPanelContentSurfaceTabGroutSizeBody").hide();
            if (firstTwoWords.toLowerCase() === "change counter" || firstTwoWords.toLowerCase() === "change paint") {
                $('.top-panel-search').hide();  // Hide the search panel
            } else {
                $('.top-panel-search').show();  // Show the search panel
            }
        
            $('.radio-surface-rotation').show();
            $('#topPanelTilesListBox').show();
            if (layoutMode == "PORTRAIT") {
                $(".partOfProductTab").show();
            }
            else {
                $(".partOfProductTab").hide();
            }
            break;
        case "LAYOUT":
            $('#topPanelGrout').hide();
            $('#topPanelTilesListBox').hide();
            
            $(".top-panel-search").hide();
            $('.radio-surface-rotation').hide();
            $(".partOfProductTab").hide();
            $("#topPanelContentSurfaceTabGroutSizeBody").hide();

            $('#topPanelLayout').show();

            break;
        case "GROUT":
            $('#topPanelLayout').hide();
            $('#topPanelTilesListBox').hide();
            $(".top-panel-search").hide();
            $(".partOfProductTab").hide();

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

