<?php

namespace App\Livewire\App\Charts;

use App\Models\Grupo;
use App\Models\Meta;
use App\Models\Venda;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class Totalizador extends Component
{

    public $grupo_id;
    public $dt_inicio;
    public $dt_fim;

    public $totalValor = 0;

    public $lastUpdated = null;

    public $cardValor = [];
    public $cardQuant = [];
    public $filial_id = null;
    public $vendedor_id = null;


    public function mount()
    {
        ds($this->filial_id);
        $this->lastUpdated = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();

        $date = $this->lastUpdated->data_pedido;
        $this->dt_inicio = Carbon::parse($date)->startOfMonth()->format('Y-m-d');
        $this->dt_fim = Carbon::parse($date)->endOfMonth()->format('Y-m-d');
        $this->cardValor = $this->getDataValor();
        $this->cardQuant = $this->getDataQuant();
    }

    public function render()
    {

        return view('livewire.app.charts.totalizador');
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="flex items-center justify-center">
                    <x-loading class="loading-bars text-primary" />
                </div>
            HTML;
    }

    public function getDataValor(): array
    {
        $grupo = Grupo::find($this->grupo_id);

        $tipo_grupo_id = $grupo->tipoGrupo()->pluck('id')->first();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();
        $vendas = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $this->vendedor_id);
            })
            ->when($tipo_grupo_id, function ($query) use ($tipo_grupo_id) {
                $query->where('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->whereBetween('data_pedido', [$this->dt_inicio, $this->dt_fim])
            ->sum($grupo->campo_valor_id);

        $metas = Meta::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('grupo_id', $grupo->id)
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $vendedor_id);
            })
            ->whereBetween('mes', [Carbon::parse($this->dt_inicio)->month, Carbon::parse($this->dt_fim)->month])
            ->whereBetween('ano', [Carbon::parse($this->dt_inicio)->year, Carbon::parse($this->dt_fim)->year])
            ->sum('valor_meta');


        return [
            "total" => $vendas,
            "meta" => $metas,
            "chart" => [
                'type' => 'bar',
                'options' => [
                    'width' => '100%',
                    'responsive' => true,
                ],
                'data' => [
                    'labels' => ['Total', 'Meta'],
                    'datasets' => [
                        [
                            'label' => 'Vendas',
                            'data' => [$vendas, $metas],
                            'backgroundColor' => ['#002855', '#F9C408'],

                            'borderWidth' => 1
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getDataQuant(): array
    {
        $grupo = Grupo::find($this->grupo_id);

        $tipo_grupo_id = $grupo->tipoGrupo()->pluck('id')->first();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();
        $vendas = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $this->filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $this->vendedor_id);
            })
            ->when($tipo_grupo_id, function ($query) use ($tipo_grupo_id) {
                $query->where('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->whereBetween('data_pedido', [$this->dt_inicio, $this->dt_fim])
            ->count();

        $metas = Meta::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('grupo_id', $grupo->id)
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $vendedor_id);
            })
            ->whereBetween('mes', [Carbon::parse($this->dt_inicio)->month, Carbon::parse($this->dt_fim)->month])
            ->whereBetween('ano', [Carbon::parse($this->dt_inicio)->year, Carbon::parse($this->dt_fim)->year])
            ->sum('quantidade');


        return [
            "total" => $vendas,
            "meta" => $metas,
            "chart" => [
                'type' => 'bar',
                'options' => [
                    'width' => '100%',
                    'responsive' => true,
                ],
                'data' => [
                    'labels' => ['Total', 'Meta'],
                    'datasets' => [
                        [
                            'label' => 'Vendas',
                            'data' => [$vendas, $metas],
                            'backgroundColor' => ['#002855', '#F9C408'],

                            'borderWidth' => 1
                        ]
                    ]
                ]
            ]
        ];
    }
}
