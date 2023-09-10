<x-mail::message>
# Action required for your Revival Conference 2023 order.

Thank you for placing an order for Revival Conference 2023! We are excited to see you at the conference.

We are reaching out to you because we need you to confirm your order. Please click the button below to apply a payment and complete your order.

<x-mail::panel>
## Order Number _{{ Str::upper($order->number) }}_

### __Name:__ {{ $order->customer->fullName() }}<br>
### __Order Date:__ {{ $order->created_at->format('F j, Y') }}

<x-mail::table>
| Details        |
| :------------- |
| {{ $order->bracelets()->count() }} bracelets |
| __Total Due:__ ${{$order_total}} |
</x-mail::table>
</x-mail::panel>

<x-mail::button :url="$order->payment_link">
Make Payment
</x-mail::button>

If you have any questions, please contact us at [info@revivalmovementusa.org](mailto:info@revivalmovementusa.org)

Thanks,<br>
The Revival Movement Team
</x-mail::message>
