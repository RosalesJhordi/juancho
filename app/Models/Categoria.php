<?php

namespace App\Models;

use App\Models\Concerns\TieneSlug;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use TieneSlug;

    protected $table = 'categorias';

    protected $fillable = ['nombre', 'slug', 'descripcion', 'orden'];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class)->orderBy('orden')->orderBy('nombre');
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }
}
