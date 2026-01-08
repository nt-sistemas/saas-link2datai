<?php

namespace App\Filament\Resources\Imports\Widgets;

use App\Models\Import;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ImportsStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Import::where('tenant_id', auth()->user()->tenant_id)->count();
        $totalPorPlanilha = Import::where('tenant_id', auth()->user()->tenant_id)->where('filename', '!=', '')->where('is_processed', true)->count();
        $totalPorWebService = Import::where('tenant_id', auth()->user()->tenant_id)->where('filename', '')->where('is_processed', true)->count();

        return [
            Stat::make('Total de Imports', $total),
            Stat::make('Total Por Planilha', $totalPorPlanilha),
            Stat::make('Total por WebService', $totalPorWebService),
        ];
    }
}
