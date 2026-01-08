<?php

namespace App\Filament\Resources\Imports\Widgets;

use App\Models\Import;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ImportsDiarioStats extends StatsOverviewWidget
{
    public function getColumns(): int|array
    {
        return [
            'md' => 2,

        ];
    }

    protected function getStats(): array
    {
        $lastDate = Import::where('tenant_id', auth()->user()->tenant_id)->orderBy('data_pedido', 'desc')->first()->data_pedido;
        $totalVendasDia = Import::where('tenant_id', auth()->user()->tenant_id)
            ->where('data_pedido', '>=', Carbon::parse($lastDate)->format('Y-m-d 0:00:00'))
            ->count();

        return [
            Stat::make('Ultima Sincronização', Carbon::parse($lastDate)->format('d/m/Y')),
            Stat::make('Total de Vendas no Dia', $totalVendasDia),

        ];
    }
}
