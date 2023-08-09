<?php

namespace App\Listeners;

use App\Events\JetstreamuserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Password;

class SendNewUserPasswordEmail
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
    public function handle(JetstreamuserCreated $event): void
    {
        Password::sendResetLink(['email' => $event->user->email]);
    }
}
