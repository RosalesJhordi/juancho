@props(['estado'])

@php
    $estilos = [
        'nueva'      => 'bg-orange-500 text-white',
        'contactada' => 'bg-blue-100 text-blue-800',
        'cerrada'    => 'bg-emerald-100 text-emerald-800',
        'descartada' => 'bg-slate-200 text-slate-600',
        'publicado'  => 'bg-emerald-500 text-white',
        'borrador'   => 'bg-amber-100 text-amber-800',
    ];
@endphp

<span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-bold {{ $estilos[$estado] ?? 'bg-slate-200 text-slate-700' }}">{{ ucfirst($estado) }}</span>
