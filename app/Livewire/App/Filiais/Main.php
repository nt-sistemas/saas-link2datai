<?php

namespace App\Livewire\App\Filiais;

use App\Models\Filial;
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

    public function changeView()
    {
        //ds($this->item1);
        //$this->item1 = !$this->item1;
        Redis::set(auth()->user()->id . $this->filial_id . '_dashboard_view', $this->item1 === true ? 'true' : 'false');
        $this->item1 = filter_var(Redis::get(auth()->user()->id . $this->filial_id . '_dashboard_view'), FILTER_VALIDATE_BOOLEAN);
        $this->mount($this->filial_id);

        //ds($this->item1);
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
}
