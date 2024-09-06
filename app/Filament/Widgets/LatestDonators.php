<?php

namespace App\Filament\Widgets;

use App\Models\Donator;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestDonators extends BaseWidget
{

    protected static ?int $sort = 5;

    protected static ?string $heading = "Novos Doadores";

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
        return $table
            ->query(
                Donator::query()->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('name')
                ->label('Nome')
                ->searchable(),
                TextColumn::make('email')
                ->label('Email')
                ->searchable(),
                TextColumn::make('phone')
                ->label('Telefone')
                ->searchable(),
            ]);
    }
}
