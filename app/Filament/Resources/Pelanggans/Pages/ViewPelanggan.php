<?php

namespace App\Filament\Resources\Pelanggans\Pages;

use App\Filament\Resources\Pelanggans\PelangganResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewPelanggan extends ViewRecord
{
    protected static string $resource = PelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Edit Data'),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Pelanggan')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nama_lengkap')
                            ->label('Nama Lengkap'),

                        TextEntry::make('no_hp')
                            ->label('No. HP / WhatsApp')
                            ->fontFamily('mono')
                            ->copyable(),

                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable()
                            ->default('-'),

                        TextEntry::make('created_at')
                            ->label('Terdaftar Sejak')
                            ->dateTime('d M Y')
                            ->timezone('Asia/Jakarta'),
                    ]),

                Section::make('Statistik Pelanggan')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('total_pesanan')
                            ->label('Total Pesanan')
                            ->badge()
                            ->color('info')
                            ->state(fn ($record) => $record->riwayatPesanan()->count()),

                        TextEntry::make('pesanan_selesai')
                            ->label('Pesanan Selesai')
                            ->badge()
                            ->color('success')
                            ->state(fn ($record) => $record->riwayatPesanan()
                                ->where('status', 'selesai')->count()),

                        TextEntry::make('total_revenue')
                            ->label('Total Revenue')
                            ->state(fn ($record) => 'Rp '.number_format(
                                $record->riwayatPesanan()
                                    ->where('status', 'selesai')
                                    ->sum('grand_total'),
                                0, ',', '.'
                            ))
                            ->color('success')
                            ->weight('bold'),

                        TextEntry::make('last_order')
                            ->label('Order Terakhir')
                            ->state(fn ($record) => $record->riwayatPesanan()
                                ->latest()
                                ->first()?->created_at?->timezone('Asia/Jakarta')
                                ->format('d M Y') ?? '-'),
                    ]),

                Section::make('Riwayat Pesanan')
                    ->schema([
                        RepeatableEntry::make('riwayatPesanan')
                            ->label('')
                            ->schema([
                                TextEntry::make('kode_pesanan')
                                    ->label('Kode')
                                    ->fontFamily('mono')
                                    ->copyable(),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn ($state) => match ($state) {
                                        'menunggu_pembayaran' => 'warning',
                                        'diproses' => 'info',
                                        'siap_kirim' => 'primary',
                                        'selesai' => 'success',
                                        'dibatalkan' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn ($state) => match ($state) {
                                        'menunggu_pembayaran' => 'Menunggu Bayar',
                                        'diproses' => 'Diproses',
                                        'siap_kirim' => 'Siap Kirim',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                        default => $state,
                                    }),

                                TextEntry::make('grand_total')
                                    ->label('Grand Total')
                                    ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.')),

                                TextEntry::make('created_at')
                                    ->label('Tanggal Order')
                                    ->dateTime('d M Y')
                                    ->timezone('Asia/Jakarta'),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }
}
