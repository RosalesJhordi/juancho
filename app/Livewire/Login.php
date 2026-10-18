<?php

namespace App\Livewire;

use App\Livewire\Concerns\MensajesEs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.publico')]
#[Title('Acceso')]
class Login extends Component
{
    use MensajesEs;

    public string $email = '';
    public string $password = '';
    public bool $recordar = false;


public function entrar(): void
{
    $this->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $this->email = Str::lower(trim($this->email));

    $clave = Str::lower($this->email).'|'.request()->ip();

    if (RateLimiter::tooManyAttempts($clave, 5)) {
        $this->addError(
            'email',
            'Demasiados intentos. Espera '
            . RateLimiter::availableIn($clave)
            . ' segundos.'
        );

        return;
    }

    if (! Auth::attempt([
        'email' => $this->email,
        'password' => $this->password,
    ], $this->recordar)) {
        RateLimiter::hit($clave, 60);
        $this->password = '';

        $this->addError('email', 'Correo o clave incorrectos.');

        return;
    }

    RateLimiter::clear($clave);
    request()->session()->regenerate();

    $this->redirectIntended(route('admin.dashboard'));
}


    public function render()
    {
        return view('livewire.login');
    }
}
