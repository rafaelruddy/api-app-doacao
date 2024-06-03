<?php

namespace App\Filament\Admin\Resources\DonatorResource\Pages;

use App\Filament\Admin\Resources\DonatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonators extends ListRecords
{
    protected static string $resource = DonatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Criar Doador')->icon('heroicon-o-plus'),
        ];
    }
}
