<?php

namespace App\Livewire\App\Charts;

use App\Models\Grupo;
use App\Models\Venda;
use Carbon\Carbon;
use LarawireGarage\LarapexLivewire\LivewireChartComponent;
use LarawireGarage\LarapexLivewire\Wireable\WireableDonutChart;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class ChartDonutModalidadeVendaValor extends LivewireChartComponent
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
        return array_map(fn($value) => rand(1, 100), range(1, 5));
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
            ->selectRaw('SUM(' . $grupo->campo_valor_id . ') as total, modalidade_venda_id')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->when($this->filiais_multi_ids, function ($query) {
                $query->whereIn('filial_id', $this->filiais_multi_ids);
            })
            ->when($this->vendedores_multi_ids, function ($query) {
                $query->whereIn('vendedor_id', $this->vendedores_multi_ids);
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
            ->orderBy('modalidade_venda_id', 'desc')
            ->groupBy('modalidade_venda_id')
            ->limit(10)
            ->with('modalidadeVenda')
            ->get();


        $chart = [];


        foreach ($vendas as $venda) {
            $chart['labels'][] = $venda->modalidadeVenda->name;
            $chart['data'][] = floatval($venda->total) ?? 0;
        }


        return $chart;
    }

    public function build()
    {
        $this->chart = (new WireableDonutChart($this->chart_id)) // ->id($this->chart_id)
        ->addPieces($this->getDataChart()['data'])
            ->setLabels($this->getDataChart()['labels'])
            ->colors(['#1E3A8A', '#2563EB', '#3B82F6', '#60A5FA', '#93C5FD', '#BFDBFE', '#E0E7FF', '#C7D2FE', '#A5B4FC', '#818CF8'])
            ->setPlotOptions([
                'pie' => [
                    'height' => '100%',
                    'donut' => [
                        'labels' => [
                            'show' => true,
                            'name' => [
                                'show' => true,
                                'fontSize' => '12px',
                                'color' => '#263544',
                                'offsetY' => -10,
                            ],
                            'value' => [
                                'show' => true,
                                'fontSize' => '16px',
                                'color' => '#263544',
                                'offsetY' => 16,
                                'formatter' => "function(val){
                                    return val;
                                }", // Will be set via jsCallback
                            ],
                            'total' => [
                                'show' => true,
                                'label' => 'Total',
                            ],

                        ],
                    ],
                ]
            ])
            ->jsCallback('plotOptions.pie.donut.labels.total.formatter', "
                function (w) {
                                    return w.globals.seriesTotals.reduce( (a, b) => a + b , 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

                }"
            )
            ->jsCallback('labels.value.formatter', "
                function (val, opts) {
               return val.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }"
            )
            ->jsCallback('tooltip.y.formatter', "
                function (val, opts) {
                    return val.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }"
            )
            // /**
            //  * using String
            //  */
            // ->jsCallback('dataLabels.formatter', "function (val, opts) {
            //     return val + '$'
            // }")
        ;
    }
}
