<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockBajoNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    public $productosCriticos;
    public $productosAgotados;

    /**
     * Create a new message instance.
     */
    public function __construct($productosCriticos, $productosAgotados)
    {
        $this->productosCriticos = $productosCriticos;
        $this->productosAgotados = $productosAgotados;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Alerta de Stock Bajo - DiscZone',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.stock-bajo',
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
