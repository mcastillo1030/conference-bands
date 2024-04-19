<table>
    <thead>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Number</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registrations as $registration)
            <tr>
                <td>{{$registration->name}}</td>
                <td>{{$registration->created_at->format('m/d/Y H:m a')}}</td>
                <td>{{$registration->registration_id}}</td>
                <td>{{$registration->customer->first_name}}</td>
                <td>{{$registration->customer->last_name}}</td>
                <td>{{$registration->customer->email}}</td>
                <td>{{$registration->customer->phone_number}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
