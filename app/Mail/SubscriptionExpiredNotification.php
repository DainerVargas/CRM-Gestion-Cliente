<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $accountName;
    public $expiryDate;

    /**
     * Create a new message instance.
     */
    public function __construct($clientName, $accountName, $expiryDate)
    {
        $this->clientName = $clientName;
        $this->accountName = $accountName;
        $this->expiryDate = $expiryDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu suscripción ha finalizado - Acción Requerida',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-expired',
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
