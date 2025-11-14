<?php

namespace App\Livewire\App\Charts;

use App\Models\Grupo;
use App\Models\Meta;
use App\Models\Venda;
use Carbon\Carbon;
use LarawireGarage\LarapexLivewire\LivewireChartComponent;
use LarawireGarage\LarapexLivewire\Wireable\WireableAreaChart;
use LarawireGarage\LarapexLivewire\Wireable\WireableBarChart;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class ChartRankingFiliaisAtingimento extends LivewireChartComponent
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
        return array_map(fn($value) => [$value, rand(1000, 10000)], range(1, 20));
    }

    #[On('show-filter-chart-bar')]
    public function refreshChart($params)
    {

        $this->dt_inicio = $params['dt_inicio'];
        $this->dt_fim = $params['dt_fim'];
        $this->filiais_multi_ids = $params['filiais_multi_ids'];

        $this->build();
    }

    #[Computed]
    public function getDataChart()
    {
        $grupo = Grupo::find($this->grupo_id);


        $tipo_grupo_id = $grupo->tipoGrupo->pluck('id')->toArray();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

        $vendas = Venda::query()
            ->selectRaw('SUM(' . $grupo->campo_valor_id . ') as total, filial_id,Count(id) as quantidade')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereBetween('data_pedido', [$this->dt_inicio, $this->dt_fim])
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
            ->orderBy('total', 'desc')
            ->groupBy('filial_id')
            ->with('filial')
            ->get();

        $chart = [];


        $collect = collect();

        foreach ($vendas as $venda) {
            $metas = Meta::query()
                ->selectRaw('SUM(valor_meta) as meta_valor, SUM(quantidade) as meta_quantidade')
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('filial_id', $venda->filial_id)
                ->where('grupo_id', $grupo->id)
                ->whereBetween('mes', [Carbon::parse($this->dt_inicio)->month, Carbon::parse($this->dt_fim)->month])
                ->whereBetween('ano', [Carbon::parse($this->dt_inicio)->year, Carbon::parse($this->dt_fim)->year])
                ->get();

            $atingimento_valor = $this->atingimento_meta($metas->first()->meta_valor ?? 0, $venda->total);
            $atingimento_quantidade = $this->atingimento_meta($metas->first()->meta_quantidade ?? 0, $venda->quantidade);
            if ($atingimento_valor != 0) {
                $collect->push([
                    'filial' => $venda->filial->name,
                    'total' => $venda->total,
                    'quantidade' => $venda->quantidade,
                    'meta_valor' => $metas->first()->meta_valor ?? 0,
                    'meta_quantidade' => $metas->first()->meta_quantidade ?? 0,
                    'atingimento_valor' => $atingimento_valor,
                    'atingimento_quantidade' => $atingimento_quantidade,
                ]);
            }
        }

        foreach ($collect->sortByDesc('atingimento_valor')->slice(0, 10) as $item) {
            $chart['labels'][] = $item['filial'];
            $chart['data'][] = $item['total'];
            $chart['quantidade'][] = $item['quantidade'];
            $chart['metas_valor'][] = $item['meta_valor'];
            $chart['metas_quantidade'][] = $item['meta_quantidade'];
            $chart['atingimento_valor'][] = $item['atingimento_valor'];
            $chart['atingimento_quantidade'][] = $item['atingimento_quantidade'];
        }


        return $chart;
    }

    public function build()
    {

        $this->chart = (new WireableBarChart($this->chart_id)) // ->id($this->chart_id)
        //->addBar('Valor', $this->dataSource())
        ->setDataset([
            [
                'name' => "Valores",
                //'labels' => $this->getDataChart()['labels'] ?? [],
                'data' => $this->getDataChart()['atingimento_valor'] ?? []
            ],

        ])
            ->showDataLabels(true)
            ->setFill([
                'opacity' => 1.0
            ])
            ->colors(['#002855', '#feb019'])
            ->setPlotOptions([
                'bar' => [
                    'borderRadius' => 4,
                    'columnWidth' => '90%',
                    'barHeight' => '90%',
                    'horizontal' => true,
                    'dataLabels' => [
                        'enabled' => true,
                        //'orientation' => 'vertical',

                    ],

                ],

            ])
            //->randomColors()
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
            ->jsCallback('dataLabels.formatter', "function (val, opts) {

                return Math.abs(Math.round(val)) + '%';
            }")
            ->jsCallback('xaxis.labels.formatter', "function (val, index) {
                console.log(val);
                console.log(index);
                return val;
            }")
            ->setYAxis([])
            ->setXAxis([

                'categories' => $this->getDataChart()['labels'] ?? [],
            ]);
    }

    public function atingimento_meta($meta, $venda)
    {
        if ($meta == 0) {
            return 0;
        }
        $percentual =
            ($venda / $meta) * 100;
        return number_format($percentual, 2, '.', '');
    }
}
