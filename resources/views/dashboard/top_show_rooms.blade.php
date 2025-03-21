@if(isset($topShowRooms) && count($topShowRooms) > 0)
    @foreach($topShowRooms as $aTopShowRooms)
        <div class="col-sm-12">
            <div class="d-flex justify-content-between mb-4">
                <div>{{$aTopShowRooms['name']}}<br>
                    <span class="text-muted">{{$aTopShowRooms['city']}}</span></div>
                <div>{{$aTopShowRooms['usage_count']}}</div>
            </div>

            <div class="progress progress-md mt-4">
                <div class="progress-bar {{$aTopShowRooms['bg_color']}}" role="progressbar" style="width: {{$aTopShowRooms['percentage']}}%" aria-valuenow="{{$aTopShowRooms['percentage']}}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <hr>
        </div>
    @endforeach
    <div class="col-12 mt-2" style="text-align: right;">
        <a href="#">View All ></a>
    </div>
@endif