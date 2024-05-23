<?php

namespace App\Filament\Admin\Resources\DonatorResource\Pages;

use App\Filament\Admin\Resources\DonatorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDonator extends CreateRecord
{
    protected static string $resource = DonatorResource::class;
}
