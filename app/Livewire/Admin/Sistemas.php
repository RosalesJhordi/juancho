<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AutorizaModulo;
use App\Livewire\Concerns\MensajesEs;
use App\Models\Sistema;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Sistemas')]
class Sistemas extends Component
{
    use AutorizaModulo, MensajesEs;

    protected string $modulo = 'sistemas';

    public ?int $editandoId = null;
    public string $nombre = '';
    public string $descripcion = '';
    public bool $activo = true;

    public function editar(int $id): void
    {
        $s = Sistema::findOrFail($id);

        $this->editandoId = $s->id;
        $this->nombre = $s->nombre;
        $this->descripcion = (string) $s->descripcion;
        $this->activo = (bool) $s->activo;
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('sistemas', 'nombre')->ignore($this->editandoId)],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'activo'      => ['boolean'],
        ]);

        $datos = [
            'nombre'      => trim($this->nombre),
            'descripcion' => $this->descripcion ?: null,
            'activo'      => $this->activo,
        ];

        if ($this->editandoId) {
            Sistema::findOrFail($this->editandoId)->update($datos);
        } else {
            Sistema::create($datos + ['slug' => Sistema::generarSlug($datos['nombre'])]);
        }

        $this->cancelar();
        session()->now('ok', 'Sistema guardado.');
    }

    public function cancelar(): void
    {
        $this->reset(['editandoId', 'nombre', 'descripcion']);
        $this->activo = true;
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        // Se eliminan también sus tarifas y opciones; los proyectos quedan sin sistema.
        Sistema::findOrFail($id)->delete();

        if ($this->editandoId === $id) {
            $this->cancelar();
        }

        session()->now('ok', 'Sistema eliminado.');
    }

    public function render()
    {
        return view('livewire.admin.sistemas', [
            'sistemas' => Sistema::withCount('proyectos')->orderBy('nombre')->get(),
        ]);
    }
}
