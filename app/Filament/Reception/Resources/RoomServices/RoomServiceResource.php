<?php

namespace App\Filament\Reception\Resources\RoomServices;

use App\Filament\Reception\Resources\RoomServices\Pages\CreateRoomService;
use App\Filament\Reception\Resources\RoomServices\Pages\EditRoomService;
use App\Filament\Reception\Resources\RoomServices\Pages\ListRoomServices;
use App\Filament\Reception\Resources\RoomServices\Schemas\RoomServiceForm;
use App\Filament\Reception\Resources\RoomServices\Tables\RoomServicesTable;
use App\Models\RoomService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoomServiceResource extends Resource
{
    protected static ?string $model = RoomService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $navigationLabel = 'Room Service';

    protected static string|\UnitEnum|null $navigationGroup = 'Réception';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Commande Room Service';

    protected static ?string $pluralModelLabel = 'Room Services';

    public static function form(Schema $schema): Schema
    {
        return RoomServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomServicesTable::configure($table);
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
            'index' => ListRoomServices::route('/'),
            'create' => CreateRoomService::route('/create'),
            'edit' => EditRoomService::route('/{record}/edit'),
        ];
    }
}
