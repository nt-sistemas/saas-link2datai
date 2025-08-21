<?php

namespace App\Livewire\App\Components\Charts;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class ChartMensal extends Component
{
    public $data = [];

    public function mount()
    {
        $this->data = $this->getData();
    }

    public function render()
    {
        return view('livewire.app.components.charts.chart-mensal');
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
