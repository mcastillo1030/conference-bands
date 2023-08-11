<x-mail::message>
# Thank you for registering for Revival Conference 2023!

As you requested, we're re-sending you the confirmation of your order. Below are the details of your order

<x-mail::panel>
## Confirmation Number {{ Str::upper($order->number) }}

### __Name:__ {{ $order->customer->fullName() }}
### __Order Date:__ {{ $order->created_at->format('F j, Y') }}

### __Bracelets:__ {{ $order->bracelets->count() }}
<x-mail::table>
| Bracelet Numbers    |
|:------------- |
@foreach ($order->bracelets as $bracelet)
| {{ $bracelet->number }} {{$bracelet->name ?? ''}} |
@endforeach
</x-mail::table>

</x-mail::panel>

If you have any questions, please contact us at [info@revivalmovementusa.org](mailto:info@revivalmovementusa.org)

Thanks,<br>
The Revival Movement Team
</x-mail::message>
