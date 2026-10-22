<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AutorizaModulo;
use App\Models\Proyecto;
use App\Support\Fotos;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Proyectos y fotos')]
class ProyectosIndex extends Component
{
    use AutorizaModulo, WithPagination;

    protected string $modulo = 'proyectos';

    #[Url(as: 'q', except: '')]
    public string $buscar = '';

    #[Url(except: '')]
    public string $estado = '';

    public function updatedBuscar(): void { $this->resetPage(); }
    public function updatedEstado(): void { $this->resetPage(); }

    /** Pasa un proyecto de borrador a publicado y viceversa. */
    public function alternarEstado(int $id): void
    {
        $p = Proyecto::findOrFail($id);
        $p->update(['estado' => $p->estado === 'publicado' ? 'borrador' : 'publicado']);

        session()->now('ok', $p->estado === 'publicado' ? 'Proyecto publicado.' : 'Proyecto pasado a borrador.');
    }

    public function alternarDestacado(int $id): void
    {
        $p = Proyecto::findOrFail($id);
        $p->update(['destacado' => ! $p->destacado]);
    }

    public function eliminar(int $id): void
    {
        $p = Proyecto::with('fotos')->findOrFail($id);

        foreach ($p->fotos as $foto) {
            Fotos::disco()->delete($foto->ruta);
        }

        $p->delete();

        session()->now('ok', 'Proyecto eliminado.');
    }

    public function render()
    {
        $like = '%' . addcslashes($this->buscar, '%_\\') . '%';

        $proyectos = Proyecto::with(['fotos', 'categoria', 'subcategoria'])
            ->when($this->buscar !== '', fn ($q) => $q->where('nombre', 'like', $like))
            ->when($this->estado !== '', fn ($q) => $q->where('estado', $this->estado))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.proyectos-index', ['proyectos' => $proyectos]);
    }
}
