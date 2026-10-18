<?php

namespace App\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Proyecto;
use App\Models\Sistema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Resumen')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'publicados' => Proyecto::publicados()->count(),
            'borradores' => Proyecto::where('estado', 'borrador')->count(),
            'nuevas'     => Cotizacion::where('estado', 'nueva')->count(),
            'clientes'   => Cliente::count(),
            'ultimas'    => Cotizacion::with(['cliente', 'sistema'])->latest()->take(6)->get(),
            // Sistemas activos que el cotizador no puede calcular todavía
            'sinTarifa'  => Sistema::activos()->get()->filter(fn ($s) => ! $s->tarifaVigente())->values(),
        ]);
    }
}
