<?php

namespace App\Mail;

use App\Models\Pesanan;
use App\Services\InvoicePdfService;
use App\Services\ReviewLinkService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerTrackingLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pesanan $order;

    public string $paymentMethodLabel;

    public ?string $checkoutUrl;

    public array $reviewLinks;

    public bool $includeInvoicePdf;

    protected ?string $invoicePdf = null;

    public function __construct(Pesanan $order, string $paymentMethodLabel, bool $includeInvoicePdf = false)
    {
        $this->order = $order;
        $this->paymentMethodLabel = $paymentMethodLabel;
        $this->checkoutUrl = $order->pembayaranTerakhir?->checkout_url;
        $this->reviewLinks = app(ReviewLinkService::class)->linksForOrder($order)->all();
        $this->includeInvoicePdf = $includeInvoicePdf;

        if ($includeInvoicePdf) {
            $this->invoicePdf = app(InvoicePdfService::class)->output($order);
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Link tracking pesanan Sadita - '.$this->order->kode_pesanan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer_tracking_link',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if (! $this->includeInvoicePdf || ! $this->invoicePdf) {
            return [];
        }

        return [
            Attachment::fromData(
                fn () => $this->invoicePdf,
                'invoice-'.$this->order->kode_pesanan.'.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
