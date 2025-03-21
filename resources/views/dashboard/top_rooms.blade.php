@if(isset($topFiveUsedRooms) && count($topFiveUsedRooms) > 0)
    @foreach($topFiveUsedRooms as $aTopRooms)
        <div class="col-sm-12">
            <div class="d-flex justify-content-between mb-4">
                <div>{{$aTopRooms['name']}}<br>
                    <span class="text-muted">{{$aTopRooms['category']}}</span>
                </div>
                <div>{{$aTopRooms['count']}}</div>
            </div>

            <div class="progress progress-md mt-4">
                <div class="progress-bar {{$aTopRooms['bg_color']}}" role="progressbar"
                     style="width: {{$aTopRooms['percentage']}}%"
                     aria-valuenow="{{$aTopRooms['percentage']}}" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <hr>
        </div>
    @endforeach
    <div class="col-12 mt-2" style="text-align: right;">
        <a href="#">View All ></a>
    </div>
@endif