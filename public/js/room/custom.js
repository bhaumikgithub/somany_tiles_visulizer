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

let wallSelections = [];   // Array to store wall selections
let floorSelections = [];  // Array to store floor selections
let counterSelections = []; // Array to store counter selections
let ceilingSelections = []; // Array to store ceiling selections
let themeSelections = [];   // Array to store theme selections

let lastRoomCanvasTitle = ''; // Variable to store the last room-canvas title

// Function to update the h5 element text based on the current mode
function updateTopPanelText() {
  if (lastRoomCanvasTitle === 'Change wall') {
    $('#topPanel h5').text('Wall: ' + wallSelections.join(', ')); // Display all wall selections
  } else if (lastRoomCanvasTitle === 'Change floor') {
    $('#topPanel h5').text('Floor: ' + floorSelections.join(', ')); // Display all floor selections
  } else if (lastRoomCanvasTitle === 'Change counter') {
    $('#topPanel h5').text('Counter: ' + counterSelections.join(', ')); // Display all counter selections
  } else if (lastRoomCanvasTitle === 'Change ceiling') {
    $('#topPanel h5').text('Ceiling: ' + ceilingSelections.join(', ')); // Display all ceiling selections
  } else if (lastRoomCanvasTitle === 'Change theme') {
    $('#topPanel h5').text('Theme: ' + themeSelections.join(', ')); // Display all theme selections
  } else {
    $('#topPanel h5').text('Choose Tiles');
  }
}
updateTopPanelText();

function selectedpaneltext() {
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
}
selectedpaneltext();

// Track clicks on any li inside #topPanelTilesListUl
$('#topPanelTilesListUl').on('click', 'li', function () {
  if ($('#topPanelTilesListUl').hasClass('wallul') && lastRoomCanvasTitle === 'Change wall') {
    wallCount++;
    let wallLetter = String.fromCharCode(64 + wallCount); // Convert wallCount to letter
    wallSelections.push('Wall ' + wallLetter); // Add to wall selections
    console.log('Wall Click Count:', wallCount);
    updateTopPanelText();
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
  } else {
    console.log("Invalid action: Ensure the correct room-canvas is selected.");
  }
});

// Store the last room-canvas title and update the class on room-canvas click
$('.room-canvas').on('click', function () {
  var title = $(this).attr('title');

  // Store the title in the variable
  lastRoomCanvasTitle = title;
  console.log('Last roomCanvas title set to:', lastRoomCanvasTitle);

  // Update the h5 element or perform other actions based on the title
  if (title === 'Change wall') {
    $('#topPanel h5').text('Wall');
    $('#topPanelTilesListUl').addClass('wallul').removeClass('floorul counterul ceilingul themeul');
  } else if (title === 'Change floor') {
    $('#topPanel h5').text('Floor');
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

  // Update the topPanelText after setting the class
  updateTopPanelText();
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