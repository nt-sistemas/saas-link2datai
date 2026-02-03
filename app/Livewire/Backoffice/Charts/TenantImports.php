<?php

namespace App\Livewire\Backoffice\Charts;

use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use LarawireGarage\LarapexLivewire\LivewireChartComponent;
use LarawireGarage\LarapexLivewire\Wireable\WireableBarChart;

class TenantImports extends LivewireChartComponent
{
    protected $listeners = [];

    private function dataSource()
    {

        $vendas = Venda::query()
            ->select('tenant_id', DB::raw('COUNT(*) as total_imports'))
            ->groupBy('tenant_id')
            ->get();

        $data = [];
        foreach ($vendas as $venda) {
            $data['label'][] = $venda->tenant->name;
            $data['value'][] = $venda->total_imports;
        }

        // Dataset logic
        return $data;
    }

    public function build()
    {
        $this->chart = (new WireableBarChart($this->chart_id)) // ->id($this->chart_id)
            ->setDataset([
                [
                    'name' => 'Importações',
                    'data' => $this->dataSource()['value'] ?? [],
                ],

            ])
            ->setFill([
                'opacity' => 1.0,
            ])
            ->setColors(['#002855'])
            ->showDataLabels(true)
            ->setXAxis([
                'title' => [
                    'text' => 'Empresas',
                ],
                'categories' => $this->dataSource()['label'] ?? [],
            ]);

        // /**
        //  * using heredoc
        //  */
        // ->jsCallback('tooltip.y.formatter', <<<HTML
        //     function(value,{series,seriesIndex,dataPointIndex,w}){
        //         return value;
        //     }
        // HTML)
        // /**
        //  * using String
        //  */
        // ->jsCallback('dataLabels.formatter', "function (val, opts) {
        //     return val + '$'
        // }")
    }
}
