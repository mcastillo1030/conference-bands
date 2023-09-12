<x-mail::message>
# Order Cancelled

An order has been cancelled. Below are the details of the order:

<x-mail::panel>
## Order Number {{ Str::upper($order->number) }}

### __Name:__ {{ $order->customer->fullName() }}
### __Order Date:__ {{ $order->created_at->format('F j, Y') }}

### __Bracelets:__ {{ $order->bracelets->count() }}
<x-mail::table>
| Bracelet Number(s) | Bracelet Group |
| :-------------| :---------- |
@foreach ($order->bracelets as $bracelet)
| {{ $bracelet->number }} | {{ $bracelet->group ?? 'n/a' }} |
@endforeach
</x-mail::table>

</x-mail::panel>

You can view the full details of this order by clicking the button below.

<x-mail::button :url="route('orders.show', $order->number)">
View Order
</x-mail::button>

</x-mail::message>
