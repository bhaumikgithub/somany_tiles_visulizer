@if(isset($pdfDownloads))
    @foreach($pdfDownloads as $aPdf)
        <tr>
            <td>{{$aPdf->name}}</td>
            <td>{{$aPdf->user_account}}</td>
            <td>{{$aPdf->mobile}}</td>
            <td>{{$aPdf->pincode}}</td>
            <td><a href="/pdf-summary/{{ $aPdf->unique_id }}?readonly=true&name={{ base64_encode($aPdf->name) }}" target="_blank">{{ $aPdf->unique_id }}</a></td>
            <td>{{$aPdf->created_at->format('d-m-y')}}</td>
        </tr>
    @endforeach
@endif