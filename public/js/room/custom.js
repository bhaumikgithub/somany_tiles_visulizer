// return '<div class="tile-list-text">\n                <p class="-caption">' + this.name + '</p>\n                ' + this.getExtraOptionsText().replace('product code: ', '') + '\n                ' + size.replace('Size: ', '') + ' ' + shape + ' ' + finish + ' ' + rotoPrintSet + '\n                ' + this.getPriceText() + ' ' + url + ' ' + usedColors + '\n            </div>';
// set canvas height
let isFirstLoad = true; // Flag to track first-time load

function AdjustCanvasWidthHeight() {
  var windowWidth = $(window).width();
  var windowHeight = $(window).height();
  var newWidth = windowHeight * 1.78;

  var newLeft = Math.round((windowWidth - newWidth) / 2);

  $("#roomCanvas").height(windowHeight);
  $("#roomCanvas").width(newWidth);

  $("#container").css({ left: newLeft });
}

function updateDivWidth() {
  var canvasWidth = $("#roomCanvas").width();
  $(".detail-section").width(canvasWidth);
}

function applyCanvasAdjustments() {
  if ($(window).width() > 500) { // Only execute if screen width > 500px
    AdjustCanvasWidthHeight();
    updateDivWidth();

    // Apply margin-left only on first load
    if (isFirstLoad) {
      $(".detail-section").css({ "margin-left": "-5px" });
      isFirstLoad = false; // Mark as applied
    }
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
  $(".detail-section").css({ "margin-left": "0px" }); // Ensure consistent margin reset on resize
  setTopPanelHeight();
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