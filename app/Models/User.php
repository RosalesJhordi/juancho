<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Reemplaza el User.php que trae Laravel.
 * rol: 'admin' (acceso total) | 'editor' (solo los módulos listados en permisos).
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const MODULOS = [
        'proyectos'  => 'Proyectos y fotos',
        'categorias' => 'Categorías y subcategorías',
    ];

    protected $fillable = ['name', 'email', 'password', 'rol', 'permisos'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'permisos'          => 'array',
    ];

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function puedeEntrar(): bool
    {
        return in_array($this->rol, ['admin', 'editor'], true);
    }

    public function puede(string $modulo): bool
    {
        return $this->esAdmin()
            || ($this->rol === 'editor' && in_array($modulo, $this->permisos ?? [], true));
    }
}
