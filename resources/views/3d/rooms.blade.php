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
    return ids;
}

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
        window.$('#roomsForm').attr('action', '/rooms/enable');
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
        window.$('#roomsForm').attr('action', '/rooms/disable');
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
        window.$('#roomsForm').attr('action', '/rooms/delete');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm removing rooms', 'Please confirm removing selected ' + checkedItems.length + ' rooms.', 'Remove Rooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteRoom(id) {
    'use strict';
    if (id) {
        window.$('#roomsForm').attr('action', '/rooms/delete');
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
    window.$('#form-update-room-useMirrors').attr('checked', false);
    window.$('#addRoomFormBlock').hide();
    window.$('#form-update-room-extraOptions').attr('checked', false);
    window.$('#updateRoomFormExtraOptions').hide();
    window.$('#form-update-room-iconfile-img').attr('src', '');

    window.$.ajax({
        url: '/get/room/' + id,
        success: function (room) {
            window.$('#form-update-room-id').val(room.id);
            window.$('#form-update-room-name').val(room.name);
            window.$('#form-update-room-type').val(room.type);
            window.$('#form-update-room-mapSize').val(room.mapSize);
            window.$('#form-update-room-cameraFov').val(room.cameraFov);
            window.$('#form-update-room-size').val(room.size);
            window.$('#form-update-room-sourcesPath').val(room.sourcesPath);
            window.$('#form-update-room-firstPersonViewHeight').val(room.firstPersonViewHeight);
            window.$('#form-update-room-endPoints').val(room.endPoints);
            window.$('#form-update-room-parts').val(room.parts);
            window.$('#form-update-room-tiledSurfaces').val(room.tiledSurfaces);
            window.$('#form-update-room-mirrors').val(room.mirrors);

            if (Number(room.useMirrors)) { window.$('#form-update-room-useMirrors').attr('checked', true); }
            if (Number(room.enabled)) { window.$('#form-update-room-enabled').attr('checked', true); }
            window.$('#form-update-room-iconfile-img').attr('src', room.iconfile);

            window.$('#updateRoomFormBlock').slideDown();
        }
    });
}

function changeExtraOptions(checked) {
    'use strict';
    if (checked) {
        window.$('#updateRoomFormExtraOptions').slideDown();
    } else {
        window.$('#updateRoomFormExtraOptions').hide();
    }
}

function showBigIconfileImageModal(name, image) {
    'use strict';
    if (name && image) {
        window.$('#bigIconfileImageModalHeader').text('Room: ' + name);
        window.$('#bigIconfileImageModalImg').attr('src', image);
        window.$('#bigIconfileImageModal').modal('show');
    }
}
</script>

@include('common.alerts')
@include('common.errors')

<div id="addRoomFormBlock" class="panel-body" style="display: none;">
  <form id="addRoomForm" action="/room/add" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group required">
      <label for="form-room-name" class="col-sm-3 control-label">Name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-room-name" class="form-control" placeholder="Room name" required>
      </div>
    </div>

    <div class="form-group">
      <label for="form-room-type" class="col-sm-3 control-label">Category</label>
      <div class="col-sm-6">
        <select name="type" id="form-room-type" class="form-control">
          @if (count($roomTypes) > 0)
          @foreach ($roomTypes as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="form-room-useMirrors" class="col-sm-3 control-label">Use mirrors</label>
      <div class="col-sm-6">
        <input type="checkbox" name="useMirrors" id="form-room-useMirrors" value="1">
      </div>
    </div>

    <div class="form-group">
      <label for="form-room-mapSize" class="col-sm-3 control-label">Map size</label>
      <div class="col-sm-6">
        <select name="mapSize" id="form-room-mapSize" class="form-control" placeholder="Map size" required>
          <option value="1024">1024</option>
          <option value="2048" selected>2048</option>
          <option value="4096">4096</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="form-room-cameraFov" class="col-sm-3 control-label">Camera FOV</label>
      <div class="col-sm-6">
        <input type="number" name="cameraFov" id="form-room-cameraFov" class="form-control" placeholder="Camera FOV" min="10" max="180" value="60">
      </div>
    </div>

    <div class="form-group required">
      <label for="form-room-sourcesPath" class="col-sm-3 control-label">Resources path</label>
      <div class="col-sm-6">
        <input type="text" name="sourcesPath" id="form-room-sourcesPath" class="form-control" placeholder="Path to room resources" required>
      </div>
    </div>

    <div class="form-group">
      <label for="form-room-iconfile" class="col-sm-3 control-label">Icon file</label>
      <div class="col-sm-6">
        <input type="file" name="iconfile" id="form-room-iconfile" accept="image/*" class="form-control">
      </div>
      <span class="col-sm-3 help-block">Image must be less than 1 MB and resolution less than 1024x1024 pixels.</span>
    </div>

    <div class="form-group required">
      <label for="form-room-firstPersonViewHeight" class="col-sm-3 control-label">First Person View Height</label>
      <div class="col-sm-6">
        <input type="text" name="firstPersonViewHeight" id="form-room-firstPersonViewHeight" class="form-control" placeholder="First Person View Height" required>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-room-size" class="col-sm-3 control-label">Room sizes</label>
      <div class="col-sm-6">
        <input type="text" name="size" id="form-room-size" class="form-control code-font" placeholder="JSON object" required>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-room-endPoints" class="col-sm-3 control-label">End points</label>
      <div class="col-sm-6">
        <textarea rows="2" name="endPoints" id="form-room-endPoints" class="form-control code-font" placeholder="JSON object" required></textarea>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-room-parts" class="col-sm-3 control-label">Parts</label>
      <div class="col-sm-6">
        <textarea rows="4" name="parts" id="form-room-parts" class="form-control code-font" placeholder="JSON array of objects" required></textarea>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-room-tiledSurfaces" class="col-sm-3 control-label">Tiled surfaces</label>
      <div class="col-sm-6">
        <textarea rows="4" name="tiledSurfaces" id="form-room-tiledSurfaces" class="form-control code-font" placeholder="JSON array of objects" required></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="form-room-mirrors" class="col-sm-3 control-label">Mirrors</label>
      <div class="col-sm-6">
        <textarea rows="3" name="mirrors" id="form-room-mirrors" class="form-control code-font" placeholder="JSON array of objects"></textarea>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <div class="pull-right">
          <button type="submit" class="btn btn-primary">Add room</button>
          <button type="reset" class="btn btn-default" onclick="$('#addRoomFormBlock').slideUp();">Cancel</button>
        </div>
      </div>
    </div>
  </form>
</div>


<div id="updateRoomFormBlock" class="panel-body" style="display: none;">
  <form id="updateRoomForm" action="/room/update" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group required">
      <label for="form-update-room-id" class="col-sm-3 control-label">Id</label>
      <div class="col-sm-3">
        <input type="text" name="id" id="form-update-room-id" class="form-control" readonly="readonly" required>
      </div>
      <div class="col-sm-3">
        <label><input type="checkbox" name="enabled" id="form-update-room-enabled" value="1"> Enabled</label>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-update-room-name" class="col-sm-3 control-label">Name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-update-room-name" class="form-control" placeholder="Room name" required>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-room-type" class="col-sm-3 control-label">Category</label>
      <div class="col-sm-6">
        <select name="type" id="form-update-room-type" class="form-control">
          @if (count($roomTypes) > 0)
          @foreach ($roomTypes as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-room-useMirrors" class="col-sm-3 control-label">Use mirrors</label>
      <div class="col-sm-6">
        <input type="checkbox" name="useMirrors" id="form-update-room-useMirrors" value="1">
      </div>
    </div>

    <div class="form-group required">
      <label for="form-update-room-sourcesPath" class="col-sm-3 control-label">Sources path</label>
      <div class="col-sm-6">
        <input type="text" name="sourcesPath" id="form-update-room-sourcesPath" class="form-control" placeholder="Map size" required>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-room-mapSize" class="col-sm-3 control-label">Map size</label>
      <div class="col-sm-6">
        <select name="mapSize" id="form-update-room-mapSize" class="form-control" placeholder="Path to room resources" required>
          <option value="1024">1024</option>
          <option value="2048" selected>2048</option>
          <option value="4096">4096</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-room-cameraFov" class="col-sm-3 control-label">Camera FOV</label>
      <div class="col-sm-6">
        <input type="number" name="cameraFov" id="form-update-room-cameraFov" class="form-control" placeholder="Camera FOV" min="10" max="180" value="60">
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-room-iconfile" class="col-sm-3 control-label">Icon file</label>
      <div class="col-sm-2">
        <img id="form-update-room-iconfile-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconfileImageModal(window.$('#form-update-room-name').val(), this.src)">
      </div>
      <div class="col-sm-4">
        <input type="file" name="iconfile" id="form-update-room-iconfile" accept="image/*" class="form-control">
      </div>
      <span class="col-sm-3 help-block">Image must be less than 1 MB and resolution less than 1024x1024 pixels.</span>
    </div>


    <div class="form-group">
      <label for="form-update-room-extraOptions" class="col-sm-3 control-label">3d Room extra options</label>
      <div class="col-sm-3">
        <label>
          <input type="checkbox" name="extraOptions" id="form-update-room-extraOptions" onchange="changeExtraOptions(this.checked);" value="1">
          I understand what I do
        </label>
      </div>
      <span class="col-sm-6 help-block">Please do not change anything, if you do not understand what you do.</span>
    </div>

    <div id="updateRoomFormExtraOptions" style="display: none">
      <div class="form-group required">
        <label for="form-update-room-firstPersonViewHeight" class="col-sm-3 control-label">First Person View height</label>
        <div class="col-sm-6">
          <input type="text" name="firstPersonViewHeight" id="form-update-room-firstPersonViewHeight" class="form-control" placeholder="First Person View Height" required>
        </div>
      </div>

      <div class="form-group required">
        <label for="form-update-room-size" class="col-sm-3 control-label">Size</label>
        <div class="col-sm-6">
          <input type="text" name="size" id="form-update-room-size" class="form-control code-font" placeholder="JSON object" required>
        </div>
      </div>

      <div class="form-group required">
        <label for="form-update-room-endPoints" class="col-sm-3 control-label">End points</label>
        <div class="col-sm-6">
          <textarea rows="2" name="endPoints" id="form-update-room-endPoints" class="form-control code-font" placeholder="JSON object" required></textarea>
        </div>
      </div>

      <div class="form-group required">
        <label for="form-update-room-parts" class="col-sm-3 control-label">Parts</label>
        <div class="col-sm-6">
          <textarea rows="4" name="parts" id="form-update-room-parts" class="form-control code-font" placeholder="JSON array of objects" required></textarea>
        </div>
      </div>

      <div class="form-group required">
        <label for="form-update-room-tiledSurfaces" class="col-sm-3 control-label">Tiled surfaces</label>
        <div class="col-sm-6">
          <textarea rows="4" name="tiledSurfaces" id="form-update-room-tiledSurfaces" class="form-control code-font" placeholder="JSON array of objects" required></textarea>
        </div>
      </div>

      <div class="form-group">
        <label for="form-update-room-mirrors" class="col-sm-3 control-label">Mirrors</label>
        <div class="col-sm-6">
          <textarea rows="3" name="mirrors" id="form-update-room-mirrors" class="form-control code-font" placeholder="JSON array of objects"></textarea>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <button type="button" class="btn btn-default" onclick="deleteRoom(window.$('#form-update-room-id').val());" title="Remove Room"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
        <span class="pull-right">
          <button type="submit" class="btn btn-primary">Update room</button>
          <button type="reset" class="btn btn-default" onclick="$('#updateRoomFormBlock').slideUp();">Cancel</button>
        </span>
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

<div id="bigIconfileImageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="bigIconfileImageModalHeader" class="modal-title">Room image</h4>
      </div>
      <div class="modal-body" style="text-align: center;">
        <img id="bigIconfileImageModalImg" src="" alt="" style="max-width: 512px; max-height: 512px;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="panel panel-default">
  <div class="dropdown pull-right">
    <button class="btn btn-default btn-sm" onclick="addRoom()">+ Add room</button>
    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
      With selected
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" onclick="enableSelectedRooms();">Enable</a></li>
      <li><a href="#" onclick="disableSelectedRooms();">Disable</a></li>
      <li class="divider"></li>
      <li><a href="#" onclick="deleteSelectedRooms();">Remove</a></li>
    </ul>
  </div>

  <h3 class="panel-heading">3D Rooms list</h3>

  <div class="panel-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Room</th>
          <th>Name</th>
          <th>Category</th>
          <th>UseMirrors</th>
          <th>MapSize</th>
          <th>SourcesPath</th>
          <th>Link</th>
          <th>Enabled</th>
          <th>&nbsp;</th>
        </tr>
      </thead>

      <tbody>
        @if (count($rooms) > 0)
        @foreach ($rooms as $room)
        <tr @if (!$room->enabled) style="opacity: 0.5;" @endif>
          <td class="table-text">
            <input type="checkbox" name="" value="{{ $room->id }}" onchange="addCheckedRoom(this.value, this.checked);">
          </td>
          <td class="table-text">
            <img src="{{ $room->iconfile }}" alt="" class="img-thumbnail" style="max-width: 64px; max-height: 64px; cursor: pointer;" onclick="showBigIconfileImageModal('{{ $room->name }}', this.src)">
          </td>
          <td class="table-text bold"><a href="#" onclick="editRoom( {{ $room->id }} )" title="Edit">{{ $room->name }}</a></td>
          <td class="table-text"> @if (isset($roomTypes[$room->type])) {{ $roomTypes[$room->type] }} @else {{ $room->type }} @endif </td>
          <td class="table-text">@if ($room->useMirrors) Yes @else No @endif</td>
          <td class="table-text">{{ $room->mapSize }}</td>
          <td class="table-text" title="{{ $room->sourcesPath }}">... {{ substr($room->sourcesPath, 12, -1) }}</td>
          <td class="table-text"><a href="/room3d/{{ $room->id }}" title="/room3d/{{ $room->id }}"><img src="/img/icons/3d.png" alt="" width="32"></a></td>
          <td class="table-text">@if ($room->enabled) Yes @else No @endif</td>
          <td class="table-text">
            <button type="button" class="close" onclick="deleteRoom({{ $room->id }})" title="Remove Room">&times;</button>
          </td>
        </tr>
        @endforeach
        @else
          No one room found.
        @endif
      </tbody>
    </table>
    <div class="page-links" style="text-align: center;">{{ $rooms->links() }}</div>
  </div>
</div>

@endsection
