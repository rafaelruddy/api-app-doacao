<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DonationResource\Pages;
use App\Filament\Admin\Resources\DonationResource\RelationManagers;
use App\Models\Donation;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $activeNavigationIcon = 'heroicon-s-gift';

    protected static ?string $navigationGroup = 'Doação';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Doações';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('donator_id')
                    ->relationship(name: 'donator', titleAttribute: 'name')
                    ->label('Doador')
                    ->native(false)
                    ->required(),
                Select::make('campaign_id')
                    ->relationship(name: 'campaign', titleAttribute: 'name')
                    ->native(false)
                    ->label('Campanha'),
                DateTimePicker::make('date')
                    ->required()
                    ->seconds(false)
                    ->native(false)
                    ->label('Data de Doação'),
                Select::make('status')
                    ->label('Status')
                    ->options(Donation::STATUS)
                    ->native(false)
                    ->required(),
                Section::make('Itens Doados')->schema([
                    Repeater::make('donated_items')
                        ->hiddenLabel()
                        ->relationship()
                        ->defaultItems(0)
                        ->live()
                        ->schema([
                            Select::make('item_id')
                                ->label('Item')
                                ->options(Item::query()
                                    ->pluck('name', 'id'))
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->distinct()
                                ->searchable()
                                ->required()
                                ->native(false),
                            TextInput::make('quantity')
                                ->required()
                                ->minValue(0)
                                ->numeric()
                                ->label('Quantidade'),
                        ])
                        ->columns(2),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Donator.name'),
                TextColumn::make('Campaign.name'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Donation::STATUS[$state])
                    ->color(fn ($state) => Donation::STATUS_COLOR[$state]),
                TextColumn::make('date')
                    ->label('Data da Doação')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'view' => Pages\ViewDonation::route('/{record}'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
