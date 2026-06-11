<?php

namespace App\Filament\Resources\Pembayarans\Tables;

use App\Models\Pembayaran;
use App\Services\ActivityLogger;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PembayaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->query(
                Pembayaran::query()->with(['pesanan.pelanggan', 'verifikator'])->latest()
            )
            ->columns([
                TextColumn::make('pesanan.kode_pesanan')
                    ->label('Kode Pesanan')
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pesanan.pelanggan.nama_lengkap')
                    ->label('Pelanggan')
                    ->searchable(),

                BadgeColumn::make('metode')
                    ->label('Metode')
                    ->color('info'),

                TextColumn::make('jumlah_dibayar')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => Pembayaran::STATUS_MENUNGGU,
                        'success' => Pembayaran::STATUS_LUNAS,
                        'danger' => Pembayaran::STATUS_DITOLAK,
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        Pembayaran::STATUS_MENUNGGU => 'Menunggu Verifikasi',
                        Pembayaran::STATUS_LUNAS => 'Lunas',
                        Pembayaran::STATUS_DITOLAK => 'Ditolak',
                        default => $state,
                    }),

                ImageColumn::make('bukti_transfer')
                    ->label('Bukti')
                    ->disk('public')
                    ->height(48)
                    ->width(48)
                    ->defaultImageUrl(url('/images/no-image.png'))
                    ->extraImgAttributes(['class' => 'rounded']),

                TextColumn::make('waktu_dibayar')
                    ->label('Waktu Bayar')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),

                TextColumn::make('verifikator.name')
                    ->label('Diverifikasi Oleh')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Pembayaran::STATUS_MENUNGGU => 'Menunggu Verifikasi',
                        Pembayaran::STATUS_LUNAS => 'Lunas',
                        Pembayaran::STATUS_DITOLAK => 'Ditolak',
                    ]),

                SelectFilter::make('metode')
                    ->label('Metode Bayar')
                    ->options([
                        'transfer_bank' => 'Transfer Bank',
                        'cash' => 'Cash',
                        'qris' => 'QRIS',
                        'cod' => 'COD',
                    ]),
            ])
            ->recordActions([
                // Verifikasi Lunas
                Action::make('verifikasi')
                    ->label('✓ Lunas')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Verifikasi Pembayaran')
                    ->modalDescription('Yakin pembayaran ini sudah lunas? Aksi ini akan mencatat timestamp dan admin yang memverifikasi.')
                    ->modalSubmitActionLabel('Ya, Verifikasi Lunas')
                    ->visible(fn (Pembayaran $record) => $record->status === Pembayaran::STATUS_MENUNGGU)
                    ->action(function (Pembayaran $record) {
                        $record->update([
                            'status' => Pembayaran::STATUS_LUNAS,
                            'diverifikasi_oleh' => Auth::id(),
                            'waktu_diverifikasi' => now(),
                        ]);

                        // Update status pesanan ke diproses
                        $record->pesanan()->update(['status' => 'diproses']);

                        // Audit trail
                        ActivityLogger::verifikasiPembayaran($record, Pembayaran::STATUS_LUNAS);
                    }),

                // Tolak Pembayaran
                Action::make('tolak')
                    ->label('✗ Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->modalDescription('Isi alasan penolakan yang akan disampaikan ke pelanggan.')
                    ->form([
                        Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->minLength(10)
                            ->rows(3)
                            ->placeholder('Contoh: Nominal tidak sesuai, bukti tidak terbaca, dll.'),
                    ])
                    ->visible(fn (Pembayaran $record) => $record->status === 'menunggu')
                    ->action(function (Pembayaran $record, array $data) {
                        $record->update([
                            'status' => Pembayaran::STATUS_DITOLAK,
                            'alasan_penolakan' => $data['alasan_penolakan'],
                            'diverifikasi_oleh' => Auth::id(),
                            'waktu_diverifikasi' => now(),
                        ]);

                        // Audit trail
                        ActivityLogger::verifikasiPembayaran($record, 'ditolak', $data['alasan_penolakan']);
                    }),

                EditAction::make()->label('Edit')->color('gray')->icon('heroicon-o-pencil'),
            ]);
    }
}
