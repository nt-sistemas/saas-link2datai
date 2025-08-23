<?php

namespace App\Livewire\App\Components;

use App\Models\GrupoEstoque;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class PanelEstoque extends Component
{
    public $data;
    public $date_ini;
    public $date_fim;

    public function mount()
    {
        $this->data = $this->getData();


    }
    public function render()
    {
        return view('livewire.app.components.panel-estoque');
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
        $tiposPedidos = GrupoEstoque::query()->where('tenant_id', auth()->user()->tenant_id)->get();

        $data = [];

        foreach($tiposPedidos as $tipo) {
            $data[] = [
                'tipo' => $tipo,
                'total' => $tipo->vendas()
                    ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
                    ->orderBy('data_pedido','desc')
                    ->sum('valor_total'),
                'quantidade' => $tipo->vendas()
                    ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
                    ->count()
            ];
        }

        return $data;
    }
}
