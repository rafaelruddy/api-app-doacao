<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
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
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $activeNavigationIcon = 'heroicon-s-user';

    protected static ?string $navigationGroup = 'Usuários';

    protected static ?string $modelLabel = 'Usuários';


    public static function form(Form $form): Form
    {

        function getInformacaoPessoal(): Component
        {
            return
                Section::make('Informação pessoal')
                ->columns([
                    'sm' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->schema([
                    Hidden::make('id'),
                    Group::make()->columnSpan([
                        'sm' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])->columns(2)->schema([
                        TextInput::make('name')
                            ->label('Nome completo')
                            ->columnSpanFull()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')->label('Email')->unique(ignoreRecord: true)
                            ->email()
                            ->required()
                            ->maxLength(255),

                        //                 TextInput::make('phone')
                        //                 ->unique(ignoreRecord: true)
                        //                 ->label('Telefone')
                        //                 ->tel()
                        //                 ->required()
                        //                 ->stripCharacters([' ', '-'])
                        //                 ->mask(RawJs::make(<<<'JS'
                        //               '+99 99 99999-9999'
                        // JS)),
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
                                ->revealable()
                                ->label('Senha')
                                ->password()
                                ->nullable()
                                ->confirmed()
                                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->required(fn(string $context): bool => $context === 'create')
                                ->rules([
                                    'min:8',
                                    'string',
                                    'confirmed', // Campo de confirmação da senha
                                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                                ])
                                ->autocomplete(false)->disabled(fn(Get $get): bool => !$get('passwordDisable')),
                        ]),
                        Group::make([
                            TextInput::make('password_confirmation')
                                ->placeholder('*********')
                                ->password()
                                ->revealable()
                                ->label('Confirmar senha')
                                ->required(fn(string $context): bool => $context === 'create')
                                ->rules([
                                    'min:8',
                                ])
                                ->autocomplete(false)->disabled(fn(Get $get): bool => !$get('passwordDisable')),
                        ]),
                    ]),
                ]);
        }
        function getPermissaoInput(): component
        {
            return Section::make('Permissões menu')->columns(2)
                ->schema([
                    Select::make('roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->columnSpanFull()
                        ->searchable(),
                ]);
        }

        return $form
            ->schema([
                getInformacaoPessoal(),
                getPasswordInput(),
                getPermissaoInput()->disabled(fn(Get $get) => !Auth()->user()->hasRole('super_admin')),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            // 'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
