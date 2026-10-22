<?php

namespace Database\Seeders;

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
                'name' => 'Juan',
                'email' => 'juan@gmail.com',
                'password' => '78106572',
                'rol' => 'admin',
            ],
            [
                'name' => 'Jhon',
                'email' => 'jhon@gmail.com',
                'password' => '71654843',
                'rol' => 'admin',
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

        // ---- Categorías y subcategorías (Ventanas → Sistema Nova, Serie 25…)
        $this->call(CatalogoSeeder::class);
    }
}
