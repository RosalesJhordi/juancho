<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Foto;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.publico')]
#[Title('Galería de fotos')]
class Galeria extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $categoria = '';

    public function filtrar(string $slug = ''): void
    {
        $this->categoria = $slug;
        $this->resetPage();
    }

    public function render()
    {
        $fotos = Foto::query()
            ->with('proyecto')
            ->whereHas('proyecto', fn ($q) => $q->publicados()->when(
                $this->categoria !== '',
                fn ($p) => $p->whereHas('categoria', fn ($c) => $c->where('slug', $this->categoria))
            ))
            ->latest('id')
            ->paginate(24);

        return view('livewire.galeria', [
            'fotos'      => $fotos,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }
}
