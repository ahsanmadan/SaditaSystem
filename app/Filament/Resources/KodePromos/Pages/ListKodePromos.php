<?php

namespace App\Filament\Resources\KodePromos\Pages;

use App\Filament\Resources\KodePromos\KodePromoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKodePromos extends ListRecords
{
    protected static string $resource = KodePromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
