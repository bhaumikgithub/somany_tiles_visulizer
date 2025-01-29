@extends('layouts.app')

@section('content')

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */

var changedRooms = {};

// function addCheckedRoom(value, checked) {
//     'use strict';
//     if (!isNaN(parseInt(value, 10))) {
//         changedRooms[value] = checked;
//         window.$('#warningAlertBox').slideUp();
//     }
// }

// function getCheckedRoomsArray() {
//     'use strict';
//     var ids = [],
//         id;
//     for (id in changedRooms) {
//         if (changedRooms.hasOwnProperty(id) && changedRooms[id]) {
//             ids.push(id);
//         }
//     }
//     return ids;
// }

function showConfirmDialog(headerText, message, buttontext) {
    'use strict';
    window.$('#confirmDialogHeader').text(headerText);
    window.$('#confirmDialogText').text(message);
    window.$('#confirmDialogSubmit').text(buttontext);
    window.$('#confirmDialog').modal('show');
}

function enableSelectedRooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/rooms2d/enable');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm enabling rooms', 'Please confirm enabling selected ' + checkedItems.length + ' rooms.', 'Enable Rooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function disableSelectedRooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/rooms2d/disable');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm disabling rooms', 'Please confirm disabling selected ' + checkedItems.length + ' rooms.', 'Disable Rooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteSelectedRooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/rooms2d/delete');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm removing rooms', 'Please confirm removing selected ' + checkedItems.length + ' rooms.', 'Remove Rooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteRoom(id) {
    'use strict';
    if (id) {
        window.$('#roomsForm').attr('action', '/rooms2d/delete');
        window.$('#roomsFormSelectedRooms').val('[' + id + ']');
        showConfirmDialog('Confirm removing room', 'Please confirm removing room.', 'Remove Room');
    }
}

function addRoom() {
    'use strict';
    window.$('#updateRoomFormBlock').hide();
    window.$('#addRoomFormBlock').slideToggle();
}

function editRoom(id) {
    'use strict';
    document.forms.updateRoomForm.reset();
    window.$('#form-update-room-enabled').attr('checked', false);
    window.$('#addRoomFormBlock').hide();
    window.$('#form-update-room-icon-img').attr('src', '');
    window.$('#form-update-room-shadow-img').attr('src', '');
    window.$('#form-update-room-shadow-matt-img').attr('src', '');

    window.$.ajax({
        url: '/get/room2d/' + id,
        success: function (room) {
            window.$('#form-update-room-id').val(room.id);
            window.$('#form-update-room-name').val(room.name);
            window.$('#form-update-room-type').val(room.type);

            if (Number(room.enabled)) { window.$('#form-update-room-enabled').attr('checked', true); }
            window.$('#form-update-room-icon-img').attr('src', room.icon);
            window.$('#form-update-room-image-img').attr('src', room.image);
            window.$('#form-update-room-shadow-img').attr('src', room.shadow);
            window.$('#form-update-room-shadow-matt-img').attr('src', room.shadow_matt);

            window.$('#form-update-room-surfaces').attr('href', '/room2d/' + room.id + '/surfaces');

            window.$('#updateRoomFormBlock').slideDown();
        }
    });
}

function showBigIconImageModal(name, image) {
    'use strict';
    if (name && image) {
        window.$('#bigIconImageModalHeader').text('Room: ' + name);
        window.$('#bigIconImageModalImg').attr('src', image);
        window.$('#bigIconImageModal').modal('show');
    }
}
</script>

@include('common.alerts')
@include('common.errors')


<form id="roomsForm" action="" method="POST">
  {{ csrf_field() }}
  <input id="roomsFormSelectedRooms" type="hidden" name="selectedRooms" value="">
</form>

<div id="confirmDialog" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="confirmDialogHeader" class="modal-title">Confirm </h4>
      </div>
      <div class="modal-body">
        <p id="confirmDialogText">Please confirm.</p>
      </div>
      <div class="modal-footer">
        <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="window.$('#roomsForm').submit();">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

  <h3 class="panel-heading">User's Pdf-Summary List</h3>

  <div class="panel-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>User Account Name</th>
          <th>Mobile</th>
          <th>pincode</th>
          <th>Pdf</th>
          <th>Date</th>
        </tr>
        <tr>
          <td><input type="text" class="form-control filter" data-filter="name" placeholder="Name"></td>
          <td><input type="text" class="form-control filter" data-filter="user_account" placeholder="Account"></td>
          <td><input type="text" class="form-control filter" data-filter="mobile" placeholder="Mobile"></td>
          <td><input type="text" class="form-control filter" data-filter="pincode" placeholder="Pincode"></td>
          <td><input type="text" class="form-control filter" data-filter="unique_id" placeholder="Unique ID"></td>
          <td>
            <select class="form-control sort-date">
                <option value="">Sort by Date</option>
                <option value="asc">Date ↑ (Ascending)</option>
                <option value="desc">Date ↓ (Descending)</option>
            </select>
        </td>        
        </tr>
      </thead> 

      <tbody id="user-data">
        @include('filteredPdfList')
      </tbody>
    </table>
    <div id="pagination-links">
      <div class="page-links" style="text-align: center;">{{ $usersData->links() }}</div>
    </div>
  </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    let filters = {};
    let sortByDate = '';

    console.log(filters);
    // Handle input filters
    const filterInputs = document.querySelectorAll('.filter');
    filterInputs.forEach(input => {
        input.addEventListener('input', function () {
            const key = this.dataset.filter;
            const value = this.value;
            filters[key] = value;
            fetchFilteredData();
        });
    });

    // Handle sorting
    const sortDateSelect = document.querySelector('.sort-date');
    sortDateSelect.addEventListener('change', function () {
        sortByDate = this.value;
        fetchFilteredData();
    });


    function fetchFilteredData() {
        
        const url = "{{ route('filter_pdf_list') }}";
        const params = new URLSearchParams({
            ...filters,
            sort_by_date: sortByDate
        });

        fetch(`${url}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          document.getElementById('user-data').innerHTML = data.html;

          // userDataContainer.innerHTML = data.html;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
    }
});



</script>

@endsection
