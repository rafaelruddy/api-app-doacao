<?php

namespace App\Filament\Admin\Resources\InstitutionResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        $institutionId = $this->getOwnerRecord()->id;
        $userOptions = User::whereDoesntHave('institutions', function ($query) use ($institutionId) {
            $query->where('institutions.id', $institutionId);
        })->get()->pluck('name', 'id');
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('SincronizarUsuario')->label("Sincronizar Usuários")->icon('heroicon-m-link')->color("success")->form([
                    Select::make('user_id')->label('Usuário')->options($userOptions)->native(false)->searchable()->required()
                ])->action(function (array $data) {
                    $data['user_id'];
                    $user = User::find($data['user_id']);
                    DB::table('institution_users')->insert([
                        'institution_id' => $this->ownerRecord->id,
                        'user_id' => $user->id
                    ]);
                })
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
