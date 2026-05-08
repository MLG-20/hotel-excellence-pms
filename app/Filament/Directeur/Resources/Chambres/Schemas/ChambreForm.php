<?php

namespace App\Filament\Directeur\Resources\Chambres\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ChambreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('numero')
                    ->label('Numéro de chambre')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'simple' => 'Simple',
                        'double' => 'Double',
                        'suite' => 'Suite',
                        'familiale' => 'Familiale',
                        'presidentielle' => 'Présidentielle',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('etage')
                    ->label('Étage')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                TextInput::make('capacite')
                    ->label('Capacité (personnes)')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
                TextInput::make('prix_nuit')
                    ->label('Prix / nuit (FCFA)')
                    ->required()
                    ->numeric()
                    ->prefix('FCFA'),
                Select::make('statut')
                    ->label('Statut')
                    ->options([
                        'disponible' => 'Disponible',
                        'occupee' => 'Occupée',
                        'maintenance' => 'Maintenance',
                        'nettoyage' => 'Nettoyage',
                    ])
                    ->default('disponible')
                    ->required()
                    ->native(false),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),

                Placeholder::make('images_actuelles')
                    ->label('Photos actuelles')
                    ->content(function ($record) {
                        $images = $record?->images ?? [];
                        if (empty($images)) {
                            return new HtmlString('<span style="color:#9ca3af">Aucune photo uploadée</span>');
                        }
                        $html = '<div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px">';
                        foreach ($images as $path) {
                            $html .= '<img src="' . Storage::url($path) . '" style="height:80px;width:120px;object-fit:cover;border-radius:6px;border:2px solid #f59e0b">';
                        }
                        $html .= '</div>';
                        return new HtmlString($html);
                    })
                    ->hiddenOn('create')
                    ->columnSpanFull(),

                FileUpload::make('images')
                    ->label(fn ($record) => $record?->images ? 'Remplacer toutes les photos' : 'Photos de la chambre')
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->directory('chambres')
                    ->visibility('public')
                    ->reorderable()
                    ->maxFiles(8)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->columnSpanFull()
                    ->helperText('Glissez jusqu\'à 8 photos (JPG/PNG/WebP, max 5 Mo). La 1ère photo = image principale. Laisser vide = garder les photos actuelles.'),
            ]);
    }
}
