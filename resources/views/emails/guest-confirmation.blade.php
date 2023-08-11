<x-mail::message>
# Thank you for registering for Revival Conference 2023!

This is your confirmation you registered your bracelet for the conference. Below are the details of your order

<x-mail::panel>
## Confirmation Number _{{ Str::upper($order->number) }}_

### __Name:__ {{ $order->customer->fullName() }}<br>
### __Order Date:__ {{ $order->created_at->format('F j, Y') }}

### __Bracelets:__ {{ $order->bracelets->count() }}
<x-mail::table>
| Bracelet Number(s)    |
| :------------- |
@foreach ($order->bracelets as $bracelet)
| {{ $bracelet->number }} |
@endforeach
</x-mail::table>

</x-mail::panel>

If you have any questions, please contact us at [info@revivalmovementusa.org](mailto:info@revivalmovementusa.org)

Thanks,<br>
The Revival Movement Team
</x-mail::message>
