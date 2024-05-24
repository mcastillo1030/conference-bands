<x-mail::message>
# Thank you for registering for {{ $registration->name }}!

This is your confirmation that you registered for the upcoming event. Below are the details of your registration:

<x-mail::panel>
## Confirmation Number _{{ $registration->registration_id }}_

### __Event Date:__ {{ Carbon\Carbon::parse($registration->event_date, 'America/New_York')->format('F j, Y h:ia') }}
### __Event Location:__ {{ $registration->event_location }}
### __Name:__ {{ $registration->customer->fullName() }}<br>
### __Number of Guests:__ {{ $registration->guests }}
</x-mail::panel>

On the day of the event, please show the QR code below to check in.

<p style="text-align: center;">
    <img src="{{ $registration->getQrCode() }}" style="margin-left: auto; margin-right: auto;" /><br>
</p>

If you have any questions, please contact us at [info@revivalmovementusa.org](mailto:info@revivalmovementusa.org)

Thanks,<br>
The Revival Movement Team
</x-mail::message>
