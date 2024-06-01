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
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $activeNavigationIcon = 'heroicon-s-book-open';

    protected static ?string $modelLabel = 'Campanhas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados da Campanha')->schema([
                    Group::make()->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->avatar()
                            ->collection('avatarsCampaigns')
                            ->imageEditor()
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                            ])
                            ->hiddenLabel(),
                        SpatieMediaLibraryFileUpload::make('banner')
                            ->collection('bannersCampaigns')
                            ->imageEditor()
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                            ])
                            ->hiddenLabel(),
                    ])->columns(2),

                    Select::make('institution_id')
                        ->relationship(name: 'institution', titleAttribute: 'name')
                        ->label('Instituição')
                        ->native(false)
                        ->required(),
                    TextInput::make('name')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->label('Nome'),
                    Textarea::make('description')
                        ->required()
                        ->label('Descrição'),
                    DateTimePicker::make('start_date')
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
                ]),
                Section::make('Itens Necessários')->schema([
                    Repeater::make('necessary_items')
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
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->collection('avatarsCampaigns'),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                TextColumn::make('items_quantity_objective')
                    ->label('Objetivo '),

                TextColumn::make('institution.name'),

                TextColumn::make('start_date')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
