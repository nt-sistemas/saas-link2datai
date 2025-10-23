<?php

namespace App\Livewire\App\Charts;

use App\Models\Grupo;
use App\Models\Venda;
use Carbon\Carbon;
use LarawireGarage\LarapexLivewire\LivewireChartComponent;
use LarawireGarage\LarapexLivewire\Wireable\WireableBarChart;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class ChartBar extends LivewireChartComponent
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

    #[Computed()]
    public function getDataChart()
    {
        $grupo = Grupo::find($this->grupo_id);



        $tipo_grupo_id = $grupo->tipoGrupo->pluck('id')->toArray();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

        $vendas = Venda::query()
            ->selectRaw('filial_id, SUM(' . $grupo->campo_valor_id . ') as total, DATE(data_pedido) as data,Count(id) as quantidade')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->when($this->filiais_multi_ids, function ($query) {
                $query->whereIn('filial_id', $this->filiais_multi_ids);
                //$query->groupBy('filial_id');
            })
            ->when($this->vendedores_multi_ids, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $vendedor_id);
            })
            ->whereBetween('data_pedido', [$this->dt_inicio, $this->dt_fim])
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
            ->orderBy('data', 'asc')
            ->groupBy('data', 'filial_id')
            ->get();

        ds($vendas);

        exit;

        $chart = [];


        foreach ($vendas as $venda) {
            $chart['labels'][] = Carbon::parse($venda->data)->format('d/m') ?? '';
            $chart['data'][] = $venda->total ?? 0;
            $chart['quantidade'][] = $venda->quantidade ?? 0;
        }


        return [
            'valor_total' => [
                'type' => 'bar',

                'options' => [
                    'width' => '100%',
                    'maintainAspectRatio' => false,
                    'responsive' => true,

                ],
                'data' => [
                    'labels' => $chart['labels'],
                    'datasets' => [
                        [
                            'label' => $grupo->name . ' | Total - R$',
                            'data' => $chart['data'],
                            'backgroundColor' => ['#002855'],
                        ],

                    ]
                ]
            ],
            'quantidade_total' => [
                'type' => 'bar',
                'options' => [
                    'width' => '100%',
                    'maintainAspectRatio' => false,
                    'responsive' => true,

                ],
                'data' => [
                    'labels' => $chart['labels'],
                    'datasets' => [
                        [
                            'label' => $grupo->name . ' | Quantidade - Unidades',
                            'data' => $chart['quantidade'],
                            'backgroundColor' => ['#002855'],
                        ],

                    ]
                ]
            ]
        ];
    }
    public function build()
    {


        $this->chart = (new WireableBarChart($this->chart_id)) // ->id($this->chart_id)
            //->addBar('Valor', $this->dataSource())
            ->setDataset([
                [
                    'name' => "Series 1",
                    'data' => [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
                ],
                [
                    'name' => "Series 1",
                    'data' => [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
                ]
            ])
            ->showDataLabels(true)
            ->setFill([
                'opacity' => 1.0
            ])
            ->colors(['#002855', '#feb019'])
            ->setPlotOptions([
                'bar' => [
                    'borderRadius' => 8,
                    'columnWidth' => '70%',
                    'dataLabels' => [
                        'enabled' => true,
                        'orientation' => 'vertical',

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
                return val + '$'
            }");
    }
}
