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

class ChartBarQuantidadeComparar extends LivewireChartComponent
{
    protected $listeners = [];
    public $grupo_id;
    public $mes_inicio;
    public $mes_final;
    public $ano_inicio;
    public $ano_final;
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

    #[On('show-filter-chart-bar-comparar')]
    public function refreshChart($params)
    {

        $this->mes_inicio = $params['mes_inicial'];
        $this->ano_inicio = $params['ano_inicial'];
        $this->mes_final = $params['mes_final'];
        $this->ano_final = $params['ano_final'];

        $this->grupo_id = $params['grupo_id'];
        $this->filiais_multi_ids = $params['filiais_multi_ids'];
        $this->vendedores_multi_ids = $params['vendedores_multi_ids'];

        $this->getDataChart();

        $this->build();
    }

    #[Computed]
    public function getDataChart()
    {
        if (!$this->grupo_id) {
            return [];
        }
        $grupo = Grupo::find($this->grupo_id);


        $tipo_grupo_id = $grupo ? $grupo->tipoGrupo->pluck('id')->toArray() : [];

        $grupo_estoque_ids = $grupo ? $grupo->grupo_estoque->pluck('id')->toArray() : [];
        $modalidade_venda_ids = $grupo ? $grupo->modalidade_venda->pluck('id')->toArray() : [];
        $plano_habilitado_ids = $grupo ? $grupo->plano_habilitados->pluck('id')->toArray() : [];


        $vendas_mes_1 = Venda::query()
            ->selectRaw('count(*) as total, data_pedido as data')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereMonth('data_pedido', $this->mes_inicio)
            ->whereYear('data_pedido', $this->ano_inicio)
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
            ->orderBy('data', 'asc')
            ->groupBy('data')
            ->get();

        $vendas_mes_2 = Venda::query()
            ->selectRaw('count(*) as total, data_pedido as data')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereMonth('data_pedido', $this->mes_final)
            ->whereYear('data_pedido', $this->ano_final)
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
            ->orderBy('data', 'asc')
            ->groupBy('data')
            ->get();


        $chart = [];
        foreach ($vendas_mes_1 as $venda) {
            $chart['labels'][] = Carbon::parse($venda->data)->format('d/m/Y');
            $chart['mes_1'][] = $venda->total ?? 0;

        }

        foreach ($vendas_mes_2 as $venda) {
            $chart['mes_2'][] = $venda->total ?? 0;
        }

        return $chart;
    }

    public function build()
    {

        $this->chart = (new WireableAreaChart($this->chart_id)) // ->id($this->chart_id)
        //->addBar('Valor', $this->dataSource())
        ->setDataset([
            [
                'name' => $this->getLabelName($this->mes_inicio, $this->ano_inicio),
                'data' => $this->getDataChart()['mes_1'] ?? []
            ],
            [
                'name' => $this->getLabelName($this->mes_final, $this->ano_final),
                'data' => $this->getDataChart()['mes_2'] ?? []
            ],

        ])
            ->showDataLabels(true)
            ->setFill([
                'opacity' => 1.0
            ])
            ->colors(['#002855', '#feb019'])
            ->setPlotOptions([
                'bar' => [
                    'borderRadius' => 8,
                    'padding' => 4,
                    'columnWidth' => '70%',
                    'dataLabels' => [
                        'enabled' => true,
                        'orientation' => 'vertical',

                    ],

                ],

            ])
            ->jsCallback('xaxis.labels.formatter', "function (val, index) {
                console.log(val);
                console.log(index);
                return val;
            }")
            ->setYAxis([
                'title' => [
                    'text' => 'Valor em R$',
                ],
            ])
            ->setXAxis([
                'categories' => $this->getDataChart()['labels'] ?? [],
            ]);
    }

    public function getLabelName($mes, $ano): string
    {
        $meses = [
            0 => 'Janeiro',
            1 => 'Fevereiro',
            2 => 'Março',
            3 => 'Abril',
            4 => 'Maio',
            5 => 'Junho',
            6 => 'Julho',
            7 => 'Agosto',
            8 => 'Setembro',
            9 => 'Outubro',
            10 => 'Novembro',
            11 => 'Dezembro',
        ];

        return $meses[((int)$mes - 1)] ?? '' . '/' . $ano;

    }
}
