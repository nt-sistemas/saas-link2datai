<?php

namespace App\Livewire\App\Filiais;

use App\Models\Filial;
use Carbon\Carbon;
use Livewire\Attributes\Lazy;
use Livewire\Component;


#[Lazy]
class Show extends Component
{
    public $id;
    public $filial;
    public $vendedores = [];

    public $lastMountUpdated = null;

    public function mount($id)
    {

        $this->id = $id;
        $this->filial = Filial::find($id);
        $this->vendedores = $this->getVendedores();

        $lastVenda = \App\Models\Venda::query()
            ->where('filial_id', $this->id)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();
        $this->lastMountUpdated = Carbon::parse($lastVenda->data_pedido)->format('m');


    }

    public function render()
    {


        return view('livewire.app.components.filiais.show');
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
        $vendedores = [];
        $vendas = \App\Models\Venda::query()
            ->select('vendedor_id', \DB::raw('SUM(valor_total) as total_vendas'))
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('filial_id', $this->id)
            ->whereMonth('data_pedido', date('m'))
            ->groupBy('vendedor_id')
            ->get();

        foreach ($vendas as $vendas) {
            $vendedores[] = [
                'vendedor' => \App\Models\Vendedor::find($vendas->vendedor_id),
                'total_vendas' => $vendas->total_vendas,
            ];
        }
        $sortedVendedores = collect($vendedores)->sortByDesc('total_vendas')->values()->toArray();

        return $sortedVendedores;
    }
}
