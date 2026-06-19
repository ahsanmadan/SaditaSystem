<?php

namespace App\Filament\Resources\KodePromos\Pages;

use App\Filament\Resources\KodePromos\KodePromoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKodePromo extends EditRecord
{
    protected static string $resource = KodePromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
