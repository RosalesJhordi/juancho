<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use App\Models\Foto;
use App\Models\Proyecto;
use App\Models\Subcategoria;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Resumen')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'publicados'    => Proyecto::visibles()->count(),
            'borradores'    => Proyecto::where('estado', 'borrador')->count(),
            'fotos'         => Foto::count(),
            'categorias'    => Categoria::count(),
            'subcategorias' => Subcategoria::count(),
            // Publicados sin fotos: el público no los ve
            'sinFotos'      => Proyecto::where('estado', 'publicado')->whereDoesntHave('fotos')->orderBy('nombre')->get(['id', 'nombre']),
            'ultimos'       => Proyecto::with(['categoria', 'subcategoria', 'fotos'])->latest()->take(6)->get(),
        ]);
    }
}
