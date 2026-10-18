<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    public const ESTADOS = ['nueva', 'contactada', 'cerrada', 'descartada'];

    protected $fillable = [
        'proyecto_id', 'cliente_id', 'sistema_id', 'ancho', 'alto', 'cantidad', 'area',
        'opciones', 'detalle', 'precio_estimado', 'estado', 'mensaje',
    ];

    protected $casts = [
        'ancho'           => 'decimal:2',
        'alto'            => 'decimal:2',
        'area'            => 'decimal:2',
        'precio_estimado' => 'decimal:2',
        'opciones'        => 'array',
        'detalle'         => 'array',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function sistema()
    {
        return $this->belongsTo(Sistema::class);
    }
}
