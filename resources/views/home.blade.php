@extends('layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@include('common.alerts')
@include('common.errors')


<saved-rooms></saved-rooms>

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">

      <form id="savedRoomsForm" action="" method="POST">
        {{ csrf_field() }}
        <input id="savedRoomsFormInput" type="hidden" name="selectedSavedRooms" value="">
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
              <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="window.$('#savedRoomsForm').submit();">Confirm</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">

        <div class="dropdown pull-right">
          <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            With selected
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="#" onclick="HomePage.deleteSelectedSavedRooms();">Remove</a></li>
          </ul>
        </div>

        <h3 class="panel-heading">Saved rooms list</h3>
        <!-- <div class="contanier">
          <div class="row">
            <div class="col-md-12 text-right">
              <input type="text" name="search_rooms" id="search_rooms" placeholder="Search by Tiles" />
            </div>
          </div>
        </div> -->

        @if (count($savedRooms) > 0)

        <div class="panel-body">
          <table id="example" class="table table-striped table-hover example" style="width: 100%;"> 
            <thead>
              <tr>
                <!-- <th colspan="2">Room</th>
                      <th>Url</th>
                      <th colspan="3">Action</th> -->
                <th>Room</th>
                <th>Image</th>
                <th>Url</th>
                <th>QR</th>
                <th>Tiles</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              {{-- @foreach ($savedRooms as $savedRoom)
              
              @if ($savedRoom->room)
              <tr>
                <td class="table-text">{{ $savedRoom->room->name }}</td>
                <td class="table-text">
                  <a href="/room/url/{{ $savedRoom->urlWithParams }}">
                    <img src="@if (isset($savedRoom->image)) {{ $savedRoom->image }} @else {{ $savedRoom->room->iconfile }} @endif" alt="" style="max-width: 128px; max-height: 100px;">
                  </a>
                </td>
                <td class="table-text">
                  <a href="/room/url/{{ $savedRoom->urlWithParams }}" title="/room/url/{{ $savedRoom->url }}" target="_blank">
                    @if (!config('app.hide_engine_icon') && isset($savedRoom->engine))
                    @if ($savedRoom->engine == '2d')<img src="/img/icons/2d.png" alt="" width="32">@endif
                    @if ($savedRoom->engine == '3d')<img src="/img/icons/3d.png" alt="" width="32">@endif
                    @if ($savedRoom->engine == 'panorama')<img src="/img/icons/panorama.png" alt="" width="32">@endif
                    @endif
                    {{ $savedRoom->url }}
                    @if ($savedRoom->engine == 'panorama' && $savedRoom->note == 'backed') (backed)@endif
                  </a>
                </td>
                <td class="table-text">
                  {{ $savedRoom->tile_names }}
                </td>
                <td class="table-text">
                  <input type="checkbox" name="" value="{{ $savedRoom->id }}" onchange="HomePage.addCheckedSavedRoom(this.value, this.checked);">
                  <button type="button" class="close" onclick="HomePage.deleteSavedRoom({{ $savedRoom->id }})" title="Remove Room">&times;</button>
                </td>
              </tr>
              @endif
              @endforeach --}}
            </tbody>
          </table>
        </div>

        <!-- inject QR codes into the table above -->
        <script src="/js/qrcode.js"></script>
       
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
@push('custom-scripts')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
{{-- <script>
    // new DataTable('#example');
    $('#example').DataTable({
      "bLengthChange" : false, //thought this line could hide the LengthMenu
      "pageLength": 20,
    });
</script> --}}

<script>
  $(document).ready(function(){
    savedroomajax();
  })

   function savedroomajax()
  {
    var room_simple_table = $('.example');
    if(room_simple_table.length)
    {
      var dt_basic = room_simple_table.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                        url: '{{ route('json.data') }}',
                       
                        "dataSrc": function ( json ) {
                      //Make your callback here.
                  
                      setTimeout(() => {
                        $('table tbody').html(json.aaData)
                        QRfunction();
                      }, 100);
                     
                      // alert("Done!");
                      // QRfunction();
                

                   
                                // return json.aaData;
                    }  
                },
                columns: [
                  {
                            data: 'room',
                        },
                        {
                            data: 'image',
                        },
                        {
                            data: 'url',
                        },
                        {
                            data: 'titles',
                        },
                        {
                            data: 'id'
                        },
                    ],
                    columnDefs: [
                      {
                        targets: 0,
                            render: function(data, type, full, meta) {
                            
                                $name= full['room_name'];
                                return (
                                   $name
                                );

                            },
                      },
                      {
                        targets: 1,
                            render: function(data, type, full, meta) {
                            
                                $images='<a href="'+full['room_url']+'"><img src="'+full['room_image']+'" alt="" style="max-width: 128px; max-height: 100px;"></a>';
                                return (
                                   $images
                                );

                            },
                      },
                      {
                        targets: 2,
                            render: function(data, type, full, meta) {
                          
                                $url='<a href="'+full['room_url']+'" title="'+full['url_url']+'" target="_blank">'+full['url_image']+''+full['url']+''+full['url_image_another_text']+'</a>';
                              
                                return (
                                   $url

                                )
                            },
                      },
                     
                      {
                        targets: 3,
                            render: function(data, type, full, meta) {
                            
                                $tilename= full['tilename'];
                                return (
                                   $tilename
                                );

                            },
                      },

                      {
                        targets: 4,
                            render: function(data, type, full, meta) {
                            
                                $action=' <input type="checkbox" name="" value="'+full['id']+'" onchange="HomePage.addCheckedSavedRoom(this.value, this.checked);"><button type="button" class="close" onclick="HomePage.deleteSavedRoom('+full['id']+')" title="Remove Room">&times;</button>';
                                return (
                                   $action
                                );

                            },
                      },

                     
                     
                    ],
                    displayLength: 20,
                    lengthMenu: [
                        [5, 10, 25, 50, 100, -1],
                        [5, 10, 25, 50, 100, "All"]
                    ],
                    "language": {
                'loadingRecords': '&nbsp;',
                'processing': 'Loading...'
            },

                   
                  
      });

      $('input[type="search"]').keyup(function(){
        console.log($(this).val());
        dt_basic.search($(this).val()).draw();
    });
      // setTimeout(() => {
      //  QRfunction();
      // }, 1000);
    
    }
    // console.log("done!!!")
    // setTimeout(() => {
    //   QRfunction();
    //   }, 1000);
     
  }
</script>

<script>
  function QRfunction()
  {
    var table=$('table tbody tr');
        $.each(table, function (index, data) {
            // alert(data.buckets);
            
            const td = data.querySelectorAll('td')[2];
            const qr_td = data.querySelectorAll('td')[3];
            const a = td.querySelector('a');
            const qr = qrcode(5, 'L');
            qr.addData(a.href);
            qr.make();
            td.insertAdjacentHTML('afterend', '<td class="imgsize">' + qr.createImgTag() + '</td>');
            const e = encodeURIComponent(a.href);
            a.insertAdjacentHTML('afterend', `<div>
            <a href="mailto:?subject=TileVisualizer+room+share+link&amp;body=Room+share+link%20${e}" title="E-mail Share" target="_blank" class="btn btn-email">
                <img src="/img/icons/mail-x26.png" alt="">
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=${e}" title="Facebook Share" target="_blank" class="btn btn-facebook">
                <img src="/img/icons/facebook.png" alt="">
            </a>
            <a href="https://twitter.com/intent/tweet?url=${e}" title="Twitter Share" target="_blank" class="btn btn-twitter">
                <img src="/img/icons/twitter.png" alt="">
            </a>
            <a href="https://wa.me/?text=TileVisualizer+room+share+link%20${e}" title="Whatsapp Share" target="_blank" class="btn btn-whatsapp">
                <img src="/img/icons/whatsapp.png" alt="">
            </a>
          </div>`);
           
        })
  }
</script>
<script>
 
 

 
</script>

<!-- <script>
  $(function() {
    var minlength = 3;
    $("#search_rooms").on("keyup", function() {
      var that = this,
        value = $(this).val();
        console.log('value', value);

      if (value.length >= minlength) {
        $.ajax({
          type: "GET",
          url: "{{ route('search-tiles') }}",
          data: {
            'search_keyword': value
          },
          dataType: "text",
          success: function(msg) {
            //we need to check if the value is the same
            if (value == $(that).val()) {
              //Receiving the result of search here
            }
          }
        });
      }
    });
  });
</script> -->
@endpush