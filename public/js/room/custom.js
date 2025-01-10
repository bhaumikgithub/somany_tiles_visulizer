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
let counterCount = 0;  // New variable for counter count
let furnitureCount = 0;  // New variable for furniture count
let lastRoomCanvasTitle = ''; // Variable to store the last room-canvas title

// Function to update the h5 element text based on the current mode
function updateTopPanelText() {
  if (lastRoomCanvasTitle === 'Change wall') {
    // Display the current wall count as a letter
    let wallLetter = String.fromCharCode(64 + wallCount); // Convert wallCount to letter
    $('#topPanel h5').text('Wall' + wallLetter); // No space between "Wall" and the letter
  } else if (lastRoomCanvasTitle === 'Change floor') {
    // Display the current floor count as a letter
    let floorLetter = String.fromCharCode(64 + floorCount); // Convert floorCount to letter
    $('#topPanel h5').text('Floor ' + floorLetter); // Space between "Floor" and the letter
  } else if (lastRoomCanvasTitle === 'Change counter') {
    // Display the current counter count as a letter
    let counterLetter = String.fromCharCode(64 + counterCount); // Convert counterCount to letter
    $('#topPanel h5').text('Counter ' + counterLetter); // Space between "Counter" and the letter
  } else if (lastRoomCanvasTitle === 'Change furniture') {
    // Display the current furniture count as a letter
    let furnitureLetter = String.fromCharCode(64 + furnitureCount); // Convert furnitureCount to letter
    $('#topPanel h5').text('Furniture ' + furnitureLetter); // Space between "Furniture" and the letter
  } else {
    // If no title is selected, display "Choose floor or wall"
    $('#topPanel h5').text('Choose Tiles');
  }
}
updateTopPanelText();

function selectedpaneltext(){

  if (lastRoomCanvasTitle === 'Change wall' && wallCount === 0) {
    alert(wallCount);
    $('#slected-panel-data p').text('Please first choose data'); // Display this if no wall is selected
  } else if (lastRoomCanvasTitle === 'Change floor' && floorCount === 0) {
    $('#slected-panel-data p').text('Please first choose data'); // Display this if no wall is selected
  } else if (lastRoomCanvasTitle === 'Change counter' && counterCount === 0) {
    $('#slected-panel-data p').text('Please first choose data'); // Display this if no wall is selected
  } else if (lastRoomCanvasTitle === 'Change furniture' && furnitureCount === 0) {
    $('#slected-panel-data p').text('Please first choose data'); // Display this if no wall is selected
  }

}
selectedpaneltext();
// Track clicks on any li inside #topPanelTilesListUl
$('#topPanelTilesListUl').on('click', 'li', function () {
  // Check if #topPanelTilesListUl has the 'wallul', 'floorul', 'counterul', or 'furnitureul' class
  if ($('#topPanelTilesListUl').hasClass('wallul') && lastRoomCanvasTitle === 'Change wall') {
    // Increment the wall count only if the title is "Change wall"
    wallCount++;
    console.log('Wall Click Count:', wallCount);
    updateTopPanelText();
  } else if ($('#topPanelTilesListUl').hasClass('floorul') && lastRoomCanvasTitle === 'Change floor') {
    // Increment the floor count only if the title is "Change floor"
    floorCount++;
    console.log('Floor Click Count:', floorCount);
    updateTopPanelText();
  } else if ($('#topPanelTilesListUl').hasClass('counterul') && lastRoomCanvasTitle === 'Change counter') {
    // Increment the counter count only if the title is "Change counter"
    counterCount++;
    console.log('Counter Click Count:', counterCount);
    updateTopPanelText();
  } else if ($('#topPanelTilesListUl').hasClass('furnitureul') && lastRoomCanvasTitle === 'Change furniture') {
    // Increment the furniture count only if the title is "Change furniture"
    furnitureCount++;
    console.log('Furniture Click Count:', furnitureCount);
    updateTopPanelText();
  } else {
    console.log("Invalid action: Ensure the correct room-canvas is selected.");
  }
});

// Store the last room-canvas title and update the class on room-canvas click
$('.room-canvas').on('click', function () {
  var title = $(this).attr('title'); // Get the title attribute of the clicked room-canvas element

  // Store the title in the variable
  lastRoomCanvasTitle = title;
  console.log('Last roomCanvas title set to:', lastRoomCanvasTitle);

  // Update the h5 element or perform other actions based on the title
  if (title === 'Change wall') {
    $('#topPanel h5').text('Wall'); // Reset to "Wall"
    $('#topPanelTilesListUl').addClass('wallul').removeClass('floorul counterul furnitureul');
  } else if (title === 'Change floor') {
    $('#topPanel h5').text('Floor'); // Reset to "Floor"
    $('#topPanelTilesListUl').addClass('floorul').removeClass('wallul counterul furnitureul');
  } else if (title === 'Change counter') {
    $('#topPanel h5').text('Counter'); // Reset to "Counter"
    $('#topPanelTilesListUl').addClass('counterul').removeClass('wallul floorul furnitureul');
  } else if (title === 'Change furniture') {
    $('#topPanel h5').text('Furniture'); // Reset to "Furniture"
    $('#topPanelTilesListUl').addClass('furnitureul').removeClass('wallul floorul counterul');
  }

  // Update the topPanelH5 text after setting the class
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