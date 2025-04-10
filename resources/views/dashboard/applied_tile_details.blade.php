@if( isset($processedTiles))
    @foreach($processedTiles as $aTiles)
    @php
        $zoneCounts = is_array($aTiles['zone_used_count']) ? $aTiles['zone_used_count'] : (array) $aTiles['zone_used_count'];
    @endphp
        <tr>
            <td><img class="img-lg mb-md-0 mr-2" src="{{$aTiles['photo']}}" alt="profile image" style="border-radius: 10px;"></td>
            <td>{{$aTiles['name']}}</td>
            <td>{{str_replace(" MM","",$aTiles['size'])}}</td>
            <td>{{ucfirst($aTiles['finish'])}}</td>
            <td>{{$aTiles['floor_count'] }}</td>
            <td>{{$aTiles['wall_count']}}</td>
            <td>{{$aTiles['counter_count']}}</td>
            <td>{{ $zoneCounts['central'] ?? "-" }}</td>
            <td>{{ $zoneCounts['east'] ?? "-" }}</td>
            <td>{{ $zoneCounts['west'] ?? "-" }}</td>
            <td>{{ $zoneCounts['north'] ?? "-" }}</td>
            <td>{{ $zoneCounts['south'] ?? "-" }}</td>
        </tr>
    @endforeach
@endif