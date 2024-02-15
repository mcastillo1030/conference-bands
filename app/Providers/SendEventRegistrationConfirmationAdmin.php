<?php

namespace App\Providers;

use App\Mail\EventRegistrationConfirmationAdmin;
use App\Providers\EventRegistrationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEventRegistrationConfirmationAdmin
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
    public function handle(EventRegistrationCreated $event): void
    {
        // generate qr code
        $event->eventRegistration->generateQrCode();

        Mail::to(config('mail.from.address'))
            ->send(new EventRegistrationConfirmationAdmin($event->eventRegistration));
    }
}
