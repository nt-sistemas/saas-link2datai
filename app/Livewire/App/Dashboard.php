<?php

namespace App\Livewire\App;


use App\Models\Categoria;
use App\Models\Grupo;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Dashboard extends Component
{
    public $lastUpdated = null;
    public $daysOfData = null;
    public $date_ini;
    public $date_fim;
    public $item1;

    public $selectedTab = 'chart-tab';


    public function mount()
    {
        $this->lastUpdated = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();


        $this->daysOfData = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->diffInDays(Carbon::now()) : 0;

        $this->date_ini = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->startOfMonth()->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->date_fim = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->endOfMonth()->format('Y-m-d') : Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->item1 = filter_var(Redis::get(auth()->user()->id . '_dashboard_view'), FILTER_VALIDATE_BOOLEAN);
    }


    public function render(): \Illuminate\View\View
    {

        return view('livewire.app.dashboard');
    }

    #[Computed]
    public function categories()
    {
        return \App\Models\Categoria::query()
            ->where('active', true)
            ->orderBy('order', 'asc')
            ->get();
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

    public function reorderCategories($data)
    {

        $categoriesIds = [];
        foreach ($data as $row) {
            $categoriesIds[] = $row['value'];
        }

        $categories = Categoria::query()->findMany($categoriesIds)
            ->map(function (Categoria $category) use ($categoriesIds) {
                $category->order = array_flip($categoriesIds)[$category->id];

                return $category;
            });

        Categoria::query()->upsert(
            $categories->toArray(),
            ['id'],
            ['order']
        );
    }

    public function reorderGroups($data)
    {

        foreach ($data as $row) {
            $groupsId = [];
            $categoryId = $row['value'];
            foreach ($row['items'] as $item) {
                $groupsId[] = $item['value'];

                $groups = Grupo::query()->findMany($groupsId)
                    ->map(function (Grupo $grupo) use ($groupsId, $categoryId) {
                        $grupo->order = array_flip($groupsId)[$grupo->id];
                        $grupo->categoria_id = $categoryId;

                        return $grupo;
                    });

                Grupo::query()->upsert(
                    $groups->toArray(),
                    ['id'],
                    ['order', 'categoria_id'],
                );
            }
        }
    }

    public function changeView()
    {
        //ds($this->item1);
        //$this->item1 = !$this->item1;
        Redis::set(auth()->user()->id . '_dashboard_view', $this->item1 === true ? 'true' : 'false');
        $this->item1 = filter_var(Redis::get(auth()->user()->id . '_dashboard_view'), FILTER_VALIDATE_BOOLEAN);
        $this->mount();

        //ds($this->item1);
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
