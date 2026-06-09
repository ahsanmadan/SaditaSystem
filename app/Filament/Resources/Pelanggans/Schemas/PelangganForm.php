<?php

namespace App\Filament\Resources\Pelanggans\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PelangganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelanggan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('no_hp')
                            ->label('No. HP / WhatsApp')
                            ->required()
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique(table: 'pelanggan', column: 'email', ignoreRecord: true)
                            ->maxLength(150),
                    ]),
            ]);
    }
}
