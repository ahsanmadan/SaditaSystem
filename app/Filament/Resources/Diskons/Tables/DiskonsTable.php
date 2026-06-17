<?php

namespace App\Filament\Resources\Diskons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiskonsTable
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

                TextColumn::make('nama')
                    ->label('Nama Promo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'persen' ? 'info' : 'success')
                    ->formatStateUsing(fn (string $state): string => $state === 'persen' ? 'Persen' : 'Nominal'),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($record): string => $record->tipe === 'persen'
                        ? $record->nilai . '%'
                        : 'Rp ' . number_format($record->nilai, 0, ',', '.')
                    ),

                TextColumn::make('minimal_pembelian')
                    ->label('Min. Belanja')
                    ->money('idr')
                    ->sortable(),

                TextColumn::make('usage')
                    ->label('Penggunaan')
                    ->state(fn ($record): string => $record->kuota !== null
                        ? "{$record->digunakan} / {$record->kuota}"
                        : "{$record->digunakan} / ∞"
                    ),

                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('tanggal_berakhir')
                    ->label('Berakhir')
                    ->dateTime('d M Y H:i')
                    ->timezone('Asia/Jakarta')
                    ->placeholder('Tanpa batas waktu')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('is_aktif')
                    ->label('Hanya Promo Aktif')
                    ->query(fn (Builder $query) => $query->where('is_aktif', true)),

                Filter::make('is_nonaktif')
                    ->label('Hanya Promo Nonaktif')
                    ->query(fn (Builder $query) => $query->where('is_aktif', false)),
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
