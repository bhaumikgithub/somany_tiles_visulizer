@if( isset($appliedTiles))
    @foreach($appliedTiles as $tile)
        <tr>
            <td><img class="img-lg mb-md-0 mr-2" src="{{$tile['photo']}}" alt="profile image" style="border-radius: 10px;"></td>
            <td>{{ $tile['name'] }}</td>
            <td>{{ ucwords($tile['finish']) }}</td>
            <td>{{ $tile['room_names'] }}</td>
            <td>{{ ucwords(str_replace("_"," ",$tile['category'])) }}</td>
            <td>{{ $tile['used_count'] }}</td>
        </tr>
    @endforeach
@endif