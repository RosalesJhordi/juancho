<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AutorizaModulo;
use App\Livewire\Concerns\MensajesEs;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Subcategorías')]
class Subcategorias extends Component
{
    use AutorizaModulo, MensajesEs;

    // Se administra junto con las categorías (mismo permiso).
    protected string $modulo = 'categorias';

    /** Filtro de la lista por categoría. */
    #[Url(as: 'categoria', except: '')]
    public string $filtro = '';

    public ?int $editandoId = null;
    public string $categoria_id = '';
    public string $nombre = '';
    public string $descripcion = '';
    public int|string $orden = 0;
    public bool $activo = true;

    public function editar(int $id): void
    {
        $s = Subcategoria::findOrFail($id);

        $this->editandoId = $s->id;
        $this->categoria_id = (string) $s->categoria_id;
        $this->nombre = $s->nombre;
        $this->descripcion = (string) $s->descripcion;
        $this->orden = $s->orden;
        $this->activo = (bool) $s->activo;
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre'       => [
                'required', 'string', 'max:100',
                Rule::unique('subcategorias', 'nombre')
                    ->where('categoria_id', (int) $this->categoria_id)
                    ->ignore($this->editandoId),
            ],
            'descripcion'  => ['nullable', 'string', 'max:500'],
            'orden'        => ['nullable', 'integer', 'min:0', 'max:9999'],
            'activo'       => ['boolean'],
        ]);

        $datos = [
            'categoria_id' => (int) $this->categoria_id,
            'nombre'       => trim($this->nombre),
            'descripcion'  => $this->descripcion ?: null,
            'orden'        => (int) $this->orden,
            'activo'       => $this->activo,
        ];

        if ($this->editandoId) {
            $sub = Subcategoria::findOrFail($this->editandoId);

            // Si la mueves a otra categoría, sus proyectos dejan de pertenecer a ella.
            if ($sub->categoria_id !== $datos['categoria_id']) {
                $sub->proyectos()->update(['subcategoria_id' => null]);
            }

            $sub->update($datos + ['slug' => Subcategoria::generarSlug($datos['nombre'], $datos['categoria_id'], $sub->id)]);
        } else {
            Subcategoria::create($datos + ['slug' => Subcategoria::generarSlug($datos['nombre'], $datos['categoria_id'])]);
        }

        $categoria = $this->categoria_id; // se conserva para cargar varias seguidas
        $this->cancelar();
        $this->categoria_id = $categoria;

        session()->now('ok', 'Subcategoría guardada.');
    }

    public function cancelar(): void
    {
        $this->reset(['editandoId', 'categoria_id', 'nombre', 'descripcion', 'orden']);
        $this->activo = true;
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        Subcategoria::findOrFail($id)->delete(); // los proyectos quedan sin subcategoría

        if ($this->editandoId === $id) {
            $this->cancelar();
        }

        session()->now('ok', 'Subcategoría eliminada.');
    }

    public function render()
    {
        return view('livewire.admin.subcategorias', [
            'categorias'    => Categoria::ordenadas()->get(),
            'subcategorias' => Subcategoria::with('categoria')
                ->withCount('proyectos')
                ->when($this->filtro !== '', fn ($q) => $q->where('categoria_id', (int) $this->filtro))
                ->join('categorias', 'categorias.id', '=', 'subcategorias.categoria_id')
                ->orderBy('categorias.orden')->orderBy('categorias.nombre')
                ->orderBy('subcategorias.orden')->orderBy('subcategorias.nombre')
                ->select('subcategorias.*')
                ->get(),
        ]);
    }
}
