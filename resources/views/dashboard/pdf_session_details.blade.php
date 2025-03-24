@if(isset($user_analytics))
    <tr style="text-align:center">
        <td>{{ $user_analytics['guest_users'] }}</td>
        <td>{{ $user_analytics['logged_in_users'] }}</td>
        <td>{{ $user_analytics['total_users'] }}</td>
        <td>{{ $user_analytics['generated_summary_pages'] }}</td>
        <td>{{ $user_analytics['pdf_downloads'] }}</td>
    </tr>
@endif