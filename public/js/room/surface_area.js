window.onload = function getRoomSurface() {
    $.ajax({
        url: '/get_room_surface', // URL to the controller method for updating the price
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            room_id: $('#current_room_id').val(),
        },
        success: function(response) {
            $('.show_selected_surface_data div#selectd-data').html(response.body);
        },
        error: function(error) {
            console.error('Error fetching room details', error);
        }
    });
}

function openTileSelectionPanel(surface_name) {
    // Show the info panel
    $('#selectd-data').hide();
    $('#slected-panel').show();
    $('#slected-panel .display_surface_name h5#optionText').text(surface_name);
}