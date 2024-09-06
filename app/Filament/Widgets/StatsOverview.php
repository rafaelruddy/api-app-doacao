<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donator;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $donator = Donator::get();
        $donation = Donation::get();
        $campaign = Campaign::get();
        return [
            //
            Stat::make('Doadores cadastrados', $donator->count())
                ->color('info')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Doações cadastradas', $donation->count())
                ->color('success')
                ->description($donation->where('status', 'concluded')->count() . ' concluídas')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Campanhas cadastradas', $campaign->count())
                ->description($campaign->where('status', 'active')->count() . ' ativas')
                ->color('warning')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
