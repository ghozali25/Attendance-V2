<table>
  <thead>
    <tr>
      <th>#</th>
      <th>NIP</th>
      <th>Name</th>
      <th>Email</th>
      <th>Group</th>
      <th>Phone</th>
      <th>Gender</th>
      <th>Basic Salary</th>
      <th>Hourly Rate</th>
      <th>Division</th>
      <th>Job Title</th>
      <th>Education</th>
      <th>Birth Date</th>
      <th>Birth Place</th>
      <th>Address</th>
      <th>City</th>
      <th>Created At</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($users as $user)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td data-type="s">{{ $user->nip }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->group }}</td>
        <td data-type="s">{{ $user->phone }}</td>
        <td>{{ $user->gender }}</td>
        <td>{{ $user->basic_salary }}</td>
        <td>{{ $user->hourly_rate }}</td>
        <td>{{ $user->division?->name }}</td>
        <td>{{ $user->jobTitle?->name }}</td>
        <td>{{ $user->education?->name }}</td>
        <td>{{ $user->birth_date?->format('Y-m-d') }}</td>
        <td>{{ $user->birth_place }}</td>
        <td>{{ $user->address }}</td>
        <td>{{ $user->city }}</td>
        <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
