<?php

namespace App\Livewire;

use App\Support\Whatsapp;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/** 2-3. Opciones del cliente: ver el catálogo o pedir asesoría / contratar un servicio. */
#[Layout('layouts.portada')]
#[Title('Opciones')]
class Cliente extends Component
{
    public function render()
    {
        return view('livewire.cliente', [
            'asesoria' => Whatsapp::asesoria(),
        ]);
    }
}
