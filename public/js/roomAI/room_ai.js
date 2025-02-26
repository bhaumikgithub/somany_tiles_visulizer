$(document).ready(function () {
    $('#user_own_room').change(function () {
        let file = this.files[0];
        if (!file) return; // If no file selected, do nothing

        let formData = new FormData();
        formData.append('user_own_room', file);

        $.ajax({
            url: $('#uploadForm').attr('data-action'), // Use named route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                window.location.href = '/ai_room/'+response.room_id; // Redirect to room page
            },
            error: function (xhr) {
                $('#message').html('<p style="color: red;">' + xhr.responseJSON.message + '</p>');
            }
        });
    });
});