@if(isset($rooms))
    @foreach($rooms as $room)
        <tr>
            <td>{{ $room['room_name'] }}</td>
            <td>{{ $room['category_name'] }}</td>
            <td>{{ $room['used_count'] }}</td>
        </tr>
    @endforeach
@endif