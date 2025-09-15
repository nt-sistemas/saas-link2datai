<?php

namespace App\Livewire\App\Filiais;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;


#[Lazy]
class Main extends Component
{
    public $lastUpdated = null;
    public $daysOfData = null;
    public $date_ini;
    public $date_fim;
    public $item1 = null;

    public $filial_id;

    public $filial;


    public function mount($id)
    {
        $this->filial_id = $id;
        $this->filial = Filial::find($id);
        $this->lastUpdated = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();


        $this->daysOfData = Carbon::parse($this->lastUpdated->data_pedido)->diffInDays(Carbon::now());

        $this->date_ini = Carbon::parse($this->lastUpdated->data_pedido)->startOfMonth()->format('Y-m-d');
        $this->date_fim = Carbon::parse($this->lastUpdated->data_pedido)->endOfMonth()->format('Y-m-d');
        $this->item1 = filter_var(Redis::get(auth()->user()->id . $this->filial_id . '_dashboard_view'), FILTER_VALIDATE_BOOLEAN);
    }

    public function render()
    {
        return view('livewire.app.components.filiais.main');
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

    #[Computed]
    public function categories()
    {
        return \App\Models\Categoria::query()
            ->where('active', true)
            ->orderBy('order', 'asc')
            ->get();
    }



    public function getVendedores()
    {
        return Venda::query()
            ->select('vendedor_id')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
            ->where('filial_id', $this->filial_id)
            ->groupBy('vendedor_id')
            ->get();
    }

    #[Computed]
    public function getFiliais()
    {
        return \App\Models\Filial::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('code', 'asc')
            ->get();
    }

    #[Computed]
    public function totalCategoria($categoryId)
    {

        $grupos = Grupo::query()->where('categoria_id', $categoryId)->get();


        $total = 0;
        foreach ($grupos as $grupo) {
            $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
            $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
            $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

            $total += Venda::query()
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('tipo_grupo_id', $grupo->tipo_grupo_id)
                ->where('filial_id', $this->filial_id)
                ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                    $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
                })
                ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                    $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
                })
                ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                    $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
                })
                ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
                ->sum($grupo->campo_valor_id);
        }
        ds($total);

        return $total;
    }

    #[Computed]
    public function quantidadeCategoria($categoryId)
    {

        $grupos = Grupo::query()->where('categoria_id', $categoryId)->get();


        $quantidade = 0;
        foreach ($grupos as $grupo) {
            $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
            $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
            $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

            $quantidade += Venda::query()
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('tipo_grupo_id', $grupo->tipo_grupo_id)
                ->where('filial_id', $this->filial_id)
                ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                    $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
                })
                ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                    $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
                })
                ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                    $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
                })
                ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
                ->count();
        }

        return $quantidade;
    }
}
