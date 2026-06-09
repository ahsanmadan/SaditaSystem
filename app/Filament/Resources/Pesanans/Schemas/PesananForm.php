<?php

namespace App\Filament\Resources\Pesanans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pesanan')
                    ->description('Data ini hanya boleh diubah oleh admin dengan alasan jelas.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode_pesanan')
                            ->label('Kode Pesanan')
                            ->disabled()
                            ->dehydrated(false),

                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'diproses' => 'Diproses',
                                'siap_kirim' => 'Siap Kirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->required(),

                        TextInput::make('total_harga')
                            ->label('Total Harga (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('biaya_ongkir')
                            ->label('Biaya Ongkir (Rp)')
                            ->numeric()
                            ->prefix('Rp'),

                        TextInput::make('kodePromo.kode')
                            ->label('Kode Promo')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('diskon')
                            ->label('Potongan Diskon (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('grand_total')
                            ->label('Grand Total (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('batas_waktu_bayar')
                            ->label('Batas Waktu Bayar')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('catatan_pembeli')
                            ->label('Catatan Pembeli')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
