<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Mail\OrderCancelledAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderCancelledAdmin
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
        Mail::to(config('mail.from.address'))
            ->send(new OrderCancelledAdmin($event->order));
    }
}
