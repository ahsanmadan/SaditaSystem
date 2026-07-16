<?php

namespace App\Mail;

use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmedMail extends Mailable
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

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📦 PEMBAYARAN DIKONFIRMASI - SADITA ('.($this->pesanan->kode_pesanan ?? '-').')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_confirmed',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
