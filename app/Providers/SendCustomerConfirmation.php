<?php

namespace App\Providers;

use App\Mail\OrderCreatedGuest;
use App\Providers\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCustomerConfirmation
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
    public function handle(OrderCreated $event): void
    {

        $sent = Mail::to($event->order->customer->email, $event->order->customer->fullName())
            ->send(new OrderCreatedGuest($event->order));
        if ($sent) {
            $event->order->notifications()->create([
                'type' => 'Order Created Email',
            ]);
        }
    }
}
