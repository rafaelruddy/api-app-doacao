<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CampaignResource\Pages;
use App\Models\Address;
use App\Models\Campaign;
use App\Models\Institution;
use App\Models\Item;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

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
                    TextInput::make('name')
                        ->required()
                        ->label('Nome'),
                    TextInput::make('description')
                        ->required()
                        ->label('Descrição'),
                    DateTimePicker::make('start_date')
                        ->minDate(now())
                        ->required()
                        ->native(false)
                        ->label('Data do Início'),
                    DateTimePicker::make('end_date')
                        ->required()
                        ->minDate(now())
                        ->after('start_date')
                        ->native(false)
                        ->label('Data do Término'),
                    TimePicker::make('donation_start_time')
                        ->seconds(false)
                        ->required()
                        ->label('Hora que começa a aceitar as doações'),
                    TimePicker::make('donation_end_time')
                        ->seconds(false)
                        ->required()
                        ->after('donation_start_time')
                        ->label('Hora que termina de aceitar as doações'),
                    TextInput::make('items_quantity_objective')
                        ->required()
                        ->label('Meta de doações (quantidade)'),
                ]),
                Section::make('Itens Necessários')->schema([
                    Repeater::make('necessary_items')
                        ->hiddenLabel()
                        ->relationship()
                        ->defaultItems(0)
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $total = 0;
                            foreach ($get('necessary_items') as $item) {
                                if ($item['quantity_objective']) {
                                    $total += $item['quantity_objective'];
                                }
                            }
                            $set('items_quantity_objective', $total);
                        })
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
                            TextInput::make('quantity_objective')
                                ->required()
                                ->minValue(0)
                                ->numeric()
                                ->label('Quantidade'),
                        ])
                        ->columns(2),
                ]),
                Section::make('Endereços de Coleta')->schema([
                    Repeater::make('addressess')
                        ->hiddenLabel()
                        ->relationship()
                        ->defaultItems(0)
                        ->schema([
                            Group::make()->schema([
                                TextInput::make('street')->required()->label('Rua'),
                                TextInput::make('city')->required()->label('Cidade'),
                                TextInput::make('state')->required()->label('Estado'),
                                TextInput::make('zipcode')->required()->label('CEP'),
                                TextInput::make('latitude')->required()->label('Latitude'),
                                TextInput::make('longitude')->required()->label('Longitude'),
                            ])->columns(2),

                        ]),
                ]),
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
