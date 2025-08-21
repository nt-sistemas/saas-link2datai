<?php

namespace App\Livewire\App\Components\Filiais\Charts;

use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class ChartFilialMensal extends Component
{
    public $id;
    public $data = [];
    public $lastMountUpdated = null;

    public function mount($id)
    {
        $this->id = $id;
        $this->data = $this->getData();
        $lastVenda = \App\Models\Venda::query()
            ->where('filial_id', $this->id)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();
        $this->lastMountUpdated = Carbon::parse($lastVenda->data_pedido)->format('m');

    }

    public function render()
    {
        return view('livewire.app.components.filiais.charts.chart-filial-mensal');
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="flex items-center justify-center">
                    <x-loading class="loading-bars text-primary" />
                </div>
            HTML;

    }

    #[Computed]
    public function getData()
    {
        $groups = \App\Models\Grupo::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->get();

        $data = [];

        foreach ($groups as $group) {

            $vendas = \App\Models\Venda::query()
                ->selectRaw("data_pedido, SUM({$group->campo_valor_id}) AS total")
                ->where('grupo_id', $group->id)
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('filial_id', $this->id)
                ->whereMonth('data_pedido', '03')
                ->groupBy('data_pedido')
                ->get();


            $data['series'][] = [
                'name' => $group->name,
                'data' => $vendas->pluck('total')->toArray()
            ];


            $data['categories'] = $vendas->pluck('data_pedido')->toArray();

        }
        ds($data);
        return $data;
    }
}
