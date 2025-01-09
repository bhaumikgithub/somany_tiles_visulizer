@extends('layouts.app')

@section('content')

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */

var changedRooms = {};

function addCheckedRoom(value, checked) {
    'use strict';
    if (!isNaN(parseInt(value, 10))) {
        changedRooms[value] = checked;
        window.$('#warningAlertBox').slideUp();
    }
}

function getCheckedRoomsArray() {
    'use strict';
    var ids = [],
        id;
    for (id in changedRooms) {
        if (changedRooms.hasOwnProperty(id) && changedRooms[id]) {
            ids.push(id);
        }
    }
    // console.log(changedRooms)
    return ids;
}

function showConfirmDialog(headerText, message, buttontext) {
    'use strict';
    window.$('#confirmDialogHeader').text(headerText);
    window.$('#confirmDialogText').text(message);
    window.$('#confirmDialogSubmit').text(buttontext);
    window.$('#confirmDialog').modal('show');
}

function enableSelectedShowrooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/showrooms/enable');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm enabling Showrooms', 'Please confirm enabling selected ' + checkedItems.length + ' Showrooms.', 'Enable Showrooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function disableSelectedShowrooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/showrooms/disable');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm disabling Showrooms', 'Please confirm disabling selected ' + checkedItems.length + ' Showrooms.', 'Disable Showrooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteSelectedShowrooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/showrooms/delete');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm removing Showrooms', 'Please confirm removing selected ' + checkedItems.length + ' Showrooms.', 'Remove Showrooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteShowRoom(id) {
    'use strict';
    if (id) {
        window.$('#roomsForm').attr('action', '/showrooms/delete');
        window.$('#roomsFormSelectedRooms').val('[' + id + ']');
        showConfirmDialog('Confirm removing room', 'Please confirm removing room.', 'Remove Room');
    }
}

function addRoom() {
    'use strict';

    $('#addRoomForm')[0].reset();

    $('#addRoomForm').attr('action', '/fetch_showroom'); // Replace with your create route
    $('#addRoomForm').attr('method', 'POST');

    $('input[name="_method"]').remove();

    $('#add-button').text('Add Showroom');

    $('#addRoomFormBlock').slideToggle();
}

function editShowroom(id) {
  console.log(id)
    $.ajax({
        url: '/fetch_showroom/' + id, // Use the resource route
        method: 'GET', // Fetch the data
        success: function(data) {
          console.log(data.status);

            $('#add-button').text('Update Showoom');

            $('#name').val(data.name);
            $('#e_code').val(data.e_code);
            $('#city').val(data.city);
            $('#address').val(data.address);
            $('#form-room-type').val(data.status);

            $('#addRoomForm').attr('action', '/fetch_showroom/' + data.id);
            $('#addRoomForm').attr('method', 'POST');

            $('input[name="_method"]').remove();
            $('#addRoomForm').append('<input type="hidden" name="_method" value="PUT">');

            $('#addRoomFormBlock').slideDown();
        },
        error: function(xhr, status, error) {
            alert('An error occurred while fetching showroom data.');
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

<div id="addRoomFormBlock" class="panel-body" style="display: none;">
  <form id="addRoomForm" action="/fetch_showroom" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group required">
      <label for="form-room-name" class="col-sm-3 control-label">Name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', isset($showroom) ? $showroom->name : '') }}" required>
      </div>
    </div>

    <div class="form-group required">
        <label for="form-room-name" class="col-sm-3 control-label">E_code</label>
        <div class="col-sm-6">
            <input type="text" name="e_code" id="e_code" class="form-control" value="{{ old('e_code', isset($showroom) ? $showroom->e_code : '') }}" required>        
        </div>
    </div> 


    <div class="form-group required">
        <label for="form-room-name" class="col-sm-3 control-label">City</label>
        <div class="col-sm-6">
            <input type="text" name="city" id="city" class="form-control" value="{{ old('city', isset($showroom) ? $showroom->city : '') }}" required>
        </div>
    </div> 

    <div class="form-group required">
        <label for="form-room-name" class="col-sm-3 control-label">Address</label>
        <div class="col-sm-6">
            <textarea name="address" id="address" class="form-control" rows="4" required>{{ old('address', isset($showroom) ? $showroom->address : '') }}</textarea>
        </div>
    </div> 


    <div class="form-group required">
      <label for="form-room-type" class="col-sm-3 control-label">Category</label>
      <div class="col-sm-6">
        <select name="status" id="form-room-type" class="form-control">
            <option value="Active" id="option-active" {{ old('status', isset($showroom) ? $showroom->status : '') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" id="option-inactive" {{ old('status', isset($showroom) ? $showroom->status : '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
    </div>

    <div class="form-group required">
      <div class="col-sm-offset-3 col-sm-6">
        <div class="pull-right">
          <button id="add-button" type="submit" class="btn btn-primary">Add Showroom</button>
          <button type="reset" class="btn btn-default" onclick="$('#addRoomFormBlock').slideUp();">Cancel</button>
        </div>
      </div>
    </div>
  </form>
</div>

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

<div id="bigIconImageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="bigIconImageModalHeader" class="modal-title">Room image</h4>
      </div>
      <div class="modal-body" style="text-align: center;">
        <img id="bigIconImageModalImg" src="" alt="" style="max-width: 512px; max-height: 512px;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="panel panel-default">
  <div class="dropdown pull-right">
    <button class="btn btn-default btn-sm" onclick="addRoom();">+ Add Showroom</button>
    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
      With selected
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" onclick="enableSelectedShowrooms();">Enable</a></li>
      <li><a href="#" onclick="disableSelectedShowrooms();">Disable</a></li>
      <li class="divider"></li>
      <li><a href="#" onclick="deleteSelectedShowrooms();">Remove</a></li>
    </ul>
  </div>

  <h3 class="panel-heading">Showrooms list</h3>

  <div class="panel-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Name</th>
          <th>E_code</th>
          <th>City</th>
          <th>Address</th>
          <th>Status</th>
        </tr>
      </thead>

      <tbody>
        @if (count($showrooms) > 0)
        @foreach ($showrooms as $showroom)
        <tr @if ($showroom->status == "Inactive") style="opacity: 0.5;" @endif>
          <td class="table-text">
            <input type="checkbox" name="" value="{{ $showroom->id }}" onchange="addCheckedRoom(this.value, this.checked);">
          </td>
          {{-- <td class="table-text">
            <img src="{{ $room->icon }}" alt="" class="img-thumbnail" style="max-width: 64px; max-height: 64px; cursor: pointer;" onclick="showBigIconImageModal('{{ $room->name }}', this.src);">
          </td> --}}
          <td class="table-text bold"><a href="#" onclick="editShowroom( {{ $showroom->id }} );" title="Edit">{{ $showroom->name }}</a></td>
          <td class="table-text bold">{{ $showroom->e_code }}</td>
          <td class="table-text bold">{{ $showroom->city }}</td>
          <td class="table-text bold">{{ $showroom->address }}</td>
          <td class="table-text">@if ($showroom->status == "Active") Active @else Inactive @endif</td>

          <td class="table-text">
            <button type="button" class="close" onclick="deleteShowRoom({{ $showroom->id }});" title="Remove Showroom">&times;</button>
          </td>
        </tr>
        @endforeach
        @else
          No one room found.
        @endif
      </tbody>
    </table>
    <div class="page-links" style="text-align: center;">{{ $showrooms->links() }}</div>
  </div>
</div>

@endsection
