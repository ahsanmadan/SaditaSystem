<?php

namespace App\Mail;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $pembayaran;
    public $pesanan;

    /**
     * Create a new message instance.
     */
    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
        // Memuat relasi pesanan jika belum dimuat
        $this->pesanan = $pembayaran->relationLoaded('pesanan') ? $pembayaran->pesanan : $pembayaran->pesanan()->first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📦 PEMBAYARAN DIKONFIRMASI - SADITA ('.($this->pesanan->kode_pesanan ?? '-').')',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
