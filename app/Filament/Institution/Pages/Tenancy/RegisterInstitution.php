<?php

namespace App\Filament\Institution\Pages\Tenancy;

use App\Models\Institution;
use App\Models\Team;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Support\RawJs;

class RegisterInstitution extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Registrar Instituição';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Dados')->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->columnSpanFull()
                            ->collection('avatar'),
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
                    ->label('Endereço')
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

    protected function handleRegistration(array $data): Institution
    {
        $institution = Institution::create($data);

        $institution->users()->attach(auth()->user());

        return $institution;
    }
}
