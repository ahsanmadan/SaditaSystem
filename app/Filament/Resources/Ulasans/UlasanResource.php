<?php

namespace App\Filament\Resources\Ulasans;

use App\Filament\Resources\Ulasans\Pages\EditUlasan;
use App\Filament\Resources\Ulasans\Pages\ListUlasans;
use App\Filament\Resources\Ulasans\Schemas\UlasanForm;
use App\Filament\Resources\Ulasans\Tables\UlasansTable;
use App\Models\Ulasan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UlasanResource extends Resource
{
    protected static ?string $model = Ulasan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Ulasan & Moderasi';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'nama_pengulas';

    protected static ?string $modelLabel = 'Ulasan';

    protected static ?string $pluralModelLabel = 'Ulasan';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Pelanggan';
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'ulasan';
    }

    public static function form(Schema $schema): Schema
    {
        return UlasanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UlasansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUlasans::route('/'),
            'edit'  => EditUlasan::route('/{record}/edit'),
        ];
    }
}
