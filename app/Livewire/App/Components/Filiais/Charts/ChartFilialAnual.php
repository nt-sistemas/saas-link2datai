<?php

namespace App\Livewire\App\Components\Filiais\Charts;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class ChartFilialAnual extends Component
{
    public $id;
    public $data = [];

    public function mount($id)
    {
        $this->id = $id;
        $this->data = $this->getData();
    }

    public function render()
    {
        return view('livewire.app.components.filiais.charts.chart-filial-anual');
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
                ->selectRaw("    EXTRACT(YEAR FROM data_pedido)::INTEGER AS year, SUM({$group->campo_valor_id}) AS total")
                ->where('grupo_id', $group->id)
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('filial_id', $this->id)
                ->groupBy('year')
                ->get();

            $data['series'][] = [
                'name' => $group->name,
                'data' => $vendas->pluck('total')->toArray()
            ];


            $data['categories'] = $vendas->pluck('year')->toArray();

        }

        return $data;
    }
}
