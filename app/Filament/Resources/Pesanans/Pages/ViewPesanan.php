<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class ViewPesanan extends ViewRecord
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Ubah Status'),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pesanan')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('kode_pesanan')
                            ->label('Kode Pesanan')
                            ->fontFamily('mono')
                            ->copyable(),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'menunggu_pembayaran' => 'warning',
                                'diproses'            => 'info',
                                'siap_kirim'          => 'primary',
                                'selesai'             => 'success',
                                'dibatalkan'          => 'danger',
                                default               => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'menunggu_pembayaran' => 'Menunggu Bayar',
                                'diproses'            => 'Diproses',
                                'siap_kirim'          => 'Siap Kirim',
                                'selesai'             => 'Selesai',
                                'dibatalkan'          => 'Dibatalkan',
                                default               => $state,
                            }),

                        TextEntry::make('created_at')
                            ->label('Tanggal Order')
                            ->dateTime('d M Y, H:i')
                            ->timezone('Asia/Jakarta'),

                        TextEntry::make('total_harga')
                            ->label('Total Harga')
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                        TextEntry::make('biaya_ongkir')
                            ->label('Ongkos Kirim')
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),

                        TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                            ->weight('bold')
                            ->color('success'),

                        TextEntry::make('batas_waktu_bayar')
                            ->label('Batas Waktu Bayar')
                            ->dateTime('d M Y, H:i')
                            ->timezone('Asia/Jakarta'),

                        TextEntry::make('catatan_pembeli')
                            ->label('Catatan Pembeli')
                            ->default('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Data Pelanggan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('pelanggan.nama')->label('Nama'),
                        TextEntry::make('pelanggan.no_hp')->label('No. HP'),
                        TextEntry::make('pelanggan.email')->label('Email'),
                        TextEntry::make('pelanggan.alamat')->label('Alamat')->columnSpanFull(),
                    ]),

                Section::make('Item Pesanan')
                    ->schema([
                        RepeatableEntry::make('detailItems')
                            ->label('')
                            ->schema([
                                TextEntry::make('produk.nama')->label('Produk'),
                                TextEntry::make('jumlah')->label('Jumlah'),
                                TextEntry::make('harga_satuan')
                                    ->label('Harga Satuan')
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                            ])
                            ->columns(4),
                    ]),

                Section::make('Riwayat Pembayaran')
                    ->schema([
                        RepeatableEntry::make('riwayatPembayaran')
                            ->label('')
                            ->schema([
                                TextEntry::make('metode')->label('Metode')->badge(),
                                TextEntry::make('jumlah_dibayar')
                                    ->label('Jumlah')
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn ($state) => match ($state) {
                                        'lunas'    => 'success',
                                        'ditolak'  => 'danger',
                                        'menunggu' => 'warning',
                                        default    => 'gray',
                                    })
                                    ->formatStateUsing(fn ($state) => match ($state) {
                                        'lunas'    => 'Lunas',
                                        'ditolak'  => 'Ditolak',
                                        'menunggu' => 'Menunggu Verifikasi',
                                        default    => $state,
                                    }),
                                TextEntry::make('waktu_dibayar')
                                    ->label('Waktu Dibayar')
                                    ->dateTime('d M Y, H:i')
                                    ->timezone('Asia/Jakarta'),
                                TextEntry::make('alasan_penolakan')
                                    ->label('Alasan Penolakan')
                                    ->default('-')
                                    ->color('danger'),
                            ])
                            ->columns(3),
                    ]),

                Section::make('Pengiriman')
                    ->collapsed()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('pengiriman.kurir')->label('Kurir')->default('-'),
                        TextEntry::make('pengiriman.no_resi')
                            ->label('No. Resi')
                            ->fontFamily('mono')
                            ->copyable()
                            ->default('-'),
                        TextEntry::make('pengiriman.status_pengiriman')
                            ->label('Status Pengiriman')
                            ->badge()
                            ->default('-'),
                    ]),
            ]);
    }
}
