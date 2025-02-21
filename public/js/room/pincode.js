// Check if the modal should open on a page load
window.onload = function() {
    // Check if pincode is already set in session using AJAX
    fetch('/check-pincode')
        .then(response => response.json())
        .then(data => {
            if (!data.pincode_saved) {
                $('#pincode').modal('show');
                setTimeout(function() {
                    $('.onLoadWrapper').css('display','block');
                }, 3000);
            } else {
                $('.onLoadWrapper').css('display','block');
            }
        });

};

$(document).on("input", ".pin_code", function() {
    this.value = this.value.replace(/\D/g,'');
});

// Handle the pincode form submission
$('#pincodeForm').on('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    let pincode = $('#pin_code').val(); // Get the pincode value

    // Send the pincode to the backend using jQuery AJAX
    $.ajax({
        url: '/save-pincode', // Endpoint for saving the pincode
        type: 'POST', // HTTP method
        dataType: 'json', // Expected response format
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        data: {
            pincode: pincode // Data to send in the request body
        },
        success: function (response) {
            // Close the modal on successful response
            $('#pincode').modal('hide');
            console.log('Pincode saved successfully:', response.message);
        },
        error: function (xhr, status, error) {
            console.error('Error saving pincode:', error);
        }
    });
});

function fetchCategory(category) {
    fetch("/track-category", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ category: category }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to the respective category page
                window.location.href = `/listing/${category}`;
            }
        });
}

function fetchRoom(room_id,room) {
    fetch("/track-category", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ room_id: room_id , room:room }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to the respective category page
                window.location.href = `/room2d/${room_id}`;
            }
        });
}

