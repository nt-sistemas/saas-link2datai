<?php

namespace App\Filament\Widgets;

use App\Models\Import;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ImportsChart extends ChartWidget
{
    protected ?string $heading = 'Progresso da Importação Diário';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $lastDateImports = Import::where('tenant_id', auth()->user()->tenant_id)->orderBy('data_pedido', 'desc')->first()->data_pedido;

        $imports = Import::where('tenant_id', auth()->user()->tenant_id)
            ->whereBetween('data_pedido', [Carbon::parse($lastDateImports)->startOfMonth()->format('Y-m-d'), Carbon::parse($lastDateImports)->endOfMonth()->format('Y-m-d')])
            ->get()
            ->groupBy(function ($import) {

                return Carbon::parse($import['data_pedido'])->format('d-m');
            })
            ->map(function ($imports) {
                return $imports->count();
            });

        return [
            'datasets' => [
                [
                    'label' => 'Importações Diárias',
                    'data' => $imports->values()->toArray(),
                ],
            ],
            'labels' => $imports->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
