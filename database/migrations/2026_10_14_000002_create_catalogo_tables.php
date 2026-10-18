<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('sistemas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('sistema_id')->nullable()->constrained('sistemas')->nullOnDelete();
            $table->string('color')->nullable();
            $table->string('vidrio')->nullable();
            $table->string('medidas')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('anio', 4)->nullable();
            $table->string('estado')->default('borrador')->index(); // borrador | publicado
            $table->boolean('destacado')->default(false);
            $table->timestamps();
        });

        Schema::create('fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->string('ruta');
            $table->string('descripcion')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('portada')->default(false);
            $table->timestamps();
        });

        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sistema_id')->constrained('sistemas')->cascadeOnDelete();
            $table->decimal('precio', 10, 2);
            $table->string('unidad_calculo')->default('m2'); // m2 | ml | unidad
            $table->date('vigencia_desde')->nullable();
            $table->date('vigencia_hasta')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('opciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->index(); // vidrio | color | accesorio | instalacion
            $table->string('nombre');
            $table->decimal('precio', 10, 2)->default(0);
            $table->string('unidad_calculo')->default('m2'); // m2 | ml | unidad
            $table->foreignId('sistema_id')->nullable()->constrained('sistemas')->cascadeOnDelete(); // null = todos los sistemas
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono')->index();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->nullable()->constrained('proyectos')->nullOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('sistema_id')->nullable()->constrained('sistemas')->nullOnDelete();
            $table->decimal('ancho', 8, 2);
            $table->decimal('alto', 8, 2);
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('area', 10, 2);
            $table->json('opciones')->nullable(); // ids de opciones elegidas
            $table->json('detalle')->nullable();  // desglose del precio (se guarda tal como se mostró)
            $table->decimal('precio_estimado', 12, 2);
            $table->string('estado')->default('nueva')->index(); // nueva | contactada | cerrada | descartada
            $table->text('mensaje')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('opciones');
        Schema::dropIfExists('tarifas');
        Schema::dropIfExists('fotos');
        Schema::dropIfExists('proyectos');
        Schema::dropIfExists('sistemas');
        Schema::dropIfExists('categorias');
    }
};
