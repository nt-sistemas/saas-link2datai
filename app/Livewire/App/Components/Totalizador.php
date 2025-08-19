<?php

namespace App\Livewire\App\Components;

use App\Models\Grupo;
use App\Models\Venda;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class Totalizador extends Component
{
    public $grupo_id;
    public $dt_inicio;
    public $dt_fim;

    public $total = 0;

    public $lastUpdated = null;

    public function mount()
    {
        $this->lastUpdated = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();

        $date = $this->lastUpdated->data_pedido;
        $this->dt_inicio = Carbon::parse($date)->startOfMonth()->format('Y-m-d');
        $this->dt_fim = Carbon::parse($date)->endOfMonth()->format('Y-m-d');

    }

    public function render()
    {
        $grupo = Grupo::find($this->grupo_id);

        $this->total = $grupo->vendas()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereBetween('data_pedido', [$this->dt_inicio, $this->dt_fim])
            ->sum($grupo->campo_valor_id);

        return view('livewire.app.components.totalizador');
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="flex items-center justify-center">
                    <x-loading class="loading-bars text-primary" />
                </div>
            HTML;

    }
}
