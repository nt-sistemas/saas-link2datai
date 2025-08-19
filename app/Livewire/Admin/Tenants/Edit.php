<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Lazy]
class Edit extends Component
{
    public $id;

    #[Rule('required|string|max:255')]
    public $name;

    #[Rule('required|string|email|max:255')]
    public $email;
    #[Rule('required|string|max:20')]
    public $phone;

    public $active;

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $tenant = Tenant::findOrFail($this->id);
        $this->name = $tenant->name;
        $this->email = $tenant->email;
        $this->phone = $tenant->phone;
        $this->active = $tenant->active;

        return view('livewire.admin.tenants.edit');
    }

    public function save()
    {
        $this->validate();

        $tenant = Tenant::findOrFail($this->id);
        $tenant->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'active' => $this->active,
        ]);

        session()->flash('message', 'Tenant updated successfully.');

        return redirect()->route('admin.tenants.index');
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
