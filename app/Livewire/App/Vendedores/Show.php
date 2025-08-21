<?php

namespace App\Livewire\App\Vendedores;

use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class Show extends Component
{
    public function render()
    {
        return view('livewire.app.vendedores.show');
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
