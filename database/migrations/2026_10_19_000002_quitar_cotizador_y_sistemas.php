<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * El cotizador por medidas desaparece: el cliente elige una foto y le escribe a la empresa por WhatsApp.
 * Los "sistemas" pasan a ser subcategorías de una categoría.
 *
 * ATENCIÓN: elimina las tablas cotizaciones, clientes, opciones, tarifas y sistemas.
 * Si quieres conservar ese historial, haz un respaldo de la base de datos antes de migrar.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1) Conserva el "sistema" de cada proyecto como subcategoría de su categoría.
        if (Schema::hasTable('sistemas')) {
            DB::table('proyectos')
                ->whereNotNull('sistema_id')
                ->whereNotNull('categoria_id')
                ->orderBy('id')
                ->each(function ($proyecto) {
                    $sistema = DB::table('sistemas')->where('id', $proyecto->sistema_id)->first();

                    if (! $sistema) {
                        return;
                    }

                    $slug = Str::slug($sistema->nombre) ?: 'sistema-' . $sistema->id;

                    $subId = DB::table('subcategorias')
                        ->where('categoria_id', $proyecto->categoria_id)
                        ->where('slug', $slug)
                        ->value('id');

                    $subId ??= DB::table('subcategorias')->insertGetId([
                        'categoria_id' => $proyecto->categoria_id,
                        'nombre'       => $sistema->nombre,
                        'slug'         => $slug,
                        'descripcion'  => $sistema->descripcion,
                        'activo'       => true,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);

                    DB::table('proyectos')->where('id', $proyecto->id)->update(['subcategoria_id' => $subId]);
                });
        }

        // 2) Quita lo del cotizador (primero las tablas que dependen de otras).
        Schema::dropIfExists('cotizaciones');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('opciones');
        Schema::dropIfExists('tarifas');

        if (Schema::hasColumn('proyectos', 'sistema_id')) {
            Schema::table('proyectos', function (Blueprint $table) {
                $table->dropConstrainedForeignId('sistema_id');
            });
        }

        Schema::dropIfExists('sistemas');
    }

    public function down(): void
    {
        // Irreversible: los datos del cotizador se eliminaron. Restaura desde un respaldo.
    }
};
