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

let wallCount = 0;
let floorCount = 0;
let lastRoomCanvasTitle = ''; // Variable to store the last room-canvas title

// Track clicks on any li inside #topPanelTilesListUl
$('#topPanelTilesListUl').on('click', 'li', function () {
  // Check if #topPanelTilesListUl has the 'wallul' or 'floorul' class
  if ($('#topPanelTilesListUl').hasClass('wallul')) {
    // If it has 'wallul' class, increment the wall count
    wallCount++;
    console.log('Wall Click Count:', wallCount);
    
    // Convert wallCount to corresponding letter (A, B, C, etc.)
    let wallLetter = String.fromCharCode(64 + wallCount);  // 65 is 'A' in ASCII
    $('#topPanel h5').text('Wall ' + wallLetter);
    //alert('Wall Click Count: ' + wallCount + ' - ' + 'Wall ' + wallLetter);
  } else if ($('#topPanelTilesListUl').hasClass('floorul')) {
    // If it has 'floorul' class, increment the floor count
    floorCount++;
    console.log('Floor Click Count:', floorCount);
    
    // Convert floorCount to corresponding letter (A, B, C, etc.)
    let floorLetter = String.fromCharCode(64 + floorCount);  // 65 is 'A' in ASCII
    $('#topPanel h5').text('Floor ' + floorLetter);
   // alert('Floor Click Count: ' + floorCount + ' - ' + 'Floor ' + floorLetter);
  } else {
    console.log("Neither 'wallul' nor 'floorul' class found on #topPanelTilesListUl.");
  }

  // Update the topPanelH5 text based on both wall and floor counts
  updateTopPanelText();
});

// Store the last room-canvas title and update the class on room-canvas click
$('.room-canvas').on('click', function () {
  var title = $(this).attr('title'); // Get the title attribute of the clicked room-canvas element
  
  // Store the title in the variable
  lastRoomCanvasTitle = title;
  console.log('Last roomCanvas title set to:', lastRoomCanvasTitle);
  
  // Update the h5 element or perform other actions based on the title
  var topPanelH5 = $('#topPanel h5');
  if (topPanelH5.length > 0) {
    if (title === 'Change wall') {
      topPanelH5.text('Wall');
      // Add class 'wallul' to #topPanelTilesListUl and remove 'floorul' class if present
      $('#topPanelTilesListUl').addClass('wallul').removeClass('floorul');
    } else if (title === 'Change floor') {
      topPanelH5.text('Floor');
      // Add class 'floorul' to #topPanelTilesListUl and remove 'wallul' class if present
      $('#topPanelTilesListUl').addClass('floorul').removeClass('wallul');
    }
  }

  // Update the topPanelH5 text after setting the class
  updateTopPanelText();
});

// Function to update the text of topPanelH5 based on both wall and floor counts
function updateTopPanelText() {
  let topPanelH5 = $('#topPanel h5');
  
  // Generate the appropriate Wall and Floor text
  let wallText = wallCount > 0 ? 'Wall ' + String.fromCharCode(64 + wallCount) : '';
  let floorText = floorCount > 0 ? 'Floor ' + String.fromCharCode(64 + floorCount) : '';
  
  // Update the text based on the counts
  if (wallText && floorText) {
    topPanelH5.text(wallText + ', ' + floorText);  // Show both Wall and Floor if both are counted
  } else if (wallText) {
    topPanelH5.text(wallText);  // Only Wall if floor count is 0
  } else if (floorText) {
    topPanelH5.text(floorText);  // Only Floor if wall count is 0
  }
}
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



// $('.rotate-font').on('click', function () {
//     $('.rotate-font').removeClass('active');
//     $(this).addClass('active');
// });