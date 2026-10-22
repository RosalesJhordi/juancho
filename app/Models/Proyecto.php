<?php

namespace App\Models;

use App\Models\Concerns\TieneSlug;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use TieneSlug;

    protected $table = 'proyectos';

    public const ESTADOS = ['borrador' => 'Borrador', 'publicado' => 'Publicado'];

    protected $fillable = [
        'nombre', 'slug', 'descripcion', 'categoria_id', 'subcategoria_id',
        'color', 'vidrio', 'medidas', 'ubicacion', 'anio', 'estado', 'destacado',
    ];

    protected $casts = ['destacado' => 'boolean'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    /** Primero la portada, luego por orden. */
    public function fotos()
    {
        return $this->hasMany(Foto::class)->orderByDesc('portada')->orderBy('orden')->orderBy('id');
    }

    public function scopePublicados($query)
    {
        return $query->where('estado', 'publicado');
    }

    /** Lo que ve el público: publicado y con al menos una foto subida por el admin. */
    public function scopeVisibles($query)
    {
        return $query->where('estado', 'publicado')->whereHas('fotos');
    }

    public function getPortadaAttribute(): ?Foto
    {
        return $this->fotos->first();
    }

    public function getPortadaUrlAttribute(): ?string
    {
        return $this->portada?->url;
    }
}
