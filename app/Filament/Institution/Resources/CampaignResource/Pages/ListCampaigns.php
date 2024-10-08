<?php

namespace App\Filament\Institution\Resources\CampaignResource\Pages;

use App\Filament\Institution\Resources\CampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCampaigns extends ListRecords
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Criar Campanha')->icon('heroicon-o-plus'),
        ];
    }
}
