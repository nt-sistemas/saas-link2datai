<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
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

        if (RateLimiter::tooManyAttempts($this->email, 5)) {
            $this->addError('rateLimiter', trans('auth.throttle', ['seconds' => RateLimiter::availableIn($this->email)]));
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($this->email);
            $this->addError('invalidCredentials', trans('auth.failed'));
            return;
        }

        redirect()->route('app.dashboard');
    }
}