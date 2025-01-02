// return '<div class="tile-list-text">\n                <p class="-caption">' + this.name + '</p>\n                ' + this.getExtraOptionsText().replace('product code: ', '') + '\n                ' + size.replace('Size: ', '') + ' ' + shape + ' ' + finish + ' ' + rotoPrintSet + '\n                ' + this.getPriceText() + ' ' + url + ' ' + usedColors + '\n            </div>';


$('#grout-predefined-color .-btn').on('click', function () {

    alert("hello");
    // Remove 'active' class from all buttons
    $('#grout-predefined-color .-btn').removeClass('active');

    // Add 'active' class to the clicked button
    $(this).addClass('active');
  });