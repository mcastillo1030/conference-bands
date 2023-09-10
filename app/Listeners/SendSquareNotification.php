<?php

namespace App\Listeners;

use App\Events\SquareLinkGenerated;
use App\Mail\SquareNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendSquareNotification
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
    public function handle(SquareLinkGenerated $event): void
    {
        $sent = Mail::to($event->order->customer->email, $event->order->customer->fullName())
            ->send(new SquareNotification($event->order));
        if ($sent) {
            $event->order->notifications()->create([
                'type' => 'Order Needs Payment Email',
            ]);
        }
    }
}
