<?php

namespace App\Filament\Resources\KodePromos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KodePromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kode Promo')
                    ->description('Buat kode promo baru untuk promosi pemasaran.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode Promo')
                            ->required()
                            ->maxLength(50)
                            ->unique(table: 'kode_promo', column: 'kode', ignoreRecord: true)
                            ->dehydrateStateUsing(fn ($state) => strtoupper(trim($state)))
                            ->helperText('Contoh: SADITA10, HEMAT50K. Kode akan otomatis diubah ke huruf kapital.'),

                        Select::make('tipe_diskon')
                            ->label('Tipe Diskon')
                            ->options([
                                'persentase' => 'Persentase (%)',
                                'nominal' => 'Nominal (Rupiah)',
                            ])
                            ->required(),

                        TextInput::make('nilai_diskon')
                            ->label('Nilai Diskon')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Masukkan persentase (cth: 10 untuk 10%) atau nominal Rupiah (cth: 50000).'),

                        TextInput::make('minimum_order')
                            ->label('Minimal Belanja (Rp)')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->helperText('Minimal belanja untuk dapat menggunakan promo ini. Isi 0 jika tidak ada minimal belanja.'),

                        TextInput::make('kuota')
                            ->label('Kuota Pemakaian')
                            ->numeric()
                            ->minValue(1)
                            ->nullable()
                            ->helperText('Batas maksimal promo dapat digunakan. Kosongkan untuk pemakaian tak terbatas.'),

                        TextInput::make('dipakai')
                            ->label('Sudah Terpakai')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->default(0),

                        DateTimePicker::make('berlaku_sampai')
                            ->label('Masa Berlaku')
                            ->native(false)
                            ->nullable()
                            ->helperText('Tanggal dan waktu promo kedaluwarsa. Kosongkan untuk promo tanpa batas waktu.'),

                        Toggle::make('is_aktif')
                            ->label('Promo Aktif')
                            ->helperText('Jika dinonaktifkan, promo tidak akan bisa digunakan sama sekali.')
                            ->default(true)
                            ->inline(false),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Promo')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Tuliskan detail promosi ini...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
