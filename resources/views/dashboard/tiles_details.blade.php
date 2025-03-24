@if( isset($processedTiles))
    @foreach($processedTiles as $aTiles)
        <tr>
            <td><img class="img-lg mb-md-0 mr-2" src="{{$aTiles['photo']}}" alt="profile image" style="border-radius: 10px;"></td>
            <td>{{$aTiles['name']}}</td>
            <td>{{$aTiles['size']}}</td>
            <td>{{ucfirst($aTiles['finish'])}}</td>
            <td>{{$aTiles['category']}}</td>
            <td>{{$aTiles['innovation']}}</td>
            <td>{{$aTiles['color']}}</td>
            <td>{{$aTiles['floor_count'] }}</td>
            <td>{{$aTiles['wall_count']}}</td>
            <td>{{$aTiles['counter_count']}}</td>
        </tr>
    @endforeach
@endif