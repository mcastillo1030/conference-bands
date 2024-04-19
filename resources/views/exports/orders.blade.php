<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Number</th>
            <th>Type</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Bracelets</th>
            <th>Link</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{$order->created_at->format('m/d/Y H:m a')}}</td>
                <td>{{$order->number}}</td>
                <td>{{$order->order_type}}</td>
                <td>{{$order->customer->first_name}}</td>
                <td>{{$order->customer->last_name}}</td>
                <td>{{$order->bracelets->count()}}</td>
                <td>{{$order->order_type !== 'online' ? '' : $order->payment_link}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
