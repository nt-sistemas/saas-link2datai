<?php

namespace App\Livewire\Admin;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Dashboard extends Component
{
    public function mount()
    {
        sleep(5);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.dashboard');
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
