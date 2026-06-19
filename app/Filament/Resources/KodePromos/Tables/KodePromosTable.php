<?php

namespace App\Filament\Resources\KodePromos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KodePromosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('tipe_diskon')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'persentase' ? 'Persentase' : 'Nominal')
                    ->color(fn ($state) => $state === 'persentase' ? 'info' : 'success'),

                TextColumn::make('nilai_diskon')
                    ->label('Nilai')
                    ->formatStateUsing(function ($record, $state) {
                        return $record->tipe_diskon === 'persentase'
                            ? $state.'%'
                            : 'Rp '.number_format($state, 0, ',', '.');
                    }),

                TextColumn::make('minimum_order')
                    ->label('Min. Transaksi')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.')),

                TextColumn::make('kuota_info')
                    ->label('Dipakai / Kuota')
                    ->state(fn ($record) => $record->dipakai.' / '.($record->kuota ?? '∞')),

                TextColumn::make('tanggal_berakhir')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->placeholder('Tidak dibatasi')
                    ->color(fn ($record) => $record->isExpired() ? 'danger' : null),

                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('aktif')
                    ->label('Promo Aktif')
                    ->query(fn (Builder $query) => $query->where('is_aktif', true)),
                Filter::make('expired')
                    ->label('Sudah Expired')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal_berakhir', '<', now()->toDateString())),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
