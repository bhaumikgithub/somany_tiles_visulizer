@if( isset($pinCodeDetails))
    @foreach($pinCodeDetails as $aPincode)
        <tr>
            <td>{{$aPincode['zone']}}</td>
            <td>{{$aPincode['pincode']}}</td>
            <td>{{$aPincode['visits']}}</td>
            <td>{{ \Carbon\Carbon::parse($aPincode['last_visited_at'])->format('d-m-Y') }}</td> <!-- Format Date -->
        </tr>
    @endforeach
@endif