<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AutorizaModulo;
use App\Livewire\Concerns\MensajesEs;
use App\Models\Categoria;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Categorías')]
class Categorias extends Component
{
    use AutorizaModulo, MensajesEs;

    protected string $modulo = 'categorias';

    public ?int $editandoId = null;
    public string $nombre = '';
    public string $descripcion = '';
    public int|string $orden = 0;

    public function editar(int $id): void
    {
        $c = Categoria::findOrFail($id);

        $this->editandoId = $c->id;
        $this->nombre = $c->nombre;
        $this->descripcion = (string) $c->descripcion;
        $this->orden = $c->orden;
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('categorias', 'nombre')->ignore($this->editandoId)],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'orden'       => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $datos = [
            'nombre'      => trim($this->nombre),
            'descripcion' => $this->descripcion ?: null,
            'orden'       => (int) $this->orden,
        ];

        if ($this->editandoId) {
            Categoria::findOrFail($this->editandoId)->update($datos);
        } else {
            Categoria::create($datos + ['slug' => Categoria::generarSlug($datos['nombre'])]);
        }

        $this->cancelar();
        session()->now('ok', 'Categoría guardada.');
    }

    public function cancelar(): void
    {
        $this->reset(['editandoId', 'nombre', 'descripcion', 'orden']);
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        // Sus subcategorías se eliminan con ella; los proyectos quedan sin categoría.
        Categoria::findOrFail($id)->delete();

        if ($this->editandoId === $id) {
            $this->cancelar();
        }

        session()->now('ok', 'Categoría eliminada.');
    }

    public function render()
    {
        return view('livewire.admin.categorias', [
            'categorias' => Categoria::withCount(['proyectos', 'subcategorias'])->ordenadas()->get(),
        ]);
    }
}
