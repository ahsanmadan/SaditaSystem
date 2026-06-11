<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PesananTerbaruWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected ?string $pollingInterval = null;

    protected static ?string $heading = 'Pesanan Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pesanan::query()
                    ->with('pelanggan')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->fontFamily('mono'),

                TextColumn::make('pelanggan.nama_lengkap')
                    ->label('Pelanggan')
                    ->searchable(),

                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.')),

                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.')),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'menunggu_pembayaran',
                        'info' => 'diproses',
                        'primary' => 'siap_kirim',
                        'success' => 'selesai',
                        'danger' => 'dibatalkan',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'menunggu_pembayaran' => 'Menunggu Bayar',
                        'diproses' => 'Diproses',
                        'siap_kirim' => 'Siap Kirim',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Dipesan')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta'),
            ])
            ->paginated(false);
    }
}
