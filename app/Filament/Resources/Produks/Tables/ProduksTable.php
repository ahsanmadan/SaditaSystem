<?php

namespace App\Filament\Resources\Produks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProduksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(\App\Models\Produk::query()->with('kategori'))
            ->columns([
                ImageColumn::make('foto_utama')
                    ->label('Foto')
                    ->square()
                    ->size(52)
                    ->defaultImageUrl('https://placehold.co/52x52/f8f5f0/7A1F2B?text=No+Foto')
                    ->getStateUsing(fn ($record) => $record->fotoUtamaUrl())
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover']),

                TextColumn::make('nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn ($record) => $record->kategori?->nama),

                TextColumn::make('harga_dasar')
                    ->label('Harga Dasar')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                IconColumn::make('is_customizable')
                    ->label('Custom')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),

                IconColumn::make('is_sewa')
                    ->label('Sewa')
                    ->boolean()
                    ->trueColor('primary')
                    ->falseColor('gray'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->timezone('Asia/Jakarta')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nama')
            ->filters([
                SelectFilter::make('kategori')
                    ->label('Filter Kategori')
                    ->relationship('kategori', 'nama', fn ($query) => $query->orderBy('nama'))
                    ->placeholder('Semua Kategori'),

                Filter::make('aktif')
                    ->label('Hanya Aktif')
                    ->query(fn (Builder $query) => $query->where('is_aktif', true)),

                Filter::make('nonaktif')
                    ->label('Hanya Nonaktif')
                    ->query(fn (Builder $query) => $query->where('is_aktif', false)),

                Filter::make('is_sewa')
                    ->label('Hanya Produk Sewa')
                    ->query(fn (Builder $query) => $query->where('is_sewa', true)),

                TrashedFilter::make()
                    ->label('Termasuk Terhapus'),
            ])
            ->recordActions([
                EditAction::make()->label('Edit'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Dipilih'),
                    ForceDeleteBulkAction::make()->label('Hapus Permanen'),
                    RestoreBulkAction::make()->label('Pulihkan'),
                ]),
            ])
            ->emptyStateHeading('Belum ada produk yang tersedia')
            ->emptyStateDescription('Silakan klik tombol "Buat Produk" di atas untuk menambahkan produk baru.');
    }
}
