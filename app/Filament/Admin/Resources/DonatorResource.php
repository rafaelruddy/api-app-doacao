<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DonatorResource\Pages;
use App\Models\Donator;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class DonatorResource extends Resource
{
    protected static ?string $model = Donator::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';

    protected static ?string $activeNavigationIcon = 'heroicon-s-face-smile';

    protected static ?string $navigationGroup = 'Doação';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Doadores';


    public static function form(Form $form): Form
    {
        function getInformacaoPessoal(): Component
        {
            return
                Section::make('Informação pessoal')
                ->columns([

                    'md' => 5,
                    'xl' => 7,
                    '2xl' => 10,
                ])
                ->schema([
                    Hidden::make('id'),

                    Group::make()
                        ->columnSpan([
                            'md' => 4,
                            'xl' => 6,
                            '2xl' => 10,
                        ])
                        ->columns(2)
                        ->schema([
                            TextInput::make('name')->label('Nome Completo')
                                ->columnSpanFull()
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label('Email')
                                ->unique(ignoreRecord: true)
                                ->email()
                                ->required()
                                ->maxLength(255),

                            TextInput::make('phone')
                                ->label('Telefone')
                                ->tel()
                                ->required()
                                ->stripCharacters([' ', '-'])
                                ->mask(RawJs::make(<<<'JS'
                                    '+99 99 99999-9999'
                                JS)),

                            TextInput::make('password')
                                ->placeholder('*********')
                                ->label('Senha')
                                ->password()
                                ->nullable()
                                ->revealable()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required(fn (string $context): bool => $context === 'create')
                                ->rules([
                                    'min:8',
                                    'string',
                                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                                ]),

                            ]),

                ]);
        }
        function getPasswordInput(): component
        {
            return Section::make('Dados de Acesso')
                ->columns([
                    'sm' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->schema([
                    Toggle::make('passwordDisable')
                        ->label('Redefinir senha')
                        ->default(true)
                        ->dehydrated()
                        ->live()
                        ->hiddenOn('create'),

                    Grid::make()->columns(2)->schema([
                        Group::make([
                            TextInput::make('password')
                                ->placeholder('*********')
                                ->label('Senha')
                                ->password()
                                ->nullable()
                                ->confirmed()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required(fn (string $context): bool => $context === 'create')
                                ->rules([
                                    'min:8',
                                    'string',
                                    'confirmed', // Campo de confirmação da senha
                                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                                ])
                                ->autocomplete(false)->disabled(fn (Get $get): bool => !$get('passwordDisable')),
                        ]),

                        Group::make([
                            TextInput::make('password_confirmation')
                                ->placeholder('*********')
                                ->password()
                                ->label('Confirmar senha')
                                ->required(fn (string $context): bool => $context === 'create')
                                ->rules([
                                    'min:8',
                                ])
                                ->autocomplete(false)->disabled(fn (Get $get): bool => !$get('passwordDisable')),
                        ]),
                    ]),
                ]);
        }
        function getPermissaoInput(): component
        {
            return Section::make('Permissões menu')
                ->columns(2)
                ->schema([
                    Select::make('Permissão')
                        ->label('Função')
                        ->native(false)
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'title',
                            modifyQueryUsing: fn (Builder $query) => Auth()->user()->hasRole('Super') ? null : $query->where('title', '!=', 'Inativo')->where('title', '!=', 'Super'),
                        ),

                    Select::make('status')
                        ->native(false)
                        ->default('Ativo')
                        ->options([
                            'Ativo' => 'Ativo',
                            'Inativo' => 'Inativo',
                        ]),
                ]);
        }
        return $form
            ->schema([
                getInformacaoPessoal(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonators::route('/'),
            'create' => Pages\CreateDonator::route('/create'),
            'view' => Pages\ViewDonator::route('/{record}'),
            'edit' => Pages\EditDonator::route('/{record}/edit'),
        ];
    }
}
