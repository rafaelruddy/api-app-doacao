<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CampaignResource\Pages;
use App\Models\Address;
use App\Models\Campaign;
use App\Models\Institution;
use App\Models\Item;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
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

    protected static ?string $navigationGroup = 'Instituição';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Campanhas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados da Campanha')->schema([
                    Group::make()->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->avatar()
                            ->collection('avatar')
                            ->imageEditor()
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                            ])
                            ->hiddenLabel(),
                        SpatieMediaLibraryFileUpload::make('banner')
                            ->collection('banner')
                            ->imageEditor()
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                            ])
                            ->hiddenLabel(),
                        Select::make('institution_id')
                            ->relationship(name: 'institution', titleAttribute: 'name')
                            ->label('Instituição')
                            ->native(false)
                            ->required(),
                        TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Nome'),
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
                            ->label('Hora inicial das doações'),
                        TimePicker::make('donation_end_time')
                            ->seconds(false)
                            ->required()
                            ->after('donation_start_time')
                            ->label('Hora final das doações'),
                    ])->columns(2),

                    Textarea::make('description')
                        ->required()
                        ->label('Descrição')
                        ->rows(5),

                ])->columns(1),
                Section::make('Itens Necessários')->schema([
                    Repeater::make('necessary_items')
                        ->hiddenLabel()
                        ->addActionLabel('Adicionar Item')
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
                        ->grid(2)
                        ->columns(2),

                    Placeholder::make('total')
                        ->label('Meta Total de itens')
                        ->content(function (Get $get): string {
                            $total = 0;
                            foreach ($get('necessary_items') as $item) {
                                if ($item['quantity_objective']) {
                                    $total += $item['quantity_objective'];
                                }
                            }
                            return $total;
                        })
                ]),
                Section::make('Endereços de Coleta')->schema([
                    Repeater::make('addressess')
                        ->addActionLabel('Adicionar Endereço')
                        ->hiddenLabel()
                        ->relationship()
                        ->defaultItems(0)
                        ->schema([
                            Group::make()->schema([
                                TextInput::make('street')->required()->label('Rua'),
                                TextInput::make('zipcode')->required()->label('CEP'),
                                TextInput::make('city')->required()->label('Cidade'),
                                TextInput::make('state')->required()->label('Estado'),
                                TextInput::make('neighborhood')->required()->label('Bairro'),
                                TextInput::make('number')->required()->label('Número'),
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
                    ->collection('avatar'),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                TextColumn::make('institution.name')
                    ->label("Instituição"),

                TextColumn::make('start_date')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
