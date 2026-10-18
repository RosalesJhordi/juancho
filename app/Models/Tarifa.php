<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = 'tarifas';

    public const UNIDADES = [
        'm2'     => 'Por m²',
        'ml'     => 'Por metro lineal (perímetro)',
        'unidad' => 'Por unidad',
    ];

    protected $fillable = ['sistema_id', 'precio', 'unidad_calculo', 'vigencia_desde', 'vigencia_hasta', 'activo'];

    protected $casts = [
        'precio'         => 'decimal:2',
        'vigencia_desde' => 'date',
        'vigencia_hasta' => 'date',
        'activo'         => 'boolean',
    ];

    public function sistema()
    {
        return $this->belongsTo(Sistema::class);
    }

    public function scopeVigentes($query)
    {
        $hoy = now()->toDateString();

        return $query->where('activo', true)
            ->where(fn ($q) => $q->whereNull('vigencia_desde')->orWhere('vigencia_desde', '<=', $hoy))
            ->where(fn ($q) => $q->whereNull('vigencia_hasta')->orWhere('vigencia_hasta', '>=', $hoy));
    }

    public function estaVigente(): bool
    {
        $hoy = now()->startOfDay();

        return $this->activo
            && (! $this->vigencia_desde || $this->vigencia_desde->lte($hoy))
            && (! $this->vigencia_hasta || $this->vigencia_hasta->gte($hoy));
    }
}
