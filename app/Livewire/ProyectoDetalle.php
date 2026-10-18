<?php

namespace App\Livewire;

use App\Models\Proyecto;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.publico')]
class ProyectoDetalle extends Component
{
    public Proyecto $proyecto;

    public function mount(Proyecto $proyecto): void
    {
        // Los borradores solo los ve el personal del panel (vista previa).
        $permitido = $proyecto->estado === 'publicado' || (auth()->user()?->puedeEntrar() ?? false);
        abort_unless($permitido, 404);

        $this->proyecto = $proyecto;
    }

    public function render()
    {
        $this->proyecto->loadMissing(['fotos', 'categoria', 'sistema']);

        $relacionados = Proyecto::publicados()
            ->with(['fotos', 'categoria', 'sistema'])
            ->where('id', '!=', $this->proyecto->id)
            ->when($this->proyecto->categoria_id, fn ($q, $id) => $q->where('categoria_id', $id))
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.proyecto-detalle', [
            'fotos'        => $this->proyecto->fotos,
            'relacionados' => $relacionados,
        ])->title($this->proyecto->nombre);
    }
}
