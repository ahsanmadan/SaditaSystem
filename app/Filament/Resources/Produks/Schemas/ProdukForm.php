<?php

namespace App\Filament\Resources\Produks\Schemas;

use App\Models\Kategori;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk')
                    ->description('Data utama produk yang akan ditampilkan.')
                    ->columns(2)
                    ->schema([
                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->options(
                                Kategori::where('is_aktif', true)
                                    ->orderBy('nama')
                                    ->pluck('nama', 'id')
                            )
                            ->required()
                            ->searchable()
                            ->placeholder('Pilih kategori...'),

                        TextInput::make('nama')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(150)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->unique(table: 'produk', column: 'slug', ignoreRecord: true)
                            ->maxLength(150)
                            ->helperText('Diisi otomatis dari nama.')
                            ->dehydrateStateUsing(fn ($state) => Str::slug($state)),

                        TextInput::make('harga_dasar')
                            ->label('Harga Dasar (Rp)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->step(1000)
                            ->helperText('Harga minimum / harga mulai dari.'),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Produk')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Deskripsikan produk ini...')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                Section::make('Opsi Produk')
                    ->description('Konfigurasi sifat dan ketersediaan produk.')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_aktif')
                            ->label('Produk Aktif')
                            ->helperText('Produk tampil di katalog.')
                            ->default(true)
                            ->inline(false),

                        Toggle::make('is_customizable')
                            ->label('Bisa Dikustomisasi')
                            ->helperText('Pelanggan bisa request desain khusus.')
                            ->default(false)
                            ->inline(false),

                        Toggle::make('is_sewa')
                            ->label('Sistem Sewa')
                            ->helperText('Produk ini bersifat sewa (bukan jual putus).')
                            ->default(false)
                            ->inline(false),
                    ]),
            ]);
    }
}
