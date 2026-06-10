<?php

namespace App\Filament\Resources\Produks\Schemas;

use App\Models\Kategori;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ─── Section 1: Informasi Utama ────────────────────────────────
                Section::make('Informasi Produk')
                    ->description('Data utama produk yang akan ditampilkan di katalog.')
                    ->icon('heroicon-o-tag')
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
                            ->placeholder('Pilih kategori...')
                            ->columnSpanFull(),

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
                            ->helperText('Diisi otomatis dari nama produk.')
                            ->dehydrateStateUsing(fn ($state) => Str::slug($state)),

                        TextInput::make('harga_dasar')
                            ->label('Harga Dasar (Rp)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->step(1000)
                            ->helperText('Harga minimum / harga mulai dari.')
                            ->columnSpanFull(),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Produk')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Deskripsikan produk ini secara singkat dan menarik...')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                // ─── Section 2: Foto Produk ─────────────────────────────────────
                Section::make('Foto Produk')
                    ->description('Upload foto produk. Foto pertama akan menjadi foto utama. Format: JPG, PNG, WebP. Maks 2MB per file.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('foto_utama')
                            ->label('Foto Utama')
                            ->helperText('Foto yang tampil di kartu produk. Gunakan rasio 4:3 atau 1:1. Format JPG/PNG/WebP, maks 2MB.')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['4:3', '1:1'])
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('images/produk')
                            ->visibility('public')
                            ->required(false)
                            ->columnSpanFull()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file) => (string) str()->ulid().'.'.$file->getClientOriginalExtension()
                            ),

                        FileUpload::make('galeri_foto')
                            ->label('Foto Tambahan (Galeri)')
                            ->helperText('Upload hingga 5 foto tambahan. Format JPG/PNG/WebP, maks 2MB per file.')
                            ->image()
                            ->multiple()
                            ->maxFiles(5)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('images/produk/galeri')
                            ->visibility('public')
                            ->panelLayout('grid')
                            ->reorderable()
                            ->required(false)
                            ->columnSpanFull(),
                    ]),

                // ─── Section 3: Opsi & Ketersediaan ────────────────────────────
                Section::make('Opsi & Ketersediaan')
                    ->description('Konfigurasi sifat dan visibilitas produk.')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_aktif')
                            ->label('Tampilkan di Katalog')
                            ->helperText('Produk bisa dilihat pelanggan.')
                            ->default(true)
                            ->inline(false),

                        Toggle::make('is_customizable')
                            ->label('Bisa Dikustomisasi')
                            ->helperText('Pelanggan bisa request desain khusus.')
                            ->default(false)
                            ->inline(false),

                        Toggle::make('is_sewa')
                            ->label('Sistem Sewa')
                            ->helperText('Produk ini bersifat sewa, bukan jual putus.')
                            ->default(false)
                            ->inline(false),
                    ]),
            ]);
    }
}
