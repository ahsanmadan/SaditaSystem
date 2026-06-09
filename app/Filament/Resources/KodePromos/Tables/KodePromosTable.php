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
                    ->label('Kode Promo')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->fontFamily('mono'),

                TextColumn::make('tipe_diskon')
                    ->label('Tipe')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'persentase' => 'Persentase (%)',
                        'nominal' => 'Nominal (Rp)',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => $state === 'persentase' ? 'info' : 'success'),

                TextColumn::make('nilai_diskon')
                    ->label('Diskon')
                    ->formatStateUsing(function ($record, $state) {
                        return $record->tipe_diskon === 'persentase'
                            ? $state.'%'
                            : 'Rp '.number_format($state, 0, ',', '.');
                    })
                    ->sortable(),

                TextColumn::make('minimum_order')
                    ->label('Min. Belanja')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))
                    ->sortable(),

                TextColumn::make('kuota_status')
                    ->label('Penggunaan (Kuota)')
                    ->state(function ($record) {
                        $kuotaText = $record->kuota === null ? '∞' : $record->kuota;

                        return $record->dipakai.' / '.$kuotaText;
                    }),

                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('berlaku_sampai')
                    ->label('Berlaku Sampai')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable()
                    ->color(fn ($record) => $record->berlaku_sampai?->isPast() ? 'danger' : null),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('aktif')
                    ->label('Hanya Aktif')
                    ->query(fn (Builder $query) => $query->where('is_aktif', true)),

                Filter::make('nonaktif')
                    ->label('Hanya Nonaktif')
                    ->query(fn (Builder $query) => $query->where('is_aktif', false)),

                Filter::make('expired')
                    ->label('Sudah Kedaluwarsa')
                    ->query(fn (Builder $query) => $query->where('berlaku_sampai', '<', now())),
            ])
            ->recordActions([
                EditAction::make()->label('Edit'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Dipilih'),
                ]),
            ]);
    }
}
