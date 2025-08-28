<?php

namespace App\Livewire\App\Charts;

use Livewire\Attributes\Lazy;
use Livewire\Component;


#[Lazy]
class Totalizadores extends Component
{
    public function render()
    {
        return view('livewire.app.charts.totalizadores');
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
