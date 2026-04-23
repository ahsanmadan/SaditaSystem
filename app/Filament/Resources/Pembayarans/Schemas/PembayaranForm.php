<?php

namespace App\Filament\Resources\Pembayarans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pembayaran')
                    ->description('Field status, verifikator, dan waktu verifikasi diisi otomatis saat aksi verifikasi dilakukan.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('metode')
                            ->label('Metode Pembayaran')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('jumlah_dibayar')
                            ->label('Jumlah Dibayar (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'lunas'    => 'Lunas',
                                'ditolak'  => 'Ditolak',
                            ])
                            ->disabled()
                            ->dehydrated(false),

                        DateTimePicker::make('waktu_dibayar')
                            ->label('Waktu Dibayar')
                            ->native(false)
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
