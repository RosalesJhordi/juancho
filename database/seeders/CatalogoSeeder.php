<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Categorías y subcategorías iniciales de Juday Glass (según las notas del cliente).
 * Es seguro ejecutarlo varias veces: solo crea lo que falta y no modifica lo que ya existe.
 * Todo se puede cambiar después desde el panel (Categorías y Subcategorías).
 *
 *   php artisan db:seed --class=CatalogoSeeder
 */
class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        // categoría => [ subcategoría => descripción (o null) ]
        $estructura = [
            'Ventanas' => [
                'Sistema Nova'                         => null,
                'Sistema Nova con templado'            => null,
                'Sistema proyectante (templado y crudo)' => null,
                'Serie 20 antiguo'                     => null,
                'Serie 20 moderno'                     => null,
                'Serie 25'                             => null,
                'Serie 35'                             => 'Ventanas y proyectantes',
                'Serie 42'                             => 'Proyectantes',
                'Serie 62'                             => 'Ventanas (sistema europeo)',
            ],
            'Puertas' => [
                'Sistema Pivot'           => null,
                'Puertas para baño'       => null,
                'Puertas para habitación' => null,
                'Serie 35'                => 'Puertas',
            ],
            'Mamparas' => [
                'Mampara sistema Nova (templado y crudo)' => null,
                'Serie 62'                  => 'Mamparas (sistema europeo)',
                'Serie 80'                  => 'Mamparas (sistema europeo)',
                'Puertas de ducha clásicas' => null,
                'Puerta de ducha europeo'   => null,
            ],
            'Muro cortina y fachadas' => [],
            'Exhibidores y vitrinas'  => [],
        ];

        $ordenCategoria = 1;

        foreach ($estructura as $nombre => $subcategorias) {
            $categoria = Categoria::firstOrCreate(
                ['slug' => Str::slug($nombre)],
                ['nombre' => $nombre, 'orden' => $ordenCategoria]
            );

            $ordenCategoria++;
            $orden = 1;

            foreach ($subcategorias as $sub => $descripcion) {
                Subcategoria::firstOrCreate(
                    ['categoria_id' => $categoria->id, 'slug' => Str::slug($sub)],
                    ['nombre' => $sub, 'descripcion' => $descripcion, 'orden' => $orden, 'activo' => true]
                );

                $orden++;
            }
        }
    }
}
