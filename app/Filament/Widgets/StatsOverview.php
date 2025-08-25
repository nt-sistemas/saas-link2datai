<?php

namespace App\Filament\Widgets;

use App\Models\Filial;
use App\Models\Vendedor;
use App\Models\Categoria;
use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitado;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
     protected ?string $heading = 'Processamento';

    protected ?string $description = 'Uma visão geral do Processamento de Dados para Link2Data Intelligence.';
  protected function getStats(): array
    {
        return [
            Stat::make('Filiais', Filial::count()),
            Stat::make('Vendedores', Vendedor::count()),
            Stat::make('Categorias', Categoria::count()),
            Stat::make('Grupos de Estoque', GrupoEstoque::count()),
            Stat::make('Planos Habilitação', PlanoHabilitado::count()),
            Stat::make('Modalidades de Vendas', ModalidadeVenda::count()),
        ];
    }
}