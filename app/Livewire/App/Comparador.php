<?php

namespace App\Livewire\App;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\Venda;
use App\Models\Vendedor;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Mary\Traits\Toast;


#[Lazy]
class Comparador extends Component
{
    use Toast;

    public $mes_inicial;
    public $ano_inicial;
    public $ano_final;
    public $mes_final;

    public $groups;
    public $grupo_id = null;
    public $group_name = null;
    public $filiais;
    public $vendedores;


    public $filiais_multi_ids = [];
    public $vendedores_multi_ids = [];
    public $mes_1;
    public $ano_1;
    public $mes_2;
    public $ano_2;

    public $total_1;
    public $total_2;
    public $quantidade_1;
    public $quantidade_2;

    public $meses = [
        [
            'id' => 1,
            'name' => 'Janeiro',
        ],
        [
            'id' => 2,
            'name' => 'Fevereiro',
        ],
        [
            'id' => 3,
            'name' => 'Março',
        ],
        [
            'id' => 4,
            'name' => 'Abril',
        ],
        [
            'id' => 5,
            'name' => 'Maio',
        ],
        [
            'id' => 6,
            'name' => 'Junho',
        ],
        [
            'id' => 7,
            'name' => 'Julho',
        ],
        [
            'id' => 8,
            'name' => 'Agosto',
        ],
        [
            'id' => 9,
            'name' => 'Setembro',
        ],
        [
            'id' => 10,
            'name' => 'Outubro',
        ],
        [
            'id' => 11,
            'name' => 'Novembro',
        ],
        [
            'id' => 12,
            'name' => 'Dezembro',
        ]
    ];

    public function mount()
    {
        $this->mes_inicial = date('m');
        $this->ano_inicial = date('Y');
        $this->mes_final = date('m');
        $this->ano_final = date('Y');

        $this->getGrupos();
        $this->getFiliais();
        $this->getVendedores();

    }

    public function render()
    {
        return view('livewire.app.comparador');
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="flex items-center justify-center h-screen">
                    <div class="p-4  animate-pulse max-w-sm w-full mx-auto">
                        <div>
                            <img src="{{asset('/assets/loading.svg')}}" alt="loading"/>

                        </div>
                    </div>
                </div>
            HTML;

    }

    public function getGrupos()
    {
        return $this->groups = Grupo::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getFiliais()
    {
        return $this->filiais = Filial::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getVendedores()
    {
        return $this->vendedores = Vendedor::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('name', 'asc')
            ->get();

    }

    public function comparar()
    {
        if ($this->grupo_id === null) {
            $this->error(
                title: 'Você precisa Seleconar um Grupo de Venda para Comparar',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-info',                  // Optional (daisyUI classes)
                timeout: 3000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
            return;
        }
        $this->group_name = Grupo::find($this->grupo_id)->name;
        $data = ['mes_inicial' => $this->mes_inicial,
            'ano_inicial' => $this->ano_inicial,
            'mes_final' => $this->mes_final,
            'ano_final' => $this->ano_final,
            'grupo_id' => $this->grupo_id,
            'filiais_multi_ids' => $this->filiais_multi_ids,
            'vendedores_multi_ids' => $this->vendedores_multi_ids,];

        $this->total_1 = $this->getTotal($this->mes_inicial, $this->ano_inicial)->first()->total ?? 0;
        $this->quantidade_1 = $this->getTotal($this->mes_inicial, $this->ano_inicial)->first()->quantidade ?? 0;
     
        $this->total_2 = $this->getTotal($this->mes_final, $this->ano_final)->first()->total ?? 0;
        $this->quantidade_2 = $this->getTotal($this->mes_final, $this->ano_final)->first()->quantidade ?? 0;
        $this->mes_1 = $this->getDataMes($this->mes_inicial, $this->ano_inicial);
        $this->mes_2 = $this->getDataMes($this->mes_final, $this->ano_final);

        $this->dispatch('show-filter-chart-bar-comparar', $data);


    }

    public function getTotal($mes, $ano)
    {
        if (!$this->grupo_id) {
            return [];
        }
        $grupo = Grupo::find($this->grupo_id);


        $tipo_grupo_id = $grupo ? $grupo->tipoGrupo->pluck('id')->toArray() : [];

        $grupo_estoque_ids = $grupo ? $grupo->grupo_estoque->pluck('id')->toArray() : [];
        $modalidade_venda_ids = $grupo ? $grupo->modalidade_venda->pluck('id')->toArray() : [];
        $plano_habilitado_ids = $grupo ? $grupo->plano_habilitados->pluck('id')->toArray() : [];


        $total = Venda::query()
            ->selectRaw('SUM(' . $grupo->campo_valor_id . ') as total, count(*) as quantidade')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereMonth('data_pedido', $mes)
            ->whereYear('data_pedido', $ano)
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
            ->get();

        return $total;
    }

    public function getDataMes($mes, $ano)
    {
        $meses = collect($this->meses);
        $mes_name = $meses->where('id', $mes)->first()['name'] ?? '';
        return $mes_name . ' de ' . $ano;
    }
}
