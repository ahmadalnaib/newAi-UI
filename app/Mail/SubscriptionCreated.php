<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $userName;
    public $invoiceUrl;
    public $planDetails;
    
    

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName, string $invoiceUrl, array $planDetails)
    {
        $this->userName = $userName;
        $this->invoiceUrl = $invoiceUrl;
        $this->planDetails = $planDetails;
    }



    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . $this->planDetails['name'] . ' Subscription',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscription.created',
            with: [
                'userName' => $this->userName,
                'invoiceUrl' => $this->invoiceUrl,
                'planDetails' => $this->planDetails,
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
