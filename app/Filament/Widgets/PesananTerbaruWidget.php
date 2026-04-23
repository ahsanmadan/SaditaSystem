<?php

namespace App\Filament\Widgets;

use App\Services\Analytics\DashboardReportService;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Pesanan;
use Filament\Tables;

class PesananTerbaruWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Pesanan Terbaru';

    protected int | string | array $columnSpan = 'full';

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
                Tables\Columns\TextColumn::make('id_pesanan')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\BadgeColumn::make('status')
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dipesan')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta'),
            ])
            ->paginated(false);
    }
}
