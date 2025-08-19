<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Categoria;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Mary\Traits\Toast;


#[Lazy]
class Create extends Component
{
    use Toast;

    #[Rule('required|string|max:255')]
    public $name;

    #[Rule('required|string|max:255')]
    public $description;
    #[Rule('required|string|max:20')]
    public $order;

    public bool $active = true;

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.categories.create');
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

    public function save()
    {
        $this->validate();

        // Simulating a long-running process
        $tenant = Tenant::query()->first();
        $tenant->categories()->create([
            'name' => $this->name,
            'description' => $this->description,
            'order' => $this->order,
            'active' => $this->active,
        ]);


        $this->toast(
            type: 'success',
            title: 'Categoria criada com sucesso', // title
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );

        return redirect()->route('admin.categories.index');
    }
}
