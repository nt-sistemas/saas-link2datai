<?php

namespace App\Livewire\Backoffice\Usuarios;

use App\Models\User;
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

    public $password;

    #[Rule('same:password')]
    public $password_confirmation;

    public $tenant_id;

    public $is_active;

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $usuario = User::findOrFail($this->id);
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->tenant_id = $usuario->tenant_id;
        $this->is_active = $usuario->is_active;

        return view('livewire.backoffice.usuarios.edit');
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

        $usuario = User::findOrFail($this->id);
        $usuario->update([
            'name' => $this->name,
            'email' => $this->email,
            'tenant_id' => $this->tenant_id,
            'is_active' => $this->is_active,
            'password' => $this->password ? bcrypt($this->password) : $usuario->password,
        ]);

        session()->flash('message', 'Usuario updated successfully.');

        return redirect()->route('backoffice.usuarios.index');
    }
}
