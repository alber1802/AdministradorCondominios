<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NominaMail extends Mailable
{           
    use Queueable, SerializesModels;

    public $nomina;  

    /**
     * Create a new message instance.
     */
    public function __construct($nomina)
    {
        $this->nomina = $nomina;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Nómina de Pago - ' . now()->format('M Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nomina',
            with: [
                'nomina' => $this->nomina,
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
        $attachments = [];
        
        if ($this->nomina->comprobante_pdf && Storage::exists($this->nomina->comprobante_pdf)) {
            $attachments[] = Attachment::fromStorage($this->nomina->comprobante_pdf)
                ->as('nomina_' . $this->nomina->id . '.pdf')
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}
