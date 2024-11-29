export const HomePage = (function () {
    const changedSavedRooms = {};

    function checkSelectedSavedRooms() {
        for (const id in changedSavedRooms) {
            if (changedSavedRooms.hasOwnProperty(id) && changedSavedRooms[id]) {
                return true;
            }
        }
    }

    function getCheckedSavedRoomsArray() {
        const ids = [];
        if (checkSelectedSavedRooms()) {
            for (const id in changedSavedRooms) {
                if (changedSavedRooms.hasOwnProperty(id) && changedSavedRooms[id]) {
                    ids.push(id);
                }
            }
        }
        return ids;
    }

    return {
        addCheckedSavedRoom: function (value, checked) {
            changedSavedRooms[value] = checked;
            window.$('#warningAlertBox').fadeOut();
        },

        deleteSelectedSavedRooms: function () {
            if (checkSelectedSavedRooms()) {
                const checkedSavedRooms = getCheckedSavedRoomsArray();
                window.$('#savedRoomsForm').attr('action', '/home/rooms/delete');
                window.$('#savedRoomsFormInput').val(JSON.stringify(checkedSavedRooms));

                window.$('#confirmDialogHeader').text('Confirm removing rooms');
                window.$('#confirmDialogText').text('Please confirm removing selected ' + checkedSavedRooms.length + ' rooms.');
                window.$('#confirmDialogSubmit').text('Remove rooms');
                window.$('#confirmDialog').modal('show');
            } else {
                window.$('#warningAlertBox').fadeIn();
            }
        },

        deleteSavedRoom: function (id) {
            window.$('#savedRoomsForm').attr('action', '/home/rooms/delete');
            window.$('#savedRoomsFormInput').val('[' + id + ']');

            window.$('#confirmDialogHeader').text('Confirm removing room');
            window.$('#confirmDialogText').text('Please confirm removing room.');
            window.$('#confirmDialogSubmit').text('Remove room');
            window.$('#confirmDialog').modal('show');
        },
    };
})();
