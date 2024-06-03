<?php

namespace App\Filament\Admin\Resources\DonatorResource\Pages;

use App\Filament\Admin\Resources\DonatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDonator extends ViewRecord
{
    protected static string $resource = DonatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
