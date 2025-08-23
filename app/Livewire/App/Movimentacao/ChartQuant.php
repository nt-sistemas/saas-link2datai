<?php

namespace App\Livewire\App\Movimentacao;

use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class ChartQuant extends Component
{
    public $tipo_grupo;
    public $data;

    public function mount($tipo_grupo)
    {
        $this->tipo_grupo = $tipo_grupo;
    }

    public function render()
    {
        return view('livewire.app.movimentacao.chart-quant');
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
}