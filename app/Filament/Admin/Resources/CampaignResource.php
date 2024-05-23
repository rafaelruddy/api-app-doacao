<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CampaignResource\Pages;
use App\Models\Address;
use App\Models\Campaign;
use App\Models\Institution;
use App\Models\Item;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Campanhas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados da Campanha')->schema([
                    Select::make('institution_id')->relationship(name: 'institution', titleAttribute: 'name')
                        ->label('Instituição')
                        ->native(false)
                        ->required(),

                    TextInput::make('name')->label('Nome'),
                    TextInput::make('description')->label('Descrição'),
                    DatePicker::make('date')->label('Data'),
                    TextInput::make('items_quantity_objective'),
                ]),
                Section::make('Itens Necessários')->schema([
                    Repeater::make('necessary_items')
                        ->label('')
                        ->relationship()
                        ->defaultItems(0)
                        ->schema([
                            Select::make('item_id')
                                ->label('Item')
                                ->options(Item::query()
                                    ->pluck('name', 'id'))
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->distinct()
                                ->searchable()
                                ->native(false),
                            TextInput::make('quantity_objective')->label('Quantidade'),
                        ])->columns(2),
                ]),
                // Section::make('Endereços de Coleta')->schema([
                //     Repeater::make('addressess')
                //         ->label('')
                //         ->relationship()
                //         ->defaultItems(0)
                //         ->schema([
                //             Group::make()->schema([
                //                 TextInput::make('address_street')->label('Rua'),
                //                 TextInput::make('address_city')->label('Cidade'),
                //                 TextInput::make('address_state')->label('Estado'),
                //                 TextInput::make('address_zipcode')->label('CEP'),
                //                 TextInput::make('address_latitude')->label('Latitude'),
                //                 TextInput::make('address_longitude')->label('Longitude'),
                //             ])->columns(2),

                //         ]),
                // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Data'),
                TextColumn::make('items_quantity_objective')
                    ->label('Objetivo '),
                TextColumn::make('institution.name')
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



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'view' => Pages\ViewCampaign::route('/{record}'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}