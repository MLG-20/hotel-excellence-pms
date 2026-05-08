<?php

namespace App\Filament\Reception\Resources\Factures;

use App\Filament\Reception\Resources\Factures\Pages\CreateFacture;
use App\Filament\Reception\Resources\Factures\Pages\EditFacture;
use App\Filament\Reception\Resources\Factures\Pages\ListFactures;
use App\Filament\Reception\Resources\Factures\Schemas\FactureForm;
use App\Filament\Reception\Resources\Factures\Tables\FacturesTable;
use App\Models\Facture;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactureResource extends Resource
{
    protected static ?string $model = Facture::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Factures';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Facture';

    protected static ?string $pluralModelLabel = 'Factures';

    public static function form(Schema $schema): Schema
    {
        return FactureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacturesTable::configure($table);
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
            'index' => ListFactures::route('/'),
            'create' => CreateFacture::route('/create'),
            'edit' => EditFacture::route('/{record}/edit'),
        ];
    }
}
