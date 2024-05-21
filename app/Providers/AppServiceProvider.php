<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch
                ->modalWidth('md')
                ->modalHeading('Painel')
                ->icons([
                    'admin' => 'heroicon-o-building-library',
                    'institution' => 'heroicon-o-shopping-cart',
                ])
                ->iconSize(24)
                ->labels([
                    'admin' => 'Administrativo',
                    'institution' => 'Instituições'
                ])
                ->canSwitchPanels(fn (): bool => true);
        });
    }
}
