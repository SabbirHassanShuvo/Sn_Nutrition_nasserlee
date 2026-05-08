<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserSuspensionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;
    public $isSuspended;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $reason, $isSuspended)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->isSuspended = $isSuspended;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isSuspended ? 'Your Account has been Suspended' : 'Your Account has been Unsuspended';
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user_suspension',
            with: [
                'user' => $this->user,
                'reason' => $this->reason,
                'isSuspended' => $this->isSuspended,
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
