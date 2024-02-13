<x-mail::message>
# New Event Registration

Someone has registered for an upcoming event. Below are the details of the order

<x-mail::panel>
## Registration ID {{ $registration->registration_id }}

### __Name:__ {{ $registration->customer->fullName() }}
### __Registration Date:__ {{ $registration->created_at->format('F j, Y') }}
### __Event Name:__ {{ $registration->name }}
### __Number of Guests:__ {{ $registration->guests }}
</x-mail::panel>

You can view the full details of this registration by clicking the button below.

<x-mail::button :url="route('registrations.show', $registration->id)">
View Details
</x-mail::button>

</x-mail::message>
