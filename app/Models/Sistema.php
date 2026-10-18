<?php

namespace App\Models;

use App\Models\Concerns\TieneSlug;
use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    use TieneSlug;

    protected $table = 'sistemas';

    protected $fillable = ['nombre', 'slug', 'descripcion', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class);
    }

    public function opciones()
    {
        return $this->hasMany(Opcion::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /** Tarifa activa y dentro de su vigencia (la más reciente si hay varias). */
    public function tarifaVigente(): ?Tarifa
    {
        return $this->tarifas()->vigentes()->latest('id')->first();
    }
}
