<x-mail::message>
# Your order is now cancelled

Thank you for considering attending the Revival Conference 2023: Chosen.

This email is to confirm that your order has been cancelled.

<x-mail::panel>
## Order Number _{{ Str::upper($order->number) }}_

### __Name:__ {{ $order->customer->fullName() }}<br>
### __Order Date:__ {{ $order->created_at->format('F j, Y') }}
</x-mail::panel>

If you have any questions, please contact us at [info@revivalmovementusa.org](mailto:info@revivalmovementusa.org)

Thanks,<br>
The Revival Movement Team
</x-mail::message>
