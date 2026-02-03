<?php

namespace App\Livewire\App\Charts;

use App\Models\Grupo;
use App\Models\Venda;
use Carbon\Carbon;
use LarawireGarage\LarapexLivewire\LivewireChartComponent;
use LarawireGarage\LarapexLivewire\Wireable\WireableBarChart;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class ChartBarQuantidadeMensal extends LivewireChartComponent
{
    protected $listeners = [];

    public $grupo_id;

    public $dt_inicio;

    public $dt_fim;

    public $filiais_multi_ids = [];

    public $vendedores_multi_ids = [];

    public function mount()
    {

        $this->getDataChart();
    }

    private function dataSource()
    {

        // Dataset logic
        return array_map(fn ($value) => [$value, rand(1000, 10000)], range(1, 20));
    }

    #[On('show-filter-chart-bar')]
    public function refreshChart($params)
    {

        $this->dt_inicio = $params['dt_inicio'];
        $this->dt_fim = $params['dt_fim'];
        $this->filiais_multi_ids = $params['filiais_multi_ids'] ?? [];
        $this->vendedores_multi_ids = $params['vendedores_multi_ids'] ?? [];

        $this->build();
    }

    #[Computed()]
    public function getDataChart()
    {
        $grupo = Grupo::find($this->grupo_id);

        $tipo_grupo_id = $grupo->tipoGrupo->pluck('id')->toArray();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

        $vendas = Venda::query()
            ->selectRaw('SUM('.$grupo->campo_valor_id.") as total, DATE_TRUNC('month',data_pedido) as month,Count(id) as quantidade")
            ->where('tenant_id', auth()->user()->tenant_id)
            ->when($this->filiais_multi_ids, function ($query) {
                $query->whereIn('filial_id', $this->filiais_multi_ids);
            })
            ->when($this->vendedores_multi_ids, function ($query) {
                $query->whereIn('vendedor_id', $this->vendedores_multi_ids);
            })
            ->when($tipo_grupo_id, function ($query) use ($tipo_grupo_id) {
                $query->whereIn('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->orderBy('month', 'asc')
            ->groupBy('month')
            ->get();

        $chart = [];

        foreach ($vendas as $venda) {
            $chart['labels'][] = Carbon::parse($venda->month)->format('m/Y');
            $chart['data'][] = $venda->total ?? 0;
            $chart['quantidade'][] = $venda->quantidade ?? 0;
        }

        return $chart;
    }

    public function build()
    {

        $this->chart = (new WireableBarChart($this->chart_id)) // ->id($this->chart_id)
        // ->addBar('Valor', $this->dataSource())
            ->setDataset([
                [
                    'name' => 'Valores Diários',
                    'labels' => $this->getDataChart()['labels'] ?? [],
                    'data' => $this->getDataChart()['quantidade'] ?? [],
                ],

            ])
            ->showDataLabels(true)
            ->setFill([
                'opacity' => 1.0,
            ])
            ->colors(['#feb019'])
            ->setPlotOptions([
                'bar' => [
                    'borderRadius' => 4,
                    'columnWidth' => '70%',
                    'dataLabels' => [
                        'enabled' => true,
                        'position' => 'top',
                        // 'orientation' => 'vertical',

                    ],

                ],

            ])
            // ->randomColors()
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
            ->jsCallback('dataLabels.formatter', 'function (val, opts) {
                return val;
            }')
            ->setYAxis([
                'title' => [
                    'text' => 'Quantidade',
                ],
            ])
            ->setXAxis([
                'categories' => $this->getDataChart()['labels'] ?? [],
            ]);
    }
}
