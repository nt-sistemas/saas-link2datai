<?php

namespace App\Livewire\Backoffice\Usuarios;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Index extends Component
{
    public $headers = [
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'tenant.name', 'label' => 'Empresa'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'active', 'label' => 'Status'],
    ];

    public $search = '';

    public bool $modal_delete = false;

    public $usuario_name;

    public $usuario_id;

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.backoffice.usuarios.index');
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

    #[Computed()]
    public function usuarios()
    {
        return User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function modalDelete($usuario_id)
    {
        $this->modal_delete = true;
        $this->usuario_name = User::find($usuario_id)->name ?? null;
        $this->usuario_id = $usuario_id;
    }

    public function deleteUsuario()
    {
        if ($this->usuario_id) {
            User::destroy($this->usuario_id);
            $this->modal_delete = false;
        }
    }
}
