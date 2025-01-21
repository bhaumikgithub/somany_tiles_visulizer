var isInitialLoad = true; // Flag to track the initial load

function AdjustCanvasWidthHeight() {
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var newWidth = windowHeight * 1.78;
    var newLeft = Math.round((windowWidth - newWidth) / 2);
    var newRight = Math.round((windowWidth - newLeft - newWidth));  // Calculate new right position
    var canvasHeight = windowHeight;

    $("#roomCanvas").height(windowHeight);
    $("#roomCanvas").width(newWidth);

    $("#container").css({ left: newLeft });

    $(".back-btn").css({ left: newLeft });
    $(".cn-btn").css({ right: newRight });
    $(".share-btn-img").css({ right: newRight });
    $(".share-div").css({ right: newRight });



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

function applyCanvasAdjustments() {
    if ($(window).width() > 500) { // Only execute if screen width > 500px
        AdjustCanvasWidthHeight();

    }
}
function setTopPanelHeight() {
    const viewportHeight = $(window).height(); // Get viewport height using jQuery
    $('.top-panel').css('height', viewportHeight - 20 + 'px'); // Set height dynamically
}
$(window).on('load', function() {

    applyCanvasAdjustments();
    setTopPanelHeight();
});

$(window).on('resize', function() {
    applyCanvasAdjustments();
    AdjustCanvasWidthHeight();

});


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

function selectedpaneltext() {
    /*
    if (lastRoomCanvasTitle === 'Change wall' && wallCount === 0) {
      $('#slected-panel-data p').text('Please first choose data');
    } else if (lastRoomCanvasTitle === 'Change floor' && floorCount === 0) {
      $('#slected-panel-data p').text('Please first choose data');
    } else if (lastRoomCanvasTitle === 'Change counter' && counterCount === 0) {
      $('#slected-panel-data p').text('Please first choose data');
    } else if (lastRoomCanvasTitle === 'Change ceiling' && ceilingCount === 0) {
      $('#slected-panel-data p').text('Please first choose data');
    } else if (lastRoomCanvasTitle === 'Change theme' && themeCount === 0) {
      $('#slected-panel-data p').text('Please first choose data');
    }
      */
}
selectedpaneltext();

// Track clicks on any li inside #topPanelTilesListUl
/*
$('#topPanelTilesListUl').on('click', 'li', function () {
  if ($('#topPanelTilesListUl').hasClass('wallul') && lastRoomCanvasTitle === 'Change wall') {
    wallCount++;
    let wallLetter = String.fromCharCode(64 + wallCount); // Convert wallCount to letter
    wallSelections.push('Wall ' + wallLetter); // Add to wall selections
    console.log('Wall Click Count:', wallCount);
    updateTopPanelText(); // Update the top panel to show WALL A, WALL B, etc.
  } else if ($('#topPanelTilesListUl').hasClass('floorul') && lastRoomCanvasTitle === 'Change floor') {
    floorCount++;
    let floorLetter = String.fromCharCode(64 + floorCount); // Convert floorCount to letter
    floorSelections.push('Floor ' + floorLetter); // Add to floor selections
    console.log('Floor Click Count:', floorCount);
    updateTopPanelText();
  } else if ($('#topPanelTilesListUl').hasClass('counterul') && lastRoomCanvasTitle === 'Change counter') {
    counterCount++;
    let counterLetter = String.fromCharCode(64 + counterCount); // Convert counterCount to letter
    counterSelections.push('Counter ' + counterLetter); // Add to counter selections
    console.log('Counter Click Count:', counterCount);
    updateTopPanelText();
  } else if ($('#topPanelTilesListUl').hasClass('ceilingul') && lastRoomCanvasTitle === 'Change ceiling') {
    ceilingCount++;
    let ceilingLetter = String.fromCharCode(64 + ceilingCount); // Convert ceilingCount to letter
    ceilingSelections.push('Ceiling ' + ceilingLetter); // Add to ceiling selections
    console.log('Ceiling Click Count:', ceilingCount);
    updateTopPanelText();
  } else if ($('#topPanelTilesListUl').hasClass('themeul') && lastRoomCanvasTitle === 'Change theme') {
    themeCount++;
    let themeLetter = String.fromCharCode(64 + themeCount); // Convert themeCount to letter
    themeSelections.push('Theme ' + themeLetter); // Add to theme selections
    console.log('Theme Click Count:', themeCount);
    updateTopPanelText();
  } else if ($('#topPanelTilesListUl').hasClass('themeul') && lastRoomCanvasTitle === 'Change paint') {
    paintCount++;
    let paintLetter = String.fromCharCode(64 + themeCount); // Convert themeCount to letter
    paintSelections.push('Theme ' + paintLetter); // Add to theme selections
    console.log('Paint Click Count:', paintCount);
    updateTopPanelText();
  } else {
    console.log("Invalid action: Ensure the correct room-canvas is selected.");
  }
});*/

// Store the last room-canvas title and update the class on room-canvas click
$('.room-canvas').on('click', function () {
    var title = $(this).attr('title');

    // Store the title in the variable
    lastRoomCanvasTitle = title;
    console.log('Last roomCanvas title set to:', lastRoomCanvasTitle);

    // Update the h5 element or perform other actions based on the title
    /*
    if (title === 'Change wall') {
      $('#topPanel h5').text('Wall');
      $('#topPanelTilesListUl').addClass('wallul').removeClass('floorul counterul ceilingul themeul');
    } else if (title === 'Change floor') {
      $('#topPanel h5').text('Floor124');
      $('#topPanelTilesListUl').addClass('floorul').removeClass('wallul counterul ceilingul themeul');
    } else if (title === 'Change counter') {
      $('#topPanel h5').text('Counter');
      $('#topPanelTilesListUl').addClass('counterul').removeClass('wallul floorul ceilingul themeul');
    } else if (title === 'Change ceiling') {
      $('#topPanel h5').text('Ceiling');
      $('#topPanelTilesListUl').addClass('ceilingul').removeClass('wallul floorul counterul themeul');
    } else if (title === 'Change theme') {
      $('#topPanel h5').text('Theme');
      $('#topPanelTilesListUl').addClass('themeul').removeClass('wallul floorul counterul ceilingul');
    }
      */

    // Update the topPanelText after setting the class
    //updateTopPanelText();
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
