<?php

namespace App\Filament\Directeur\Resources\AvisClients;

use App\Filament\Directeur\Resources\AvisClients\Pages\CreateAvisClient;
use App\Filament\Directeur\Resources\AvisClients\Pages\EditAvisClient;
use App\Filament\Directeur\Resources\AvisClients\Pages\ListAvisClients;
use App\Models\AvisClient;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class AvisClientResource extends Resource
{
    protected static ?string $model = AvisClient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Avis clients';

    protected static string|\UnitEnum|null $navigationGroup = 'Site Web';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'Avis';

    protected static ?string $pluralModelLabel = 'Avis clients';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nom')
                    ->label('Nom du client')
                    ->required()
                    ->maxLength(80)
                    ->placeholder('Ex : Aminata Diallo'),

                Select::make('note')
                    ->label('Note')
                    ->options([
                        1 => '★ — Très insatisfait',
                        2 => '★★ — Insatisfait',
                        3 => '★★★ — Moyen',
                        4 => '★★★★ — Satisfait',
                        5 => '★★★★★ — Excellent',
                    ])
                    ->default(5)
                    ->required(),

                Textarea::make('message')
                    ->label('Témoignage')
                    ->required()
                    ->rows(4)
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->placeholder('Écrivez ici le témoignage du client...'),

                FileUpload::make('photo')
                    ->label('Photo (optionnel)')
                    ->image()
                    ->disk('public')
                    ->directory('avis')
                    ->visibility('public')
                    ->maxSize(1024)
                    ->helperText('Photo de profil — 200×200 px recommandé, max 1 Mo'),

                TextInput::make('ordre')
                    ->label('Ordre d\'affichage')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Toggle::make('actif')
                    ->label('Avis visible sur le site')
                    ->default(true)
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('ordre')
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->height(48)
                    ->width(48)
                    ->defaultImageUrl('https://placehold.co/80x80/f59e0b/ffffff?text=?'),

                TextColumn::make('nom')
                    ->label('Client')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('note')
                    ->label('Note')
                    ->formatStateUsing(fn (int $state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->color('warning'),

                TextColumn::make('message')
                    ->label('Témoignage')
                    ->limit(60)
                    ->color('gray'),

                TextColumn::make('ordre')
                    ->label('Ordre')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                ToggleColumn::make('actif')
                    ->label('Visible'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAvisClients::route('/'),
            'create' => CreateAvisClient::route('/create'),
            'edit'   => EditAvisClient::route('/{record}/edit'),
        ];
    }
}
