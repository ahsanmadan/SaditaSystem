<?php

namespace App\Services;

use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfService
{
    public function filename(Pesanan $order): string
    {
        return 'invoice-'.$order->kode_pesanan.'.pdf';
    }

    public function output(Pesanan $order): string
    {
        $order->loadMissing([
            'pelanggan',
            'detailItems.produk',
            'pengiriman',
            'kodePromo',
            'pembayaranTerakhir',
        ]);

        return Pdf::loadView('pdf.invoice', [
            'order' => $order,
        ])->setPaper('a4')->output();
    }
}
