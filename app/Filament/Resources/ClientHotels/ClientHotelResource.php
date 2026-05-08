<?php

namespace App\Filament\Resources\ClientHotels;

use App\Filament\Resources\ClientHotels\Pages\CreateClientHotel;
use App\Filament\Resources\ClientHotels\Pages\EditClientHotel;
use App\Filament\Resources\ClientHotels\Pages\ListClientHotels;
use App\Filament\Resources\ClientHotels\Schemas\ClientHotelForm;
use App\Filament\Resources\ClientHotels\Tables\ClientHotelsTable;
use App\Models\ClientHotel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClientHotelResource extends Resource
{
    protected static ?string $model = ClientHotel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Clients';

    protected static string|\UnitEnum|null $navigationGroup = 'Hôtel';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Client';

    protected static ?string $pluralModelLabel = 'Clients';

    public static function form(Schema $schema): Schema
    {
        return ClientHotelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientHotelsTable::configure($table);
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
            'index' => ListClientHotels::route('/'),
            'create' => CreateClientHotel::route('/create'),
            'edit' => EditClientHotel::route('/{record}/edit'),
        ];
    }
}
