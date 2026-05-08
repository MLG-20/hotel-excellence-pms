<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Gouvernante\Pages\Housekeeping;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Filament\Support\Colors\Color;
use App\Filament\Gouvernante\Widgets\GouvernanteStatsOverview;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GouvernantePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('gouvernante')
            ->path('gouvernante')
            ->login()
            ->brandName('Gestion Hôtelière — Housekeeping')
            ->colors(['primary' => Color::Emerald])
            ->authGuard('gouvernante')
            ->darkMode(isForced: true)
            ->discoverPages(
                in: app_path('Filament/Gouvernante/Pages'),
                for: 'App\Filament\Gouvernante\Pages'
            )
            ->pages([Dashboard::class, Housekeeping::class])
            ->discoverWidgets(
                in: app_path('Filament/Gouvernante/Widgets'),
                for: 'App\Filament\Gouvernante\Widgets'
            )
            ->widgets([
                GouvernanteStatsOverview::class,
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
            ->authMiddleware([Authenticate::class])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '
                    <script src="https://cdn.tailwindcss.com"></script>
                    <link rel="stylesheet" href="/css/gouvernante.css?v=' . time() . '">
                ',
            );
    }
}
