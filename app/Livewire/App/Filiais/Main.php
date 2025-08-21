<?php

namespace App\Livewire\App\Filiais;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;


#[Lazy]
class Main extends Component
{
    public $filiais = [];

    public function mount()
    {
        $this->filiais = $this->getFiliais();
    }

    public function render()
    {
        return view('livewire.app.components.filiais.main');
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

    #[Computed]
    public function getFiliais()
    {
        return \App\Models\Filial::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->get();
    }
}
