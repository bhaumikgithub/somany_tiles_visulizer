// Validation function for a single input pair for update method
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("updateRoomForm");
    if (form) {
        // Input pairs for validation
        const inputGroups = [
            {
                file: "form-update-room-chosen-theme-0",
                thumbnail: "form-update-room-chosen-thumbnail-0",
                text: "form-update-room-text-0",
                theme: 0
            },
            {
                file: "form-update-room-chosen-theme-1",
                thumbnail: "form-update-room-chosen-thumbnail-1",
                text: "form-update-room-text-1",
                theme: 1,
                clearBtn: "clear-theme-1"
            },
            {
                file: "form-update-room-chosen-theme-2",
                thumbnail: "form-update-room-chosen-thumbnail-2",
                text: "form-update-room-text-2",
                theme: 2,
                clearBtn: "clear-theme-2"
            },
            {
                file: "form-update-room-chosen-theme-3",
                thumbnail: "form-update-room-chosen-thumbnail-3",
                text: "form-update-room-text-3",
                theme: 3,
                clearBtn: "clear-theme-3"
            },
            {
                file: "form-update-room-chosen-theme-4",
                thumbnail: "form-update-room-chosen-thumbnail-4",
                text: "form-update-room-text-4",
                theme: 4,
                clearBtn: "clear-theme-4"
            },
            {
                file: "form-update-room-chosen-theme-5",
                thumbnail: "form-update-room-chosen-thumbnail-5",
                text: "form-update-room-text-5",
                theme: 5,
                clearBtn: "clear-theme-"
            }
        ];
        formAddEventListener(inputGroups,form);
        assignClearButtonEventListener(inputGroups);
    } else {
        console.error("Element with ID 'updateRoomForm' not found.");
    }
});

// Validation function for a single input pair for add method
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addRoomForm");
    if (form) {
        // Input pairs for validation
        const inputGroups = [
            {
                file: "form-room-chosen-theme-1",
                thumbnail: "form-room-chosen-thumbnail-1",
                text: "form-room-text-1",
                theme: 1
            },
            {
                file: "form-room-chosen-theme-2",
                thumbnail: "form-room-chosen-thumbnail-2",
                text: "form-room-text-2",
                theme: 2
            },
            {
                file: "form-room-chosen-theme-3",
                thumbnail: "form-room-chosen-thumbnail-3",
                text: "form-room-text-3",
                theme: 3
            },
            {
                file: "form-room-chosen-theme-4",
                thumbnail: "form-room-chosen-thumbnail-4",
                text: "form-room-text-4",
                theme: 4
            },
            {
                file: "form-room-chosen-theme-5",
                thumbnail: "form-room-chosen-thumbnail-5",
                text: "form-room-text-5",
                theme: 5
            }
        ];
        formAddEventListener(inputGroups,form);
    } else {
        console.error("Element with ID 'updateRoomForm' not found.");
    }
});

function formAddEventListener(inputGroups,form)
{
    form.addEventListener("submit", function (e) {
        let isValid = true;
        let validationMessages = [];

        inputGroups.forEach(group => {
            const fileInput = document.getElementById(group.file);
            const thumbnailInput = document.getElementById(group.thumbnail);
            const textInput = document.getElementById(group.text);

            // Check values of each input
            const hasFile = fileInput && fileInput.value.trim() !== "";
            const hasThumbnail = thumbnailInput && thumbnailInput.value.trim() !== "";
            const hasText = textInput && textInput.value.trim() !== "";

            // Collect missing fields for the current theme
            const missingFields = [];
            if (!hasFile) missingFields.push("File");
            if (!hasThumbnail) missingFields.push("Thumbnail");
            if (!hasText) missingFields.push("Text");


            // If all fields already have values, skip validation
            if (hasFile && hasThumbnail && hasText) {
                return;
            }

            // If any field is filled but others are missing, show a detailed message
            if ((hasFile || hasThumbnail || hasText) && missingFields.length > 0) {
                validationMessages.push(
                    `Theme ${group.theme}: Missing field(s) - ${missingFields.join(", ")}`
                );
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert(`Please complete the required fields:\n\n${validationMessages.join("\n")}`);
        }
    });
}


function assignClearButtonEventListener(inputGroups) {
    inputGroups.forEach(group => {
        const clearButton = document.getElementById(group.clearBtn);
        if (clearButton) {
            clearButton.addEventListener("click", function () {
                clearThemeFields(group);
            });
        }
    });
}

// Function to clear a theme's inputs
function clearThemeFields(group) {
    const fileInput = document.getElementById(group.file);
    const thumbnailInput = document.getElementById(group.thumbnail);
    const textInput = document.getElementById(group.text);
    const room_id = document.getElementById('form-update-room-id')?.value || null;
    if (fileInput) fileInput.value = ""; // Clear file input
    if (thumbnailInput) thumbnailInput.value = ""; // Clear thumbnail input
    if (textInput) textInput.value = ""; // Clear text input

    // Send AJAX request to remove the file, thumbnail, and text from the database
    fetch('/room2d/clear-theme', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ theme: group.theme , room_id:room_id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(`Theme ${group.theme} cleared from the database.`);
        } else {
            alert(`Error clearing Theme ${group.theme}.`);
        }
    })
    .catch(error => console.error("Error:", error));
}


// Function to handle file input change
function handleFileChange(fileInputId, hiddenInputId) {
    const fileInput = document.getElementById(fileInputId);
    const hiddenInput = document.getElementById(hiddenInputId);
    hiddenInput.value = fileInput.files[0].name;
}