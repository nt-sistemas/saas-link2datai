<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Categoria;
use App\Models\Tenant;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\WithPagination;


#[Lazy]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category_name;
    public $category_id;
    public bool $modal_delete = false;

    public $headers = [
        ['key' => 'name', 'label' => 'Categoria'],
        ['key' => 'description', 'label' => 'Descrição'],
        ['key' => 'order', 'label' => 'Ordem'],
        ['key' => 'active', 'label' => 'Status'],
    ];

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.categories.index');
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
    public function categories(): LengthAwarePaginator
    {
        return \App\Models\Categoria::query()
            ->when($this->search, function ($query) {
                $query->whereRaw('LOWER("name") LIKE ?', ['%' . Str::lower(trim($this->search)) . '%'])
                    ->orWhereRaw('LOWER("description") LIKE ?', ['%' . Str::lower(trim($this->search)) . '%']);
            })
            ->orderBy('order', 'asc')
            ->paginate(10);
    }

    public function modalDelete($id): void
    {
        $this->modal_delete = true;
        $this->category_name = Categoria::find($id)->name ?? null;
        $this->category_id = $id;
    }

    public function delete(): void
    {
        if ($this->category_id) {
            Categoria::destroy($this->category_id);
            $this->modal_delete = false;
        }
    }
}
