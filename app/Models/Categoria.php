<?php

namespace App\Models;

use App\Models\Concerns\TieneSlug;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use TieneSlug;

    protected $table = 'categorias';

    protected $fillable = ['nombre', 'slug', 'descripcion'];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }
}
