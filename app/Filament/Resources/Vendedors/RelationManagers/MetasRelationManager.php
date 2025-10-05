<?php

namespace App\Filament\Resources\Vendedors\RelationManagers;

use Carbon\Carbon;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MetasRelationManager extends RelationManager
{
    protected static string $relationship = 'metas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('grupo_id')
                    ->label('Grupo')
                    ->relationship('grupo', 'name')
                    ->required(),
                Select::make('filial_id')
                    ->label('Filial')
                    ->relationship('filial', 'name')
                    ->required(),
                TextInput::make('ano')
                    ->default(Carbon::now()->year)
                    ->required()
                    ->numeric(),
                TextInput::make('mes')
                    ->default(Carbon::now()->month)
                    ->required()
                    ->numeric(),
                TextInput::make('valor_meta')
                    ->numeric(),
                TextInput::make('quantidade')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Metas')
            ->columns([
                TextColumn::make('grupo.name')
                    ->label('Grupo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('filial.name')
                    ->label('Filial')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('mes')
                    ->sortable(),
                TextColumn::make('ano')
                    ->sortable(),
                TextColumn::make('valor_meta')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('quantidade')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['tenant_id'] = auth()->user()->tenant_id;

                        return $data;
                    })->using(function (array $data) {
                        $vendedor_id = $this->ownerRecord->id;

                        $meta_existente = \App\Models\Meta::where('tenant_id', auth()->user()->tenant_id)
                            ->where('grupo_id', $data['grupo_id'])
                            ->where('filial_id', $data['filial_id'])
                            ->where('vendedor_id', $vendedor_id)
                            ->where('ano', $data['ano'])
                            ->where('mes', $data['mes'])
                            ->first();


                        if ($meta_existente) {
                            // Lógica para lidar com o caso onde a meta já existe
                            // Por exemplo, lançar uma exceção ou retornar uma mensagem de erro
                            Notification::make()
                                ->danger()
                                ->title('Meta Já existe para este Vendedor neste Mês/Ano')
                                ->send();
                            return;
                        }

                        return \App\Models\Meta::create([
                            'tenant_id' => auth()->user()->tenant_id,
                            'grupo_id' => $data['grupo_id'],
                            'filial_id' => $data['filial_id'],
                            'vendedor_id' => $vendedor_id,
                            'ano' => $data['ano'],
                            'mes' => $data['mes'],
                            'valor_meta' => $data['valor_meta'],
                            'quantidade' => $data['quantidade'],
                        ]);
                    })
                //AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                //DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
