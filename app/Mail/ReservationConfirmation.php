<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reservation $reservation,
        public ?string $motDePasse = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre réservation — Hôtel Excellence #' . str_pad($this->reservation->id, 6, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reservation-confirmation',
            with: [
                'reservation' => $this->reservation,
                'motDePasse'  => $this->motDePasse,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
