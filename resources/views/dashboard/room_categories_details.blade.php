@if(isset($categoryData))
    @foreach($categoryData as $category)
        <tr>
            <td>{{ $category['category_name'] }}</td>
            <td>{{ $category['visits'] }}</td>
        </tr>
    @endforeach
@endif