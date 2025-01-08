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