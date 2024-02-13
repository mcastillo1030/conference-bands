<?php

namespace App\Providers;

use App\Mail\EventRegistrationConfirmationCustomer;
use App\Providers\EventRegistrationResend;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ResendEventRegistrationConfirmation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventRegistrationResend $event): void
    {
        if ($event->eventRegistration->customer->email === null) {
            return;
        }

        $sent = Mail::to($event->eventRegistration->customer->email, $event->eventRegistration->customer->fullName())
            ->send(new EventRegistrationConfirmationCustomer($event->eventRegistration));
        if ($sent) {
            $event->eventRegistration->notifications()->create([
                'notification_type' => 'Event Registration Confirmation Email',
            ]);
        }
    }
}
