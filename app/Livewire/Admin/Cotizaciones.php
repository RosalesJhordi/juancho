<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AutorizaModulo;
use App\Models\Cotizacion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Cotizaciones')]
class Cotizaciones extends Component
{
    use AutorizaModulo, WithPagination;

    protected string $modulo = 'cotizaciones';

    #[Url(except: '')]
    public string $estado = '';

    #[Url(as: 'q', except: '')]
    public string $buscar = '';

    public ?int $verId = null;

    public function updatedEstado(): void { $this->resetPage(); }
    public function updatedBuscar(): void { $this->resetPage(); }

    public function ver(int $id): void
    {
        $this->verId = $id;
    }

    public function cerrar(): void
    {
        $this->verId = null;
    }

    public function cambiarEstado(int $id, string $estado): void
    {
        abort_unless(in_array($estado, Cotizacion::ESTADOS, true), 422);

        Cotizacion::findOrFail($id)->update(['estado' => $estado]);
    }

    public function eliminar(int $id): void
    {
        Cotizacion::findOrFail($id)->delete();

        if ($this->verId === $id) {
            $this->verId = null;
        }

        session()->now('ok', 'Cotización eliminada.');
    }

    public function render()
    {
        $like = '%' . addcslashes($this->buscar, '%_\\') . '%';

        $cotizaciones = Cotizacion::with(['cliente', 'sistema'])
            ->when($this->estado !== '', fn ($q) => $q->where('estado', $this->estado))
            ->when($this->buscar !== '', fn ($q) => $q->whereHas(
                'cliente',
                fn ($c) => $c->where('nombre', 'like', $like)->orWhere('telefono', 'like', $like)
            ))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.cotizaciones', [
            'cotizaciones' => $cotizaciones,
            'sel'          => $this->verId
                ? Cotizacion::with(['cliente', 'sistema', 'proyecto'])->find($this->verId)
                : null,
        ]);
    }
}
