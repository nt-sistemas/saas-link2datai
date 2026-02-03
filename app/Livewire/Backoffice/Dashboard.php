<?php

namespace App\Livewire\Backoffice;

use App\Models\Filial;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Venda;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Dashboard extends Component
{
    public $tenantsCount;

    public $salesCount;

    public $usersCount;

    public function mount()
    {
        $this->tenantsCount = Tenant::count();
        $this->salesCount = Venda::count();
        $this->usersCount = User::count();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.backoffice.dashboard');
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

    public function lastFiliais()
    {
        return Filial::query()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function lastUsers()
    {
        return User::query()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
}
