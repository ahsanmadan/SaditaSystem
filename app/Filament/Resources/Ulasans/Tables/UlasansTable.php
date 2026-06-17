<?php

namespace App\Filament\Resources\Ulasans\Tables;

use App\Models\Ulasan;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UlasansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Ulasan::query()->with(['produk:id,nama', 'pesanan:id,kode_pesanan'])
            )
            ->columns([
                TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(function ($state): string {
                        $rating = max(0, min(5, (int) $state));

                        return str_repeat('*', $rating) . str_repeat('-', 5 - $rating) . " ({$rating}/5)";
                    })
                    ->color(fn ($state) => match (true) {
                        (int) $state >= 4 => 'success',
                        (int) $state === 3 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('nama_pengulas')
                    ->label('Pengulas')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('produk.nama')
                    ->label('Produk')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->limit(30),

                TextColumn::make('pesanan.kode_pesanan')
                    ->label('Pesanan')
                    ->fontFamily('mono')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('komentar')
                    ->label('Komentar')
                    ->limit(60)
                    ->tooltip(fn ($state) => $state)
                    ->searchable(),

                ImageColumn::make('foto_ulasan')
                    ->label('Foto')
                    ->disk('public')
                    ->height(48)
                    ->width(48)
                    ->defaultImageUrl(null)
                    ->extraImgAttributes(['class' => 'rounded'])
                    ->toggleable(),

                IconColumn::make('is_tampil')
                    ->label('Tampil')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash'),

                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        '5' => '5 bintang',
                        '4' => '4 bintang',
                        '3' => '3 bintang',
                        '2' => '2 bintang',
                        '1' => '1 bintang',
                    ]),

                Filter::make('tampil')
                    ->label('Hanya Tampil')
                    ->query(fn (Builder $q) => $q->where('is_tampil', true)),

                Filter::make('hidden')
                    ->label('Hanya Disembunyikan')
                    ->query(fn (Builder $q) => $q->where('is_tampil', false)),

                Filter::make('ada_foto')
                    ->label('Ada Foto')
                    ->query(fn (Builder $q) => $q->whereNotNull('foto_ulasan')),
            ])
            ->recordActions([
                Action::make('toggle_tampil')
                    ->label(fn (Ulasan $record) => $record->is_tampil ? 'Sembunyikan' : 'Tampilkan')
                    ->color(fn (Ulasan $record) => $record->is_tampil ? 'danger' : 'success')
                    ->icon(fn (Ulasan $record) => $record->is_tampil
                        ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Ulasan $record) => $record->is_tampil
                        ? 'Sembunyikan Ulasan?' : 'Tampilkan Ulasan?')
                    ->modalDescription(fn (Ulasan $record) => $record->is_tampil
                        ? 'Ulasan tidak akan terlihat di halaman publik.'
                        : 'Ulasan akan tampil kembali di halaman publik.')
                    ->action(fn (Ulasan $record) => $record->update([
                        'is_tampil' => ! $record->is_tampil,
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('tampilkan_semua')
                        ->label('Tampilkan Dipilih')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(
                            fn (Ulasan $r) => $r->update(['is_tampil' => true])
                        )),

                    BulkAction::make('sembunyikan_semua')
                        ->label('Sembunyikan Dipilih')
                        ->icon('heroicon-o-eye-slash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(
                            fn (Ulasan $r) => $r->update(['is_tampil' => false])
                        )),
                ]),
            ]);
    }
}
