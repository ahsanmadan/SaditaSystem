<?php

namespace App\Filament\Resources\Produks\Tables;

use App\Models\Kategori;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
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
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

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
                SelectFilter::make('kategori_id')
                    ->label('Filter Kategori')
                    ->options(Kategori::orderBy('nama')->pluck('nama', 'id'))
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
            ]);
    }
}
