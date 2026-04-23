<?php

namespace App\Filament\Resources\Pesanans\Tables;

use App\Models\Pelanggan;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Pesanan::query()->with(['pelanggan', 'riwayatPembayaran'])
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
                        'warning' => 'menunggu_pembayaran',
                        'info'    => 'diproses',
                        'primary' => 'siap_kirim',
                        'success' => 'selesai',
                        'danger'  => 'dibatalkan',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'menunggu_pembayaran' => 'Menunggu Bayar',
                        'diproses'            => 'Diproses',
                        'siap_kirim'          => 'Siap Kirim',
                        'selesai'             => 'Selesai',
                        'dibatalkan'          => 'Dibatalkan',
                        default               => $state,
                    }),

                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                BadgeColumn::make('riwayatPembayaran.status')
                    ->label('Status Bayar')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'lunas',
                        'danger'  => 'ditolak',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'menunggu' => 'Menunggu',
                        'lunas'    => 'Lunas',
                        'ditolak'  => 'Ditolak',
                        default    => '-',
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
                        && $record->status === 'menunggu_pembayaran' ? 'danger' : null)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'diproses'            => 'Diproses',
                        'siap_kirim'          => 'Siap Kirim',
                        'selesai'             => 'Selesai',
                        'dibatalkan'          => 'Dibatalkan',
                    ]),

                Filter::make('overdue')
                    ->label('Overdue Pembayaran')
                    ->query(fn (Builder $q) => $q
                        ->where('status', 'menunggu_pembayaran')
                        ->where('batas_waktu_bayar', '<', now())),

                Filter::make('tanggal')
                    ->label('Tanggal Order')
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
