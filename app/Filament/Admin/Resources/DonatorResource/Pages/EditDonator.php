<?php

namespace App\Filament\Admin\Resources\DonatorResource\Pages;

use App\Filament\Admin\Resources\DonatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonator extends EditRecord
{
    protected static string $resource = DonatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
