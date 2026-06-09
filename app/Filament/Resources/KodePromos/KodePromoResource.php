<?php

namespace App\Filament\Resources\KodePromos;

use App\Filament\Resources\KodePromos\Pages\CreateKodePromo;
use App\Filament\Resources\KodePromos\Pages\EditKodePromo;
use App\Filament\Resources\KodePromos\Pages\ListKodePromos;
use App\Filament\Resources\KodePromos\Schemas\KodePromoForm;
use App\Filament\Resources\KodePromos\Tables\KodePromosTable;
use App\Models\KodePromo;
use BackedEnum;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KodePromoResource extends Resource
{
    protected static ?string $model = KodePromo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'Kode Promo';

    protected static ?string $modelLabel = 'Kode Promo';

    protected static ?string $pluralModelLabel = 'Kode Promo';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'kode';

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return 'kode-promo';
    }

    public static function form(Schema $schema): Schema
    {
        return KodePromoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KodePromosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKodePromos::route('/'),
            'create' => CreateKodePromo::route('/create'),
            'edit' => EditKodePromo::route('/{record}/edit'),
        ];
    }
}
