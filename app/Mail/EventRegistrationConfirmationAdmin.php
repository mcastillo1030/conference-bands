<?php

namespace App\Mail;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * EventRegistrationConfirmationAdmin.
 *
 * @category Mailable
 * @package  App\Mail
 * @author   Marlon Castillo <mcastillo1030@github.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     <none>
 */
class EventRegistrationConfirmationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The event registration instance.
     *
     * @var \App\Models\EventRegistration
     */
    public $eventRegistration;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\EventRegistration $eventRegistration The event registration instance
     */
    public function __construct(EventRegistration $eventRegistration)
    {
        $this->eventRegistration = $eventRegistration;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Revival Movement - Event Registration Confirmation',
            from: new Address('info@revivalmovementusa.org', 'Revival Movement, Inc.')
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.registrations.admin-confirmation',
            with: [
                'registration' => $this->eventRegistration,
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
