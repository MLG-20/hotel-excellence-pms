<?php

namespace App\Filament\Reception\Widgets;

use App\Models\Chambre;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ChambresDisponiblesWidget extends BaseWidget
{
    protected static ?string $heading = 'Chambres disponibles';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Chambre::query()->where('statut', 'disponible')->orderBy('etage')->orderBy('numero'))
            ->columns([
                TextColumn::make('numero')
                    ->label('Chambre')
                    ->badge()
                    ->color('success'),
                TextColumn::make('etage')
                    ->label('Étage')
                    ->formatStateUsing(fn ($state) => $state == 0 ? 'RDC' : 'Étage ' . $state)
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'simple'          => 'gray',
                        'double'          => 'info',
                        'familiale'       => 'warning',
                        'suite'           => 'success',
                        'presidentielle'  => 'danger',
                        default           => 'gray',
                    }),
                TextColumn::make('capacite')
                    ->label('Capacité')
                    ->suffix(' pers.')
                    ->alignCenter(),
                TextColumn::make('prix_nuit')
                    ->label('Prix / nuit')
                    ->money('XOF')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->color('gray'),
            ])
            ->paginated(false);
    }
}
