<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Lazy]
class Create extends Component
{
    use Toast, WithPagination;

    #[Rule('required|string|max:255')]
    public $name;

    #[Rule('required|string|email|max:255')]
    public $email;
    #[Rule('required|string|max:20')]
    public $phone;

    public bool $active = true;


    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.tenants.create');
    }

    public function save()
    {
        $this->validate();

        // Simulating a long-running process

        Tenant::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'slug' => Str::slug($this->name),
            'active' => $this->active,
        ]);

        $this->toast(
            type: 'success',
            title: 'Empresa criada com sucesso', // title
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );

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
