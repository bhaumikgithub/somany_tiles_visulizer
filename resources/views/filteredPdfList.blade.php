@if (count($usersData) > 0)
@foreach ($usersData as $user)
<tr>
  <td class="table-text"><a href="#"  title="Name">{{ $user->name }}</a></td>
  <td class="table-text">{{ $user->user_account }} </td>
  <td class="table-text">{{ $user->mobile }}</td>
  <td class="table-text">{{ $user->pincode }}</td>
  <td class="table-text">
    <a href="/pdf-summary/{{ $user->unique_id }}?readonly=true&name={{ base64_encode($user->name) }}" target="_blank">{{ $user->unique_id }}</a>
  </td>
  <td class="table-text">{{ $user->date->format('d-m-y') }}</td>
</tr>
@endforeach
@else
  No one User found.
@endif