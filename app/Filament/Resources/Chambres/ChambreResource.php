<?php

namespace App\Filament\Resources\Chambres;

use App\Filament\Resources\Chambres\Pages\CreateChambre;
use App\Filament\Resources\Chambres\Pages\EditChambre;
use App\Filament\Resources\Chambres\Pages\ListChambres;
use App\Filament\Resources\Chambres\Schemas\ChambreForm;
use App\Filament\Resources\Chambres\Tables\ChambresTable;
use App\Models\Chambre;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChambreResource extends Resource
{
    protected static ?string $model = Chambre::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $navigationLabel = 'Chambres';

    protected static string|\UnitEnum|null $navigationGroup = 'Hôtel';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Chambre';

    protected static ?string $pluralModelLabel = 'Chambres';

    public static function form(Schema $schema): Schema
    {
        return ChambreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChambresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Chambres\RelationManagers\ReservationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChambres::route('/'),
            'create' => CreateChambre::route('/create'),
            'edit' => EditChambre::route('/{record}/edit'),
        ];
    }
}
