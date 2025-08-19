<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Index extends Component
{
    public $headers = [
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'phone', 'label' => 'Telefone'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'active', 'label' => 'Status'],
    ];

    public $search = '';
    public bool $modal_delete = false;
    public $tenant_name;
    public $tenant_id;

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.tenants.index');
    }

    #[Computed()]
    public function tenants(): LengthAwarePaginator
    {
        return Tenant::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function modalDelete($tenant_id)
    {
        $this->modal_delete = true;
        $this->tenant_name = Tenant::find($tenant_id)->name ?? null;
        $this->tenant_id = $tenant_id;
    }

    public function deleteTenant()
    {
        if ($this->tenant_id) {
            Tenant::destroy($this->tenant_id);
            $this->modal_delete = false;
        }
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
