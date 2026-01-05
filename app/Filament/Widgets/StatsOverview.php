<?php

namespace App\Filament\Widgets;

use App\Models\Categoria;
use App\Models\Filial;
use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitado;
use App\Models\Vendedor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Processamento';

    protected ?string $description = 'Uma visão geral do Processamento de Dados para Link2Data Intelligence.';

    protected function getStats(): array
    {
        return [
            Stat::make('Filiais', Filial::query()->where('tenant_id', auth()->user()->tenant_id)->count()),
            Stat::make('Vendedores', Vendedor::query()->where('tenant_id', auth()->user()->tenant_id)->count()),
            Stat::make('Categorias', Categoria::query()->where('tenant_id', auth()->user()->tenant_id)->count()),
            Stat::make('Grupos de Estoque', GrupoEstoque::query()->where('tenant_id', auth()->user()->tenant_id)->count()),
            Stat::make('Planos Habilitação', PlanoHabilitado::query()->where('tenant_id', auth()->user()->tenant_id)->count()),
            Stat::make('Modalidades de Vendas', ModalidadeVenda::query()->where('tenant_id', auth()->user()->tenant_id)->count()),
        ];
    }
}
