<?php

namespace App\Livewire\App\Vendedores;

use App\Models\Venda;
use Carbon\Carbon;
use Livewire\Attributes\Lazy;
use Livewire\Component;


#[Lazy]
class Main extends Component
{
    public $vendedores = [];
    public $lastMonthUpdate;

    public function mount()
    {
        $this->vendedores = $this->getVendedores();
        $this->rankVendedoresAsc();

        $vendas = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();

        $this->lastMonthUpdate = Carbon::parse($vendas->data_pedido)->format('m');

    }

    public function render()
    {
        return view('livewire.app.components.vendedores.main');
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

    public function getVendedores()
    {
        return \App\Models\Vendedor::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->get();
    }

    public function rankVendedoresAsc()
    {
        $vendedores = \App\Models\Vendedor::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->get();

        $resp = [];

        foreach ($vendedores as $vendedor) {
            $resp[] = [
                'name' => $vendedor->name,
                'total_vendas' => $vendedor->vendas()->whereMonth('data_pedido', $this->lastMonthUpdate)->sum('valor_total'),
            ];

        }

        $sorted = collect($resp)->sortBy('total_vendas')->values()->slice(0, 10)->toArray();

        ds($sorted);


    }
}
