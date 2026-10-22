<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->unsignedInteger('orden')->default(0)->after('descripcion');
        });

        // Ej.: categoría "Ventanas" → subcategorías "Sistema Nova", "Serie 25"…
        Schema::create('subcategorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('slug');
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['categoria_id', 'slug']);
        });

        Schema::table('proyectos', function (Blueprint $table) {
            $table->foreignId('subcategoria_id')
                ->nullable()
                ->after('categoria_id')
                ->constrained('subcategorias')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subcategoria_id');
        });

        Schema::dropIfExists('subcategorias');

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('orden');
        });
    }
};
