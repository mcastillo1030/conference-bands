<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SquareNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Order
     */
    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Revival Conference 20203: Payment Needed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $subtotal = $this->order->bracelets()->count() * config('constants.square.bracelet_cost');
        $total    = $subtotal + ($subtotal * config('constants.square.transaction_fee')) + config('constants.square.transaction_fee_fixed');

        return new Content(
            markdown: 'emails.square-notification',
            with: [
                'order' => $this->order,
                'order_total' => number_format((float) $total, 2, '.', ''),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
