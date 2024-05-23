<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InstitutionResource\Pages\CreateInstitution;
use App\Filament\Admin\Resources\InstitutionResource\Pages\EditInstitution;
use App\Filament\Admin\Resources\InstitutionResource\Pages\ListInstitutions;
use App\Models\Institution;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Instituições';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Dados')->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required(),

                        TextInput::make('phone')
                            ->label('Telefone')
                            ->tel()
                            ->required()
                            ->stripCharacters([' ', '-'])
                            ->mask(RawJs::make(<<<'JS'
                          '+99 99 99999-9999'
            JS)),

                        Textarea::make('description'),

                        Select::make('status')
                            ->label('Status')
                            ->options(Institution::STATUS)
                            ->native(false)
                            ->required(),
                    ])->columns(2),
                ])->columnSpanFull(),

                Fieldset::make()
                    ->relationship('address')->schema([
                        TextInput::make('street')->label('Rua'),
                        TextInput::make('city')->label('Cidade'),
                        TextInput::make('state')->label('Estado'),
                        TextInput::make('zipcode')->label('CEP'),
                        TextInput::make('latitude')->label('Latitude'),
                        TextInput::make('longitude')->label('Longitude'),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Institution::STATUS[$state])
                    ->color(fn ($state) => Institution::STATUS_COLOR[$state]),


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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstitutions::route('/'),
            'create' => CreateInstitution::route('/create'),
            'edit' => EditInstitution::route('/{record}/edit'),
        ];
    }
}
