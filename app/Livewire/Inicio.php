<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/** 1. Pantalla de inicio: logo + "Continuar como cliente" / acceso administrador. */
#[Layout('layouts.portada')]
#[Title('Inicio')]
class Inicio extends Component
{
    public function render()
    {
        return view('livewire.inicio');
    }
}
