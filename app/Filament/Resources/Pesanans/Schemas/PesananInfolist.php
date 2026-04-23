<?php

namespace App\Filament\Resources\Pesanans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PesananInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('pelanggan_id')
                    ->numeric(),
                TextEntry::make('kode_pesanan'),
                TextEntry::make('status'),
                TextEntry::make('total_harga')
                    ->numeric(),
                TextEntry::make('biaya_ongkir')
                    ->numeric(),
                TextEntry::make('grand_total')
                    ->numeric(),
                TextEntry::make('batas_waktu_bayar')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('waktu_selesai')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('catatan_pembeli')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
