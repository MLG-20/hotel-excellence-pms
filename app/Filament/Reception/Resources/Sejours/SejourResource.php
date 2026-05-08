<?php

namespace App\Filament\Reception\Resources\Sejours;

use App\Filament\Reception\Resources\Sejours\Pages\CreateSejour;
use App\Filament\Reception\Resources\Sejours\Pages\EditSejour;
use App\Filament\Reception\Resources\Sejours\Pages\ListSejours;
use App\Filament\Reception\Resources\Sejours\Schemas\SejourForm;
use App\Filament\Reception\Resources\Sejours\Tables\SejoursTable;
use App\Models\Sejour;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SejourResource extends Resource
{
    protected static ?string $model = Sejour::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Séjours';

    protected static string|\UnitEnum|null $navigationGroup = 'Réception';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Séjour';

    protected static ?string $pluralModelLabel = 'Séjours';

    public static function form(Schema $schema): Schema
    {
        return SejourForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SejoursTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSejours::route('/'),
            'create' => CreateSejour::route('/create'),
            'edit' => EditSejour::route('/{record}/edit'),
        ];
    }
}
