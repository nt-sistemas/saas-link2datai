<?php

namespace App\Filament\Resources\Grupos\Schemas;

use App\Models\Categoria;
use App\Models\TipoGrupo;
use App\Models\GrupoEstoque;
use Filament\Schemas\Schema;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitado;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class GrupoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('tipo_grupo_id')
                ->label('Tipo de Pedidos')
                ->options(TipoGrupo::query()->where('tenant_id', auth()->user()->tenant_id)->pluck('name', 'id'))
                    ->required(),
                TextInput::make('name')
                ->label('Nome do Grupo')
                    ->required(),
                TextInput::make('description')
                ->label('Descrição'),
                TextInput::make('order')
                ->label('Ordem')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('grupo_estoque_id')
                    ->label('Grupo de Estoque')
                    ->relationship('grupo_estoque', 'name')
                    ->multiple()
                    ->preload()
                    ->columnSpanFull(),
                Select::make('plano_habilitado_id')
                    ->label('Plano Habilitado')
                    ->multiple()
                    ->preload()
                    ->relationship('plano_habilitados', 'name')
                    ->columnSpanFull(),
                Select::make('modalidade_venda_id')
                    ->label('Modalidade de Venda')
                    ->multiple()
                    ->preload()
                    ->relationship('modalidade_venda', 'name')
                    ->columnSpanFull(),
                Select::make('campo_valor_id')
                    ->label('Campo de Valor')
                    ->options([
                        'base_faturamento_compra' => 'Base de Faturamento - Compra',
                        'valor_total' => 'Valor Total',
                        'valor_franquia' => 'Valor da Franquia'
                    ]) // Adicione as opções apropriadas aqui
                    ->required(),
                Select::make('categoria_id')
                ->label('Categoria')
                    ->relationship('categoria', 'name')
                    ->required(),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
