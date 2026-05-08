<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Directeur\Widgets\DirecteurStatsOverview;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DirecteurPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('directeur')
            ->path('directeur')
            ->login()
            ->brandName('Gestion Hôtelière — Direction')
            ->colors(['primary' => Color::Indigo])
            ->authGuard('directeur')
            ->discoverResources(
                in: app_path('Filament/Directeur/Resources'),
                for: 'App\Filament\Directeur\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Directeur/Pages'),
                for: 'App\Filament\Directeur\Pages'
            )
            ->pages([Dashboard::class])
            ->discoverWidgets(
                in: app_path('Filament/Directeur/Widgets'),
                for: 'App\Filament\Directeur\Widgets'
            )
            ->widgets([
                DirecteurStatsOverview::class,
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
