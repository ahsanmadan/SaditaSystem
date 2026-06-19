<?php

namespace App\Filament\Resources\KodePromos\Schemas;

use Filament\Forms\Components\DatePicker;
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
                Section::make('Informasi Promo')
                    ->description('Buat kode promo manual yang nanti bisa dipakai pelanggan di halaman order.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode Promo')
                            ->required()
                            ->minLength(6)
                            ->maxLength(12)
                            ->unique(table: 'kode_promo', column: 'kode', ignoreRecord: true)
                            ->dehydrateStateUsing(fn ($state) => strtoupper(trim($state)))
                            ->helperText('Gunakan huruf/angka tanpa spasi. Contoh: SADITA10 atau HEMAT50.'),

                        Select::make('tipe_diskon')
                            ->label('Jenis Diskon')
                            ->required()
                            ->options([
                                'persentase' => 'Persentase (%)',
                                'nominal' => 'Nominal (Rp)',
                            ]),

                        TextInput::make('nilai_diskon')
                            ->label('Nilai Diskon')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Isi angka diskon. Contoh 10 untuk persen atau 50000 untuk nominal.'),

                        TextInput::make('minimum_order')
                            ->label('Minimal Transaksi')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),

                        TextInput::make('kuota')
                            ->label('Kuota Pemakaian')
                            ->numeric()
                            ->minValue(1)
                            ->nullable()
                            ->helperText('Kosongkan jika promo boleh dipakai tanpa batas kuota.'),

                        Toggle::make('is_aktif')
                            ->label('Status Aktif')
                            ->default(true)
                            ->inline(false),

                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->nullable(),

                        DatePicker::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->native(false)
                            ->nullable()
                            ->afterOrEqual('tanggal_mulai'),

                        Textarea::make('deskripsi')
                            ->label('Persyaratan / Catatan Promo')
                            ->rows(3)
                            ->placeholder('Contoh: berlaku untuk semua order reguler selama promo aktif.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
