<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Proyecto;
use App\Support\Whatsapp;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Catálogo público: solo las fotos que subió el administrador, agrupadas por categoría y subcategoría
 * (Ventanas → Sistema Nova, Serie 25…). Al tocar una foto se abre con el botón "Cotizar por WhatsApp".
 */
#[Layout('layouts.publico')]
#[Title('Catálogo')]
class Catalogo extends Component
{
    #[Url(except: '')]
    public string $categoria = '';

    public function elegir(string $slug = ''): void
    {
        $this->categoria = $slug;
    }

    public function render()
    {
        $proyectos = Proyecto::visibles()
            ->with(['fotos', 'categoria', 'subcategoria'])
            ->when($this->categoria !== '', fn ($q) => $q->whereHas('categoria', fn ($c) => $c->where('slug', $this->categoria)))
            ->get();

        // Orden: categoría (campo "orden"), luego subcategoría; lo que no tiene subcategoría va primero en su categoría.
        $proyectos = $proyectos->sortBy([
            fn ($a, $b) => [$a->categoria?->orden ?? PHP_INT_MAX, $a->categoria?->nombre ?? '~']
                <=> [$b->categoria?->orden ?? PHP_INT_MAX, $b->categoria?->nombre ?? '~'],
            fn ($a, $b) => [$a->subcategoria?->orden ?? -1, $a->subcategoria?->nombre ?? '']
                <=> [$b->subcategoria?->orden ?? -1, $b->subcategoria?->nombre ?? ''],
            fn ($a, $b) => $a->id <=> $b->id,
        ]);

        $secciones = [];
        $items = [];

        foreach ($proyectos as $p) {
            $clave = ($p->categoria_id ?? 0) . '-' . ($p->subcategoria_id ?? 0);

            $secciones[$clave] ??= [
                'clave'       => $clave,
                'titulo'      => $p->subcategoria?->nombre ?? $p->categoria?->nombre ?? 'Otros trabajos',
                'etiqueta'    => $p->subcategoria ? $p->categoria?->nombre : null,
                'descripcion' => $p->subcategoria?->descripcion,
                'fotos'       => [],
            ];

            $ruta = collect([$p->categoria?->nombre, $p->subcategoria?->nombre])->filter()->join(' › ');

            foreach ($p->fotos as $foto) {
                $url = $foto->url;

                $secciones[$clave]['fotos'][] = [
                    'id'  => $foto->id,
                    'n'   => count($items),
                    'url' => $url,
                    'alt' => $p->nombre,
                ];

                $items[] = [
                    'url'    => $url,
                    'titulo' => $p->nombre,
                    'ruta'   => $ruta,
                    'wa'     => Whatsapp::consulta($p, $url),
                ];
            }
        }

        return view('livewire.catalogo', [
            'categorias' => Categoria::ordenadas()
                ->whereHas('proyectos', fn ($q) => $q->visibles())
                ->get(),
            'secciones'  => array_values($secciones),
            'items'      => $items,
        ]);
    }
}
