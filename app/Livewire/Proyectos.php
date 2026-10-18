<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Proyecto;
use App\Models\Sistema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.publico')]
#[Title('Proyectos')]
class Proyectos extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $buscar = '';

    #[Url(except: '')]
    public string $categoria = '';

    #[Url(except: '')]
    public string $sistema = '';

    #[Url(except: '')]
    public string $color = '';

    public function updatedBuscar(): void { $this->resetPage(); }
    public function updatedCategoria(): void { $this->resetPage(); }
    public function updatedSistema(): void { $this->resetPage(); }
    public function updatedColor(): void { $this->resetPage(); }

    public function limpiar(): void
    {
        $this->reset(['buscar', 'categoria', 'sistema', 'color']);
        $this->resetPage();
    }

    public function render()
    {
        $like = '%' . addcslashes($this->buscar, '%_\\') . '%';

        $proyectos = Proyecto::publicados()
            ->with(['fotos', 'categoria', 'sistema'])
            ->when($this->buscar !== '', fn ($q) => $q->where(
                fn ($w) => $w->where('nombre', 'like', $like)->orWhere('descripcion', 'like', $like)
            ))
            ->when($this->categoria !== '', fn ($q) => $q->whereHas('categoria', fn ($c) => $c->where('slug', $this->categoria)))
            ->when($this->sistema !== '', fn ($q) => $q->whereHas('sistema', fn ($s) => $s->where('slug', $this->sistema)))
            ->when($this->color !== '', fn ($q) => $q->where('color', $this->color))
            ->orderByDesc('destacado')
            ->latest()
            ->paginate(12);

        return view('livewire.proyectos', [
            'proyectos'  => $proyectos,
            'categorias' => Categoria::orderBy('nombre')->get(),
            'sistemas'   => Sistema::activos()->orderBy('nombre')->get(),
            'colores'    => Proyecto::publicados()->whereNotNull('color')->where('color', '!=', '')
                ->distinct()->orderBy('color')->pluck('color'),
            'hayFiltros' => $this->buscar !== '' || $this->categoria !== '' || $this->sistema !== '' || $this->color !== '',
        ]);
    }
}
