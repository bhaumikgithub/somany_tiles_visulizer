var isInitialLoad = true; // Flag to track the initial load
var interval;
var topPanelCustomVisible = false;
document.getElementById("roomLoaderBackground").style.visibility = "hidden";

function AdjustCanvasWidthHeight() {

    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var newWidth = windowWidth;
    var newHeight = windowHeight;
    var topPanelHeight = newHeight - 20;
    var topPanelTopPosition = 10;

    if(windowWidth > windowHeight){//landscape
        newWidth = windowHeight * 1.78;
    }
    else{//Portrait
        newHeight = windowWidth / 1.78;
        topPanelHeight = windowHeight - newHeight - 20;
        topPanelTopPosition = newHeight + 20;
    }

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

    $('.top-panel').attr('style', 'top: '+topPanelTopPosition + 'px!important');
    $('.top-panel').css('height', topPanelHeight + 'px'); // Set height dynamically
    $("#productInfoPanel").hide();

    var topAreaHeight = $(".top-panel-box").height() + $(".serch-box-wrap").height() + $(".top-panel-box-first").height();
    var toppanellistboxheight = topPanelHeight - topAreaHeight;
    console.log("topPanelHeight = " + topPanelHeight);
    console.log("topAreaHeight = " + topAreaHeight);

    $("#topPanelTilesListBox").height(topPanelHeight - topAreaHeight);

    if (isInitialLoad) {
        if (windowWidth > 1300) {
            $(".cn-btn").css("margin-right", "26px");
            $(".share-btn-img").css("margin-right", "26px");
            $(".share-div").css("margin-right", "26px");

        }
        isInitialLoad = false; // Set flag to false after initial load
    } else {
        $(".cn-btn").css("margin-right", "15px"); // Remove margin-right for resize
        $(".share-btn-img").css("margin-right", "15px");
        $(".share-div").css("margin-right", "26px");

    }
}
function allLoadCompleted(){
    $(".cmn-room-btn").css('visibility', 'visible');
    $("#topPanelmainpanel").css('visibility', 'visible');
    $(".share-div").css('visibility', 'visible');
    $(".room-canvas").css('visibility', 'visible');

}


$(window).on('load', function() {

    interval = setInterval(function(){
        if($("#sourceLoadProgressBarContainer").length>0){
        }
        else{
            clearInterval(interval);
            AdjustCanvasWidthHeight();

            setTimeout(function(){
                allLoadCompleted()},19);
        }
    },500);


});

$(window).on('resize', function() {
    AdjustCanvasWidthHeight();
});

//This function calling from 2d.min.js
function openTopPanel(){
    topPanelCustomVisible = true;
    window.$('#topPanel').animate({ 'right': 0 }, 'fast');
    window.$('#topPanelHideIcon').removeClass('glyphicon glyphicon-menu-left').addClass('glyphicon glyphicon-menu-right');
}
function closeTopPanel(){
    topPanelCustomVisible = false;
    var width = window.$('#topPanel').width();
    window.$('#topPanel').animate({ 'right': -(width + 10) }, 'fast');
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

    $('#topPanel').show(); // Toggle visibility of the topPanel
    $(this).hide();

});


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

let lastRoomCanvasTitle = ''; // Variable to store the last room-canvas title

// Function to update the h5 element text based on the current mode
/*
function updateTopPanelText() {
  if (lastRoomCanvasTitle === 'Change wall') {
    const text = wallSelections.length > 0 ? wallSelections[wallSelections.length - 1] : 'Wall:';
    $('#topPanel h5').text(text);
  } else if (lastRoomCanvasTitle === 'Change floor') {
    const text = floorSelections.length > 0 ? floorSelections[floorSelections.length - 1] : 'Floor:';
    $('#topPanel h5').text(text);
  } else if (lastRoomCanvasTitle === 'Change counter') {
    const text = counterSelections.length > 0 ? counterSelections[counterSelections.length - 1] : 'Counter:';
    $('#topPanel h5').text(text);
  } else if (lastRoomCanvasTitle === 'Change ceiling') {
    const text = ceilingSelections.length > 0 ? ceilingSelections[ceilingSelections.length - 1] : 'Ceiling:';
    $('#topPanel h5').text(text);
  } else if (lastRoomCanvasTitle === 'Change theme') {
    const text = themeSelections.length > 0 ? themeSelections[themeSelections.length - 1] : 'Theme:';
    $('#topPanel h5').text(text);
  } else if (lastRoomCanvasTitle === 'Change paint') {
    const text = themeSelections.length > 0 ? themeSelections[themeSelections.length - 1] : 'Paint:';
    $('#topPanel h5').text(text);
  } else {
    $('#topPanel h5').text('Choose Tiles');
  }
}*/
//updateTopPanelText();





// Store the last room-canvas title and update the class on room-canvas click
$('.room-canvas').on('click', function () {
    var title = $(this).attr('title');

    // Store the title in the variable
    lastRoomCanvasTitle = title;
    console.log('Last roomCanvas title set to:', lastRoomCanvasTitle);

});



$("#btnProduct").addClass("top-panel-button-active");

$('#btnProduct').on('click', function () {
    $('#topPanelTilesListBox').show();
    $('#topPanelLayout').hide();
    $('#topPanelGrout').hide();


});
$('#btnLayout').on('click', function () {
    $('#topPanelLayout').show();
    $('#topPanelTilesListBox').hide();
    $('#topPanelGrout').hide();
    $('.radio-surface-rotation').hide();



});
$('#btnGrout').on('click', function () {
    $('#topPanelGrout').show();
    $('#topPanelTilesListBox').hide();
    $('#topPanelLayout').hide();
    $('.radio-surface-rotation').hide();

});


$('#grout-predefined-color .-btn').on('click', function () {

    // Remove 'active' class from all buttons
    $('#grout-predefined-color .-btn').removeClass('active');

    // Add 'active' class to the clicked button
    $(this).addClass('active');
});


$('.share-btn-img').on('click', function () {
    $('.share-div').css('display','flex');
    $(this).hide();
    $('.share-btn-close').show();
});
$('.share-btn-close').on('click', function () {

    $('.share-div').css('display','none');
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
