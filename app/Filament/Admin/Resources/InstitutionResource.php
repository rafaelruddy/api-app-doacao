<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InstitutionResource\Pages\CreateInstitution;
use App\Filament\Admin\Resources\InstitutionResource\Pages\EditInstitution;
use App\Filament\Admin\Resources\InstitutionResource\Pages\ListInstitutions;
use App\Models\Institution;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                Section::make('Dados')->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->required(),

                    TextInput::make('phone')
                        ->label('Telefone'),

                    Textarea::make('description'),

                    Select::make('status')
                        ->label('Status')
                        ->options(Institution::STATUS)
                        ->native(false)
                        ->required(),
                ]),

                Section::make('Localização')->schema([
                    TextInput::make('latitude')
                        ->label('Latitude')
                        ->required(),

                    TextInput::make('longitude')
                        ->label('Longitude')
                        ->required(),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
