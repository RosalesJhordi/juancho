<?php

namespace App\Livewire\Concerns;

/**
 * Comprueba el permiso del módulo en CADA petición del componente
 * (no solo al cargar la página). El componente define: protected string $modulo = 'proyectos';
 */
trait AutorizaModulo
{
    public function bootAutorizaModulo(): void
    {
        $usuario = auth()->user();

        abort_unless($usuario && $usuario->puede($this->modulo), 403);
    }
}
