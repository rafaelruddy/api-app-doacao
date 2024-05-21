<?php

namespace App\Filament\Admin\Resources\InstitutionResource\Pages;

use App\Filament\Admin\Resources\InstitutionResource;
use App\Models\Institution;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;

class EditInstitution extends EditRecord
{
    protected static string $resource = InstitutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make("Alterar Status")->label("Alterar Status")->icon('heroicon-m-arrows-right-left')->form([
                Select::make("status")
                    ->native(false)
                    ->options([
                        'analysis' => 'Análise',
                        'reprooved' => 'Reprovado',
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                    ])
                    ->preload()
                    ->searchable()
                    ->required()
                    ->default(fn (Institution $record) => $record->status)
            ])->action(function (array $data, Institution $record) {

                try {
                    $record->status = $data["status"];

                    if ($record->status === 'cancelado' || $record->status === 'falha_pagamento') {
                        foreach ($record->ProdutosPedidos as $produto_pedido) {
                            $produto_franquia = $produto_pedido->Produto;
                            $produto_franquia->quantidade += $produto_pedido->quantidade;
                            $produto_franquia->save();
                        }
                    }

                    $record->save();
                    return
                        Notification::make()
                        ->title('Status Atualizado com Sucesso')
                        ->success()
                        ->send();
                } catch (\Throwable $th) {
                    return Notification::make()
                        ->title('Error')
                        ->danger()
                        ->send();
                }
            }),
        ];
    }

}
