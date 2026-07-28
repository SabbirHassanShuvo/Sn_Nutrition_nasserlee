<?php

namespace App\Mail;

use App\Models\ConsultationBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationBookingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public ConsultationBooking $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(ConsultationBooking $booking)
    {
        $this->booking = $booking->load('specialist');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $callTypeName = $this->booking->call_type === 'video_call' ? 'Video Call' : 'Callback Session';
        return new Envelope(
            subject: "Your Consultation Booking (#{$this->booking->booking_number}) is Confirmed - Join Link Included",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.consultation_approved',
            with: [
                'booking' => $this->booking,
                'specialist' => $this->booking->specialist,
            ]
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
