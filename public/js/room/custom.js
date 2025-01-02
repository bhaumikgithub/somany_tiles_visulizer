// return '<div class="tile-list-text">\n                <p class="-caption">' + this.name + '</p>\n                ' + this.getExtraOptionsText().replace('product code: ', '') + '\n                ' + size.replace('Size: ', '') + ' ' + shape + ' ' + finish + ' ' + rotoPrintSet + '\n                ' + this.getPriceText() + ' ' + url + ' ' + usedColors + '\n            </div>';


$('#grout-predefined-color .-btn').on('click', function () {

    alert("hello");
    // Remove 'active' class from all buttons
    $('#grout-predefined-color .-btn').removeClass('active');

    // Add 'active' class to the clicked button
    $(this).addClass('active');
  });


  $('.share-btn-img').click(function(){
    $('.share-div').css('display','flex');
    $(this).hide();
    $('.share-btn-close').show();
});
$('.share-btn-close').click(function(){
    $('.share-div').css('display','none');
    $(this).hide();
    $('.share-btn-img').show();
});

$('.continue-btn-img').click(function(){
    $('.continue-div').css('display','flex');
    $(this).hide();
    $('.continue-btn-close').show();
});
$('.continue-btn-close').click(function(){
    $('.continue-div').css('display','none');
    $(this).hide();
    $('.continue-btn-img').show();
});

$('.rotate-font').click(function(){
    $('.rotate-font').removeClass('active');
    $(this).addClass('active');
});