<?php

namespace App\Filament\Resources\Diskons;

use App\Filament\Resources\Diskons\Pages\CreateDiskon;
use App\Filament\Resources\Diskons\Pages\EditDiskon;
use App\Filament\Resources\Diskons\Pages\ListDiskons;
use App\Filament\Resources\Diskons\Schemas\DiskonForm;
use App\Filament\Resources\Diskons\Tables\DiskonsTable;
use App\Models\Diskon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiskonResource extends Resource
{
    protected static ?string $model = Diskon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static ?string $navigationLabel = 'Diskon & Promo';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'kode';

    protected static ?string $modelLabel = 'Diskon';

    protected static ?string $pluralModelLabel = 'Diskon';

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'diskon';
    }

    public static function form(Schema $schema): Schema
    {
        return DiskonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiskonsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiskons::route('/'),
            'create' => CreateDiskon::route('/create'),
            'edit' => EditDiskon::route('/{record}/edit'),
        ];
    }
}
