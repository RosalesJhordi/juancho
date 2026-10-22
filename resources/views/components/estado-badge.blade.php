@props(['estado'])

@php
    $estilos = [
        'publicado'  => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'borrador'   => 'bg-amber-50 text-amber-700 ring-amber-200',
    ];
    $punto = ['publicado' => 'bg-emerald-500', 'borrador' => 'bg-amber-500'];
@endphp

<span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1 text-xs font-bold ring-1 {{ $estilos[$estado] ?? 'bg-slate-100 text-slate-700 ring-slate-200' }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $punto[$estado] ?? 'bg-slate-400' }}"></span>
    {{ ucfirst($estado) }}
</span>
