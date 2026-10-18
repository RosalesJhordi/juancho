<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Fotos;

class Foto extends Model
{
    protected $table = 'fotos';

    protected $fillable = ['proyecto_id', 'ruta', 'descripcion', 'orden', 'portada'];

    protected $casts = ['portada' => 'boolean'];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function getUrlAttribute(): string
    {
        return Fotos::disco()->url($this->ruta);
    }
}
