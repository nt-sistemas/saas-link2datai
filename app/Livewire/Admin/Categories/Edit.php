<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Categoria;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Mary\Traits\Toast;


#[Lazy]
class Edit extends Component
{
    use Toast;

    public $id;

    #[Rule('string|max:255')]
    public $name;

    #[Rule('string|max:255')]
    public $description;
   
    public $order;

    public bool $active = true;

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $category = Categoria::findOrFail($this->id);
        $this->name = $category->name;
        $this->description = $category->description;
        $this->order = $category->order;
        $this->active = $category->active;

        return view('livewire.admin.categories.edit');
    }

    public function save()
    {
        $this->validate();

        $tenant = Categoria::findOrFail($this->id);
        $tenant->update([
            'name' => $this->name,
            'description' => $this->description,
            'order' => $this->order,
            'active' => $this->active,
        ]);

        session()->flash('message', 'Categoria Atualizada com Sucesso');

        return redirect()->route('admin.categories.index');
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
