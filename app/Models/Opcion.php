<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Vidrios, colores de aluminio, accesorios e instalación que suman al precio. */
class Opcion extends Model
{
    protected $table = 'opciones';

    public const TIPOS = [
        'vidrio'      => 'Vidrio',
        'color'       => 'Color de aluminio',
        'accesorio'   => 'Accesorio',
        'instalacion' => 'Instalación',
    ];

    protected $fillable = ['tipo', 'nombre', 'precio', 'unidad_calculo', 'sistema_id', 'activo'];

    protected $casts = ['precio' => 'decimal:2', 'activo' => 'boolean'];

    public function sistema()
    {
        return $this->belongsTo(Sistema::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /** Disponible para un sistema (sistema_id nulo = todos los sistemas). */
    public function scopeParaSistema($query, ?int $sistemaId)
    {
        return $query->where(fn ($q) => $q->whereNull('sistema_id')->orWhere('sistema_id', $sistemaId));
    }
}
