<?php

namespace App\Providers\Filament;

use App\Models\Institution;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class InstitutionPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('institution')
            ->path('institution')
            ->tenant(Institution::class)
            ->sidebarCollapsibleOnDesktop()
            ->topNavigation()
            ->colors([
                'primary' => Color::Amber,
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Green,
                'teal' => Color::Teal,
                'slate' => Color::Slate,
                'warning' => Color::Amber,
                'sky' => Color::Sky,
                'fuchsia' => Color::Fuchsia,
                'purple' => Color::Purple,
                'pink' => Color::Pink,
                'rose' => Color::Rose,
                'indigo' => Color::Indigo,
                'yellow' => Color::Yellow,
                'orange' => Color::Orange,
                'cyan' => Color::Cyan,
                'neutral' => Color::Neutral,
                'stone' => Color::Stone,
                'lime' => Color::Lime,
                'violet' => Color::Violet,
            ])
            ->discoverResources(in: app_path('Filament/Institution/Resources'), for: 'App\\Filament\\Institution\\Resources')
            ->discoverPages(in: app_path('Filament/Institution/Pages'), for: 'App\\Filament\\Institution\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Institution/Widgets'), for: 'App\\Filament\\Institution\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
