<?php

namespace App\Filament\Resources\Grupos\Schemas;

use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitado;
use App\Models\TipoGrupo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GrupoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome do Grupo')
                    ->required(),
                Select::make('tipo_grupo_id')
                    ->label('Tipo de Pedidos')
                    ->multiple()
                    ->preload()
                    ->options(TipoGrupo::query()->where('tenant_id', auth()->user()->tenant_id)->pluck('name', 'id'))
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
                    ->options(GrupoEstoque::query()
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->pluck('name', 'id'))
                    ->multiple()
                    ->preload()
                    ->columnSpanFull(),
                Select::make('plano_habilitado_id')
                    ->label('Plano Habilitado')
                    ->multiple()
                    ->preload()
                    ->options(PlanoHabilitado::query()
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->pluck('name', 'id'))
                    ->columnSpanFull(),
                Select::make('modalidade_venda_id')
                    ->label('Modalidade de Venda')
                    ->multiple()
                    ->preload()
                    ->options(ModalidadeVenda::query()
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->pluck('name', 'id'))
                    ->columnSpanFull(),
                Select::make('campo_valor_id')
                    ->label('Campo de Valor')
                    ->options([
                        'base_faturamento_compra' => 'Base de Faturamento - Compra',
                        'valor_total' => 'Valor Total',
                        'valor_franquia' => 'Valor da Franquia',
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
