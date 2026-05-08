<?php

namespace App\Filament\Directeur\Resources\PageSites;

use App\Filament\Directeur\Resources\PageSites\Pages\CreatePageSite;
use App\Filament\Directeur\Resources\PageSites\Pages\EditPageSite;
use App\Filament\Directeur\Resources\PageSites\Pages\ListPageSites;
use App\Models\PageSite;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PageSiteResource extends Resource
{
    protected static ?string $model = PageSite::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Pages du site';

    protected static string|\UnitEnum|null $navigationGroup = 'Site Web';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'Page';

    protected static ?string $pluralModelLabel = 'Pages du site';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('slug')
                    ->label('Page')
                    ->options([
                        'about'   => 'À propos',
                        'contact' => 'Contact',
                    ])
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),

                TextInput::make('titre')
                    ->label('Titre de la page')
                    ->required()
                    ->maxLength(120)
                    ->columnSpanFull(),

                Textarea::make('contenu')
                    ->label('Contenu / Présentation')
                    ->rows(6)
                    ->columnSpanFull()
                    ->placeholder('Texte principal affiché sur la page...'),

                TextInput::make('telephone')
                    ->label('Téléphone')
                    ->tel()
                    ->maxLength(30)
                    ->placeholder('+221 77 000 00 00'),

                TextInput::make('email_contact')
                    ->label('Email de contact')
                    ->email()
                    ->maxLength(100)
                    ->placeholder('contact@hotel-excellence.sn'),

                Textarea::make('adresse')
                    ->label('Adresse')
                    ->rows(2)
                    ->placeholder('Avenue Léopold Sédar Senghor, Thiès, Sénégal'),

                Textarea::make('horaires')
                    ->label('Horaires d\'accueil')
                    ->rows(2)
                    ->placeholder('Lun-Dim : 24h/24 — Réception ouverte en permanence'),

                Toggle::make('actif')
                    ->label('Page active')
                    ->default(true)
                    ->columnSpanFull()
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'about'   => 'info',
                        'contact' => 'success',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'about'   => 'À propos',
                        'contact' => 'Contact',
                        default   => $state,
                    }),

                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('contenu')
                    ->label('Aperçu contenu')
                    ->limit(60)
                    ->color('gray'),

                ToggleColumn::make('actif')
                    ->label('Actif'),
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
            'index'  => ListPageSites::route('/'),
            'create' => CreatePageSite::route('/create'),
            'edit'   => EditPageSite::route('/{record}/edit'),
        ];
    }
}
