<?php

namespace App\Mail;

use App\Models\Factura;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FacturaMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $factura;

    
    /**
     * Create a new message instance.
     */
    public function __construct(Factura $factura)
    {
        // Load necessary relationships for the email template
        $this->factura = $factura->load(['user', 'departamento']);
 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->getSubjectByStatus();
        
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
            view: 'mail.factura',
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

    /**
     * Generate dynamic subject based on invoice status
     */
    private function getSubjectByStatus(): string
    {
        $baseSubject = "Factura #{$this->factura->id} - {$this->factura->tipo}";
        
        return match($this->factura->estado) {
            'pendiente' => $baseSubject . ' - Recordatorio de Pago',
            'vencido' => $baseSubject . ' - URGENTE: Pago Vencido',
            'pagado' => $baseSubject . ' - Confirmación de Pago',
            default => $baseSubject . ' - Notificación'
        };
    }
}
