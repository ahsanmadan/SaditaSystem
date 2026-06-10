<?php

namespace App\Filament\Resources\Ulasans\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UlasanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Ulasan')
                    ->description('Hanya status tampil yang bisa diubah admin. Data ulasan adalah input pelanggan dan tidak boleh dimodifikasi.')
                    ->schema([
                        Toggle::make('is_tampil')
                            ->label('Tampilkan Ulasan')
                            ->helperText('Matikan untuk menyembunyikan dari halaman publik.')
                            ->inline(false),

                        Textarea::make('komentar')
                            ->label('Komentar Pelanggan')
                            ->rows(4)
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
