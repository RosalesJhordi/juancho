<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Opcion;
use App\Models\Sistema;
use App\Models\Tarifa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Usuarios
        // Administrador: configura ADMIN_EMAIL y ADMIN_PASSWORD en .env.
        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => env('ADMIN_EMAIL', 'admin@example.com'),
                'password' => env('ADMIN_PASSWORD') ?: Str::random(14),
                'rol' => 'admin',
            ],
            [
                'name' => 'Juan',
                'email' => 'juan@example.com',
                'password' => '78106572',
                'rol' => 'editor',
            ],
            [
                'name' => 'Jhon',
                'email' => 'jhon@example.com',
                'password' => '71654843',
                'rol' => 'editor',
            ],
        ];

        foreach ($usuarios as $datos) {
            $email = $datos['email'];
            $clave = $datos['password'];

            $usuario = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $datos['name'],
                    'password' => Hash::make($clave),
                    'rol' => $datos['rol'],
                ]
            );

            if ($usuario->wasRecentlyCreated) {
                $this->command?->info(
                    "Usuario creado: {$email} | Clave: {$clave}"
                );
            } else {
                $this->command?->warn(
                    "El usuario {$email} ya existe; no se modificó."
                );
            }
        }

        // ---- Categorías
        foreach ([
            'Ventanas',
            'Puertas',
            'Mamparas',
            'Divisiones de vidrio',
            'Fachadas',
        ] as $nombre) {
            Categoria::firstOrCreate(
                ['slug' => Str::slug($nombre)],
                ['nombre' => $nombre]
            );
        }

        // ---- Sistemas + tarifas de ejemplo
        // Puedes modificarlas desde el panel → Tarifas.
        $sistemas = [
            'Corredizo' => [
                'Ventanas y puertas con hojas que se deslizan.',
                180,
            ],
            'Batiente' => [
                'Hojas con bisagras que abren hacia adentro o afuera.',
                220,
            ],
            'Fijo' => [
                'Paños de vidrio fijos, sin apertura.',
                140,
            ],
        ];

        foreach ($sistemas as $nombre => [$descripcion, $precio]) {
            $sistema = Sistema::firstOrCreate(
                ['slug' => Str::slug($nombre)],
                [
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'activo' => true,
                ]
            );

            if (! $sistema->tarifas()->exists()) {
                $sistema->tarifas()->create([
                    'precio' => $precio,
                    'unidad_calculo' => 'm2',
                ]);
            }
        }

        // ---- Opciones de ejemplo
        // Puedes modificarlas desde el panel → Opciones.
        $opciones = [
            ['vidrio', 'Vidrio transparente 6 mm', 0, 'm2'],
            ['vidrio', 'Vidrio templado 8 mm', 90, 'm2'],
            ['vidrio', 'Vidrio laminado', 130, 'm2'],
            ['color', 'Natural (anodizado)', 0, 'm2'],
            ['color', 'Negro', 25, 'm2'],
            ['color', 'Blanco', 20, 'm2'],
            ['accesorio', 'Cerradura adicional', 35, 'unidad'],
            ['accesorio', 'Mosquitero', 60, 'm2'],
            ['instalacion', 'Instalación estándar', 45, 'm2'],
        ];

        foreach ($opciones as [$tipo, $nombre, $precio, $unidad]) {
            Opcion::firstOrCreate(
                [
                    'tipo' => $tipo,
                    'nombre' => $nombre,
                ],
                [
                    'precio' => $precio,
                    'unidad_calculo' => $unidad,
                    'activo' => true,
                ]
            );
        }
    }
}

