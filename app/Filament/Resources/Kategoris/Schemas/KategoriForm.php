<?php

namespace App\Filament\Resources\Kategoris\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(100)
                    ->live(debounce: 500)
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->unique(table: 'kategori', column: 'slug', ignoreRecord: true)
                    ->maxLength(100)
                    ->helperText('Diisi otomatis dari nama. Bisa diubah manual.')
                    ->dehydrateStateUsing(fn ($state) => Str::slug($state)),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Opsional — deskripsi singkat kategori ini.')
                    ->default(null)
                    ->columnSpanFull(),

                Toggle::make('is_aktif')
                    ->label('Kategori Aktif')
                    ->helperText('Kategori nonaktif tidak akan muncul di storefront.')
                    ->default(true)
                    ->inline(false),
            ]);
    }
}
