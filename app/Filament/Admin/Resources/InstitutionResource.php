<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InstitutionResource\Pages\CreateInstitution;
use App\Filament\Admin\Resources\InstitutionResource\Pages\EditInstitution;
use App\Filament\Admin\Resources\InstitutionResource\Pages\ListInstitutions;
use App\Models\Institution;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $activeNavigationIcon = 'heroicon-s-building-library';

    protected static ?string $navigationGroup = 'Instituição';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Instituições';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados')->schema([

                    Group::make()->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->avatar()
                            ->circleCropper()
                            ->collection('avatar')
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('banner')
                            ->collection('banner')
                            ->required(),

                    ])->columns(2),

                    Group::make()->schema([

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



                        Select::make('status')
                            ->label('Status')
                            ->options(Institution::STATUS)
                            ->native(false)
                            ->required(),

                    ])->columns(3),
                    Textarea::make('description')
                        ->label('Descrição')
                        ->rows(5)
                        ->required(),



                ])->columnSpanFull(),

                Fieldset::make()
                    ->label('Endereço')
                    ->relationship('address')
                    ->schema([

                        TextInput::make('street')
                            ->label('Rua'),

                        TextInput::make('city')
                            ->label('Cidade'),

                        TextInput::make('state')
                            ->label('Estado'),

                        TextInput::make('zipcode')
                            ->label('CEP')
                            ->stripCharacters(['-'])
                            ->mask(RawJs::make(<<<'JS'
                                    '99999-999'
                                JS)),
                                
                        TextInput::make('number')
                            ->label('Número'),

                        TextInput::make('neighborhood')
                            ->label('Bairro'),


                        TextInput::make('latitude')
                            ->label('Latitude'),

                        TextInput::make('longitude')
                            ->label('Longitude'),

                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->collection('avatar'),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => Institution::STATUS[$state])
                    ->color(fn($state) => Institution::STATUS_COLOR[$state]),

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
            InstitutionResource\RelationManagers\UsersRelationManager::class,
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
