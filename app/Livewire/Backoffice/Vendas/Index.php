<?php

namespace App\Livewire\Backoffice\Vendas;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Index extends Component
{
    public $tenants;

    public function mount()
    {
        $this->tenants = \App\Models\Tenant::all();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.backoffice.vendas.index');
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

    public function goToTenant($tenantId)
    {
        $tenant = \App\Models\Tenant::find($tenantId);
        $user = \App\Models\User::find(auth()->user()->id);

        $user->tenant_id = $tenant->id;
        $user->save();

        return redirect()->route('app.dashboard');
    }
}
