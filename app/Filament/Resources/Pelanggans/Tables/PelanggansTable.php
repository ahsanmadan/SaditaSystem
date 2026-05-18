<?php

namespace App\Filament\Resources\Pelanggans\Tables;

use App\Models\Pelanggan;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PelanggansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Pelanggan::query()
                    ->withCount([
                        'riwayatPesanan as total_pesanan',
                        'riwayatPesanan as pesanan_selesai' => fn (Builder $q) => $q->where('status', 'selesai'),
                    ])
                    ->withMax('riwayatPesanan as last_order_at', 'created_at')
            )
            ->columns([
                TextColumn::make('nama_lengkap')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->searchable()
                    ->fontFamily('mono')
                    ->copyable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total_pesanan')
                    ->label('Total Pesanan')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('pesanan_selesai')
                    ->label('Selesai')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('last_order_at')
                    ->label('Order Terakhir')
                    ->dateTime('d M Y')
                    ->timezone('Asia/Jakarta')
                    ->sortable()
                    ->color(fn ($state) => $state &&
                        \Carbon\Carbon::parse($state)->lt(now()->subMonths(3))
                        ? 'danger' : null),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->timezone('Asia/Jakarta')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('last_order_at', 'desc')
            ->filters([
                Filter::make('repeat_customer')
                    ->label('Repeat Customer (≥2 order selesai)')
                    ->query(fn (Builder $q) => $q->withCount([
                            'riwayatPesanan as pesanan_selesai_count' => fn ($q) =>
                                $q->where('status', 'selesai'),
                        ])->having('pesanan_selesai_count', '>=', 2)),

                Filter::make('belum_selesai')
                    ->label('Belum Pernah Order Selesai')
                    ->query(fn (Builder $q) => $q
                        ->whereDoesntHave('riwayatPesanan', fn ($q) =>
                            $q->where('status', 'selesai'))),

                Filter::make('pasif')
                    ->label('Tidak Order > 3 Bulan')
                    ->query(fn (Builder $q) => $q
                        ->whereHas('riwayatPesanan')
                        ->where(function ($q) {
                            $q->whereDoesntHave('riwayatPesanan', fn ($q) =>
                                $q->where('created_at', '>=', now()->subMonths(3)))
                              ->orWhereDoesntHave('riwayatPesanan');
                        })),

                Filter::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->native(false),
                        \Filament\Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $q, array $data) {
                        return $q
                            ->when($data['dari'], fn ($q, $v) =>
                                $q->whereDate('created_at', '>=', $v))
                            ->when($data['sampai'], fn ($q, $v) =>
                                $q->whereDate('created_at', '<=', $v));
                    }),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                EditAction::make()->label('Edit')->color('gray'),
            ]);
    }
}
