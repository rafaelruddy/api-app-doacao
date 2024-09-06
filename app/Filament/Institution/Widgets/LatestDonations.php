<?php

namespace App\Filament\Institution\Widgets;

use App\Models\Donation;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestDonations extends BaseWidget
{

    protected static ?int $sort = 5;

    protected static ?string $heading = "Doações Recentes";

    protected int | string | array $columnSpan = [
        'xs' => 2,
        'sm' => 2,
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
        '2xl' => 2,
    ];
    public function table(Table $table): Table
    {
        $institution = Filament::getTenant();
        return $table
            ->query(
                Donation::query()
                ->withWhereHas('campaign', function ($query) use ($institution) {
                    $query->where('institution_id', $institution->id);
                })
                ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('Donator.name')->label('Doador'),
                TextColumn::make('Campaign.name')->label('Campanha'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Donation::STATUS[$state])
                    ->color(fn ($state) => Donation::STATUS_COLOR[$state]),
                TextColumn::make('date')
                    ->label('Data da Doação')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
