<?php

namespace App\Providers;

use App\Models\Donation;
use App\Models\Role;
use App\Observers\DonationObserver;
use App\Policies\RolePolicy;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Illuminate\Support\Facades\Gate;
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

        Gate::policy(Role::class, RolePolicy::class);

        //
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch
                ->modalWidth('md')
                ->modalHeading('Painel')
                ->icons([
                    'admin' => 'heroicon-o-building-library',
                    'institution' => 'heroicon-o-gift',
                ])
                ->iconSize(24)
                ->labels([
                    'admin' => 'Administrativo',
                    'institution' => 'Instituições'
                ])
                ->canSwitchPanels(fn (): bool => true);
        });

        Donation::observe(DonationObserver::class);
    }
}
