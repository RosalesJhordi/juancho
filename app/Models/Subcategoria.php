<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/** Subcategoría de una categoría. Ej.: Ventanas → "Sistema Nova", "Serie 25". */
class Subcategoria extends Model
{
    protected $table = 'subcategorias';

    protected $fillable = ['categoria_id', 'nombre', 'slug', 'descripcion', 'orden', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }

    /** El slug es único dentro de su categoría ("Nova" puede existir en Ventanas y en Puertas). */
    public static function generarSlug(string $base, int $categoriaId, ?int $ignorarId = null): string
    {
        $original = Str::slug($base) ?: 'subcategoria';
        $slug = $original;
        $i = 2;

        while (
            static::query()
                ->where('categoria_id', $categoriaId)
                ->where('slug', $slug)
                ->when($ignorarId, fn ($q) => $q->where('id', '!=', $ignorarId))
                ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
