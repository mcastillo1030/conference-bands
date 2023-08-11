<?php

namespace App\Providers;

use App\Mail\OrderReconfirm;
use App\Providers\ConfirmationResend;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ResendConfirmation
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
    public function handle(ConfirmationResend $event): void
    {
        $sent = Mail::to($event->order->customer->email, $event->order->customer->fullName())
            ->send(new OrderReconfirm($event->order));

        if ($sent) {
            $event->order->notifications()->create([
                'type' => 'Order Confirmation Email Re-sent',
            ]);
        }
    }
}
