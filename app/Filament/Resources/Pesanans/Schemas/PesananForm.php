<?php

namespace App\Filament\Resources\Pesanans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        $recalculateGrandTotal = function (callable $get, callable $set) {
            $subtotal = (int) $get('total_harga') ?: 0;
            $ongkir = (int) $get('biaya_ongkir') ?: 0;
            $potongan = (int) $get('potongan_diskon') ?: 0;
            $set('grand_total', max(0, $subtotal + $ongkir - $potongan));
        };

        return $schema
            ->components([
                Section::make('Informasi Pesanan')
                    ->description('Data ini dapat diisi saat pembuatan pesanan baru dan dipantau oleh admin.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode_pesanan')
                            ->label('Kode Pesanan')
                            ->disabled()
                            ->placeholder('Otomatis Dibuat')
                            ->dehydrated(false),

                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'diproses'            => 'Diproses',
                                'siap_kirim'          => 'Siap Kirim',
                                'selesai'             => 'Selesai',
                                'dibatalkan'          => 'Dibatalkan',
                            ])
                            ->required(),

                        TextInput::make('total_harga')
                            ->label('Total Harga (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit')
                            ->dehydrated()
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) use ($recalculateGrandTotal) {
                                // Re-evaluate discount if a coupon is selected
                                $diskonId = $get('diskon_id');
                                if ($diskonId) {
                                    $diskon = \App\Models\Diskon::find($diskonId);
                                    if ($diskon) {
                                        $potongan = $diskon->calculateDiscount((int) $state);
                                        $set('potongan_diskon', $potongan);
                                    }
                                }
                                $recalculateGrandTotal($get, $set);
                            }),

                        TextInput::make('biaya_ongkir')
                            ->label('Biaya Ongkir (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->live(debounce: 500)
                            ->afterStateUpdated($recalculateGrandTotal),

                        Select::make('diskon_id')
                            ->label('Pilih Kupon Diskon')
                            ->relationship('diskon', 'kode', fn ($query) => $query->where('is_aktif', true))
                            ->placeholder('Pilih Kupon Promo (Opsional)')
                            ->disabled(fn ($context) => $context === 'edit')
                            ->dehydrated()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) use ($recalculateGrandTotal) {
                                if (!$state) {
                                    $set('kode_diskon', null);
                                    $set('potongan_diskon', 0);
                                    $recalculateGrandTotal($get, $set);
                                    return;
                                }
                                $diskon = \App\Models\Diskon::find($state);
                                if ($diskon) {
                                    $set('kode_diskon', $diskon->kode);
                                    $subtotal = (int) $get('total_harga') ?: 0;
                                    $potongan = $diskon->calculateDiscount($subtotal);
                                    $set('potongan_diskon', $potongan);
                                }
                                $recalculateGrandTotal($get, $set);
                            }),

                        TextInput::make('kode_diskon')
                            ->label('Kode Diskon Snapshot')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('potongan_diskon')
                            ->label('Potongan Diskon (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->disabled(fn ($context) => $context === 'edit')
                            ->dehydrated()
                            ->live(debounce: 500)
                            ->afterStateUpdated($recalculateGrandTotal),

                        TextInput::make('grand_total')
                            ->label('Grand Total (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),

                        DateTimePicker::make('batas_waktu_bayar')
                            ->label('Batas Waktu Bayar')
                            ->native(false)
                            ->disabled(fn ($context) => $context === 'edit')
                            ->dehydrated(fn ($state) => filled($state)),

                        Textarea::make('catatan_pembeli')
                            ->label('Catatan Pembeli')
                            ->rows(3)
                            ->disabled(fn ($context) => $context === 'edit')
                            ->dehydrated(fn ($state) => filled($state))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
