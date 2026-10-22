<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AutorizaModulo;
use App\Livewire\Concerns\MensajesEs;
use App\Models\Categoria;
use App\Models\Foto;
use App\Models\Proyecto;
use App\Models\Subcategoria;
use App\Support\Fotos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Proyecto')]
class ProyectoFormulario extends Component
{
    use AutorizaModulo, MensajesEs, WithFileUploads;

    protected string $modulo = 'proyectos';

    public ?int $proyectoId = null;

    public array $form = [
        'nombre'       => '',
        'descripcion'  => '',
        'categoria_id' => '',
        'subcategoria_id' => '',
        'color'        => '',
        'vidrio'       => '',
        'medidas'      => '',
        'ubicacion'    => '',
        'anio'         => '',
        'estado'       => 'borrador',
        'destacado'    => false,
    ];

    /** Fotos nuevas, todavía sin guardar. */
    public array $nuevasFotos = [];

    public function mount($proyecto = null): void
    {
        if ($proyecto === null) {
            return;
        }

        $p = Proyecto::findOrFail($proyecto);
        $this->proyectoId = $p->id;

        foreach (array_keys($this->form) as $campo) {
            $this->form[$campo] = $campo === 'destacado'
                ? (bool) $p->destacado
                : (string) ($p->{$campo} ?? '');
        }
    }

    protected function rules(): array
    {
        return [
            'form.nombre'       => ['required', 'string', 'max:150'],
            'form.descripcion'  => ['nullable', 'string', 'max:3000'],
            'form.categoria_id' => ['nullable', 'exists:categorias,id'],
            // La subcategoría debe pertenecer a la categoría elegida.
            'form.subcategoria_id' => [
                'nullable',
                Rule::exists('subcategorias', 'id')->where('categoria_id', (int) $this->form['categoria_id']),
            ],
            'form.color'        => ['nullable', 'string', 'max:60'],
            'form.vidrio'       => ['nullable', 'string', 'max:100'],
            'form.medidas'      => ['nullable', 'string', 'max:100'],
            'form.ubicacion'    => ['nullable', 'string', 'max:150'],
            'form.anio'         => ['nullable', 'digits:4'],
            'form.estado'       => ['required', 'in:borrador,publicado'],
            'form.destacado'    => ['boolean'],
            'nuevasFotos'       => ['array', 'max:20'],
            // Solo formatos seguros (sin SVG).
            'nuevasFotos.*'     => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ];
    }

    /** Al cambiar la categoría, la subcategoría elegida deja de valer. */
    public function updatedForm($valor, $campo): void
    {
        if ($campo === 'categoria_id') {
            $this->form['subcategoria_id'] = '';
        }
    }

    /** Descarta al subir los archivos que no sean imágenes válidas, para que la vista previa no falle. */
    public function updatedNuevasFotos(): void
    {
        $validas = [];
        $hayInvalidas = false;

        foreach ($this->nuevasFotos as $foto) {
            $revision = Validator::make(
                ['foto' => $foto],
                ['foto' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192']]
            );

            if ($revision->fails()) {
                $hayInvalidas = true;
                $foto->delete();

                continue;
            }

            $validas[] = $foto;
        }

        $this->nuevasFotos = $validas;

        if ($hayInvalidas) {
            $this->addError('nuevasFotos', 'Solo se aceptan fotos JPG, PNG o WEBP de hasta 8 MB. Las demás se descartaron.');
        }
    }

    public function guardar()
    {
        $this->validate();

        $datos = collect($this->form)->map(fn ($v) => $v === '' ? null : $v)->all();
        $datos['destacado'] = (bool) $this->form['destacado'];

        if ($this->proyectoId) {
            $proyecto = Proyecto::findOrFail($this->proyectoId);
            $proyecto->update($datos);
        } else {
            $datos['slug'] = Proyecto::generarSlug($datos['nombre']);
            $proyecto = Proyecto::create($datos);
        }

        $this->guardarFotos($proyecto);

        session()->flash('ok', 'Proyecto guardado.');

        return redirect()->route('admin.proyectos');
    }

    private function guardarFotos(Proyecto $proyecto): void
    {
        $sinPortada = ! $proyecto->fotos()->exists();

        foreach ($this->nuevasFotos as $foto) {
            $proyecto->fotos()->create([
                'ruta'    => Fotos::disco()->putFile("proyectos/{$proyecto->id}", $foto),
                'portada' => $sinPortada,
            ]);

            $sinPortada = false; // solo la primera foto del proyecto es portada
        }
    }

    public function quitarNueva(int $indice): void
    {
        unset($this->nuevasFotos[$indice]);
        $this->nuevasFotos = array_values($this->nuevasFotos);
    }

    public function hacerPortada(int $fotoId): void
    {
        $foto = Foto::where('proyecto_id', $this->proyectoId)->findOrFail($fotoId);

        Foto::where('proyecto_id', $this->proyectoId)->update(['portada' => false]);
        $foto->update(['portada' => true]);
    }

    public function eliminarFoto(int $fotoId): void
    {
        $foto = Foto::where('proyecto_id', $this->proyectoId)->findOrFail($fotoId);
        $eraPortada = $foto->portada;

        Fotos::disco()->delete($foto->ruta);
        $foto->delete();

        if ($eraPortada) {
            Foto::where('proyecto_id', $this->proyectoId)->orderBy('id')->first()?->update(['portada' => true]);
        }
    }

    public function render()
    {
        return view('livewire.admin.proyecto-formulario', [
            'fotos'      => $this->proyectoId
                ? Foto::where('proyecto_id', $this->proyectoId)->orderByDesc('portada')->orderBy('id')->get()
                : collect(),
            'categorias' => Categoria::ordenadas()->get(),
            'subcategorias' => $this->form['categoria_id'] !== ''
                ? Subcategoria::where('categoria_id', (int) $this->form['categoria_id'])->ordenadas()->get()
                : collect(),
        ]);
    }
}
