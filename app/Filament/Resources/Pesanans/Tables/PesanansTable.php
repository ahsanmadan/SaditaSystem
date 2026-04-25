<?php

namespace App\Filament\Resources\Pesanans\Tables;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Pesanan::query()->with(['pelanggan', 'riwayatPesanan'])
            )
            ->columns([
                TextColumn::make('kode_pesanan')
                    ->label('Kode Pesanan')
                    ->searchable()
                    ->fontFamily('mono')
                    ->weight('semibold')
                    ->copyable(),

                TextColumn::make('pelanggan.nama_lengkap')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => Pesanan::STATUS_MENUNGGU,
                        'info' => Pesanan::STATUS_DIPROSES,
                        'primary' => Pesanan::STATUS_SIAPKIRIM,
                        'success' => Pesanan::STATUS_SELESAI,
                        'danger' => Pesanan::STATUS_DIBATALKAN,
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        Pesanan::STATUS_MENUNGGU => 'Menunggu Bayar',
                        Pesanan::STATUS_DIPROSES => 'Diproses',
                        Pesanan::STATUS_SIAPKIRIM => 'Siap Kirim',
                        Pesanan::STATUS_SELESAI => 'Selesai',
                        Pesanan::STATUS_DIBATALKAN => 'Dibatalkan',
                        default => $state,
                    }),

                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))
                    ->sortable(),

                BadgeColumn::make('riwayatPesanan.status')
                    ->label('Status Bayar')
                    ->colors([
                        'warning' => Pembayaran::STATUS_MENUNGGU,
                        'success' => Pembayaran::STATUS_LUNAS,
                        'danger' => Pembayaran::STATUS_DITOLAK,
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        Pembayaran::STATUS_MENUNGGU => 'Menunggu',
                        Pembayaran::STATUS_LUNAS => 'Lunas',
                        Pembayaran::STATUS_DITOLAK => 'Ditolak',
                        default => '-',
                    })
                    ->separator(','),

                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),

                TextColumn::make('batas_waktu_bayar')
                    ->label('Batas Bayar')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable()
                    ->color(fn ($record) => $record?->batas_waktu_bayar?->isPast()
                        && $record->status === Pesanan::STATUS_MENUNGGU ? 'danger' : null)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        Pesanan::STATUS_MENUNGGU => 'Menunggu Pesanan',
                        Pesanan::STATUS_DIPROSES => 'Diproses',
                        Pesanan::STATUS_SIAPKIRIM => 'Siap Kirim',
                        Pesanan::STATUS_SELESAI => 'Selesai',
                        Pesanan::STATUS_DIBATALKAN => 'Dibatalkan',
                    ]),

                Filter::make('overdue')
                    ->label('Overdue Pesanan')
                    ->query(fn (Builder $q) => $q
                        ->where('status', Pesanan::STATUS_MENUNGGU)
                        ->where('batas_waktu_bayar', '<', now())),

                Filter::make('tanggal')
                    ->label('Tanggal Order')
                    ->form([
                        DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->native(false),
                        DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $q, array $data) {
                        return $q
                            ->when($data['dari'], fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
                            ->when($data['sampai'], fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
                    }),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                EditAction::make()->label('Edit')->color('gray'),
            ]);
    }
}
