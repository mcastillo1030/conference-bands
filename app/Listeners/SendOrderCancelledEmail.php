<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Mail\OrderCancelledGuest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderCancelledEmail
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
    public function handle(OrderCancelled $event): void
    {
        $sent = Mail::to($event->order->customer->email, $event->order->customer->fullName())
            ->send(new OrderCancelledGuest($event->order));
        if ($sent) {
            $event->order->notifications()->create([
                'type' => 'Order Cancelled Email',
            ]);
        }
    }
}
