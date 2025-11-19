<?php

namespace App\Livewire\Categories;

use App\Livewire\App\Charts\ChartBar;
use App\Models\Grupo;
use App\Models\Meta;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;

#[Lazy]
class Show extends Component
{
    public $id;
    public $group;
    public $lastUpdated;
    public $data_ini;
    public $data_fim;

    public $filial_id = null;
    public $vendedor_id = null;
    public $filiais_multi_ids = [];

    public array $chartPeriodo;
    public array $chartRankingFiliaisValores;
    public array $chartRankingFiliaisQuantidades;
    public array $chartRankingVendedoresValores;
    public array $chartRankingVendedoresQuantidades;

    public $grupo_estoque_ids = [];
    public $modalidade_venda_ids = [];
    public $plano_habilitado_ids = [];


    public function mount()
    {
        $this->data_ini = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->startOfMonth()->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->data_fim = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->endOfMonth()->format('Y-m-d') : Carbon::now()->endOfMonth()->format('Y-m-d');

        $data = Redis::get(auth()->user()->id . '_show_details');
        $this->filiais_multi_ids = is_null($data) ? [] : array_filter(json_decode($data, true)['filial_multi_ids']);
        $this->data_ini = is_null($data) ? $this->data_ini : json_decode($data, true)['dt_inicio'];
        $this->data_fim = is_null($data) ? $this->data_fim : json_decode($data, true)['dt_fim'];

        if ($this->filial_id) {
            $this->filiais_multi_ids = array($this->filial_id);
        } else {
            $this->filiais_multi_ids = is_null($data) ? [] : array_filter(json_decode($data, true)['filial_multi_ids']);
        }


        $this->group = Grupo::find($this->id);

        $this->grupo_estoque_ids = $this->group->grupo_estoque->pluck('id')->toArray();
        $this->modalidade_venda_ids = $this->group->modalidade_venda->pluck('id')->toArray();
        $this->plano_habilitado_ids = $this->group->plano_habilitados->pluck('id')->toArray();


        $this->lastUpdated = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();


    }

    public function render()
    {
        return view('livewire.categories.show');
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

    public function filter()
    {

        $this->dispatch('show-filter-chart-bar', [
            'dt_inicio' => $this->data_ini,
            'dt_fim' => $this->data_fim,
            'filiais_multi_ids' => $this->filiais_multi_ids,
        ]);

    }

    public function hover()
    {
        return 'function(event, chartElement) {
            event.native.target.style.cursor = chartElement[0] ? "pointer" : "default";
        }';
    }

    public function atingimento_meta($meta, $venda)
    {
        $percentual =
            (($venda - $meta) / ($meta == 0 ? 1 : $meta)) * 100;
        return number_format($percentual, 2, '.', '');
    }
}
