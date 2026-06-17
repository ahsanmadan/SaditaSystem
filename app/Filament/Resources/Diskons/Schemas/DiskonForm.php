<?php

namespace App\Filament\Resources\Diskons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DiskonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Diskon & Promo')
                    ->description('Atur kode diskon, tipe potongan, dan syarat penggunaan.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode Promo')
                            ->required()
                            ->maxLength(50)
                            ->unique(table: 'diskon', column: 'kode', ignoreRecord: true)
                            ->placeholder('Contoh: SADITA10')
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state)),

                        TextInput::make('nama')
                            ->label('Nama Promo')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: Promo Pembukaan Toko'),

                        Select::make('tipe')
                            ->label('Tipe Potongan')
                            ->options([
                                'persen' => 'Persentase (%)',
                                'nominal' => 'Nominal Rupiah (Rp)',
                            ])
                            ->default('nominal')
                            ->required()
                            ->live(),

                        TextInput::make('nilai')
                            ->label('Nilai Potongan')
                            ->numeric()
                            ->required()
                            ->prefix(fn (callable $get) => $get('tipe') === 'nominal' ? 'Rp' : null)
                            ->suffix(fn (callable $get) => $get('tipe') === 'persen' ? '%' : null)
                            ->placeholder(fn (callable $get) => $get('tipe') === 'persen' ? 'Contoh: 10' : 'Contoh: 50000'),

                        TextInput::make('minimal_pembelian')
                            ->label('Minimal Pembelian (Rp)')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->helperText('Pelanggan harus belanja minimal sebesar jumlah ini agar promo bisa digunakan.'),

                        TextInput::make('maksimal_potongan')
                            ->label('Maksimal Potongan (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->helperText('Batas maksimum potongan (hanya berfungsi jika tipe potongan adalah persentase).'),

                        TextInput::make('kuota')
                            ->label('Kuota Penggunaan')
                            ->numeric()
                            ->placeholder('Contoh: 100')
                            ->helperText('Batas berapa kali promo ini dapat digunakan secara keseluruhan. Kosongkan jika tanpa batas.'),

                        TextInput::make('digunakan')
                            ->label('Sudah Digunakan')
                            ->numeric()
                            ->disabled()
                            ->default(0)
                            ->dehydrated(false),

                        DateTimePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->native(false),

                        DateTimePicker::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->native(false),

                        Toggle::make('is_aktif')
                            ->label('Promo Aktif')
                            ->default(true)
                            ->inline(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
