<?php

namespace App\Livewire\App\GrupoEstoque;

use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class ChartFilial extends Component
{
     public $grupo_estoque;
    public $data;

     public function mount($grupo_estoque)
    {
        $this->grupo_estoque = $grupo_estoque;
    }
    public function render()
    {
        return view('livewire.app.grupo-estoque.chart-filial');
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