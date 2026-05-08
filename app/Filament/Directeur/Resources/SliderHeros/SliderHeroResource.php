<?php

namespace App\Filament\Directeur\Resources\SliderHeros;

use App\Filament\Directeur\Resources\SliderHeros\Pages\CreateSliderHero;
use App\Filament\Directeur\Resources\SliderHeros\Pages\EditSliderHero;
use App\Filament\Directeur\Resources\SliderHeros\Pages\ListSliderHeros;
use App\Models\SliderHero;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class SliderHeroResource extends Resource
{
    protected static ?string $model = SliderHero::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Sliders Hero';

    protected static string|\UnitEnum|null $navigationGroup = 'Site Web';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Slide';

    protected static ?string $pluralModelLabel = 'Sliders Hero';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('titre')
                    ->label('Titre principal')
                    ->required()
                    ->maxLength(120)
                    ->columnSpanFull()
                    ->placeholder('Ex : Bienvenue à l\'Hôtel Excellence'),

                TextInput::make('sous_titre')
                    ->label('Sous-titre')
                    ->maxLength(200)
                    ->columnSpanFull()
                    ->placeholder('Ex : Un séjour d\'exception vous attend…'),

                Placeholder::make('image_actuelle')
                    ->label('Image actuelle')
                    ->content(fn ($record) => $record?->image
                        ? new HtmlString('<img src="' . Storage::url($record->image) . '" style="max-height:140px;border-radius:8px;border:2px solid #f59e0b;margin-top:4px">')
                        : new HtmlString('<span style="color:#9ca3af">Aucune image uploadée</span>')
                    )
                    ->hiddenOn('create')
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label(fn ($record) => $record?->image ? 'Remplacer l\'image' : 'Image de fond')
                    ->image()
                    ->disk('public')
                    ->directory('sliders')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->columnSpanFull()
                    ->helperText('Recommandé : 1920×900 px, JPG ou WebP, max 5 Mo'),

                TextInput::make('bouton_texte')
                    ->label('Texte du bouton')
                    ->default('Réserver maintenant')
                    ->maxLength(50)
                    ->placeholder('Ex : Découvrir nos chambres'),

                TextInput::make('bouton_lien')
                    ->label('Lien du bouton')
                    ->default('/chambres')
                    ->maxLength(200)
                    ->placeholder('/chambres ou /chambres?type=suite'),

                TextInput::make('ordre')
                    ->label('Ordre d\'affichage')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->helperText('0 = affiché en premier'),

                Toggle::make('actif')
                    ->label('Slide actif')
                    ->default(true)
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('ordre')
            ->columns([
                ImageColumn::make('image')
                    ->label('Aperçu')
                    ->disk('public')
                    ->height(60)
                    ->width(100)
                    ->defaultImageUrl('https://placehold.co/200x80/1e3a5f/ffffff?text=No+Image'),

                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->limit(50)
                    ->weight('bold'),

                TextColumn::make('sous_titre')
                    ->label('Sous-titre')
                    ->limit(60)
                    ->color('gray'),

                TextColumn::make('ordre')
                    ->label('Ordre')
                    ->numeric()
                    ->sortable()
                    ->badge()
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
            'index'  => ListSliderHeros::route('/'),
            'create' => CreateSliderHero::route('/create'),
            'edit'   => EditSliderHero::route('/{record}/edit'),
        ];
    }
}
