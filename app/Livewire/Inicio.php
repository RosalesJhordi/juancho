<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Proyecto;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.publico')]
#[Title('Inicio')]
class Inicio extends Component
{
    public function render()
    {
        return view('livewire.inicio', [
            'destacados' => Proyecto::publicados()
                ->with(['fotos', 'categoria', 'sistema'])
                ->orderByDesc('destacado')
                ->latest()
                ->take(6)
                ->get(),

            'categorias' => Categoria::withCount(['proyectos' => fn ($q) => $q->publicados()])
                ->orderBy('nombre')
                ->get()
                ->where('proyectos_count', '>', 0)
                ->values(),
        ]);
    }
}
