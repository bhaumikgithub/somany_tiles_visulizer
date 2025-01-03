// return '<div class="tile-list-text">\n                <p class="-caption">' + this.name + '</p>\n                ' + this.getExtraOptionsText().replace('product code: ', '') + '\n                ' + size.replace('Size: ', '') + ' ' + shape + ' ' + finish + ' ' + rotoPrintSet + '\n                ' + this.getPriceText() + ' ' + url + ' ' + usedColors + '\n            </div>';


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