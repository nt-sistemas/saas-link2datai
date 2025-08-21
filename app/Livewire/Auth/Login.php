<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public ?string $email = '';


    public ?string $password = '';

    #[Layout('components.layouts.auth')]
    public function render(): View
    {
        return view('livewire.auth.login');
    }

    public function login(): void
    {
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', __('auth.failed'));
        }

        redirect()->route('app.dashboard');
    }
}
