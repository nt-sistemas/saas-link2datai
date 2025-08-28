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


        $this->daysOfData = Carbon::parse($this->lastUpdated->data_pedido)->diffInDays(Carbon::now());

        $this->date_ini = Carbon::parse($this->lastUpdated->data_pedido)->startOfMonth()->format('Y-m-d');
        $this->date_fim = Carbon::parse($this->lastUpdated->data_pedido)->endOfMonth()->format('Y-m-d');
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
}
