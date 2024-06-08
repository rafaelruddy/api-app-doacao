<?php

namespace App\Filament\Institution\Widgets;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donator;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsInstitutionOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $institution = Filament::getTenant();
        $donator = Donator::get();
        $campaign = Campaign::query()->where('institution_id', $institution->id)->get();
        $donation = Donation::query()->withWhereHas('campaign', function ($query) use ($institution) {
            $query->where('institution_id', $institution->id);
        });
        return [
            //
            Stat::make('Doadores cadastrados', $donator->count())
                ->color('info')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Campanhas cadastradas', $campaign->count())
                ->description($campaign->where('status', 'active')->count() . ' ativas')
                ->color('warning')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Doações cadastradas', $donation->count())
                ->description($donation->where('status', 'concluded')->count() . ' concluidas')
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
