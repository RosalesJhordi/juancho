<!DOCTYPE html>
<html lang="es">
<head>
    @include('layouts.partials.head')
    <meta name="robots" content="noindex, nofollow">
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased" x-data="{ menu: false }">
@php
    $usuario = auth()->user();
    // [ruta, texto, módulo requerido, icono]
    $items = [
        ['admin.dashboard',     'Resumen',           null,         'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z'],
        ['admin.proyectos',     'Proyectos y fotos', 'proyectos',  'M4 5h16v14H4zM4 15l4-4 4 4 3-3 5 5'],
        ['admin.categorias',    'Categorías',        'categorias', 'M3 7h6l2 2h10v10H3z'],
        ['admin.subcategorias', 'Subcategorías',     'categorias', 'M4 7h10M18 7h2M4 17h2M10 17h10M14 4v6M6 14v6'],
    ];
@endphp

{{-- Barra superior (móvil) --}}
<div class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur lg:hidden">
    <div class="barra-marca"></div>
    <div class="flex items-center justify-between px-4 py-2.5">
        <span class="flex items-center gap-2.5">
            <img src="{{ asset('img/favicon.png') }}" alt="" width="32" height="32" class="h-8 w-8 rounded-lg object-cover">
            <span class="font-display text-base font-extrabold">{{ config('catalogo.empresa') }}</span>
        </span>
        <button type="button" @click="menu = true" aria-label="Abrir menú" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-slate-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    </div>
</div>

<div class="lg:flex">
    <div x-show="menu" x-cloak x-transition.opacity @click="menu = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"></div>

    {{-- ============ MENÚ LATERAL ============ --}}
    <aside :class="menu ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
        <div class="barra-marca"></div>

        <div class="flex items-center justify-between px-5 py-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('img/favicon.png') }}" alt="" width="40" height="40" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200">
                <span class="leading-tight">
                    <span class="block font-display text-base font-extrabold text-slate-900">{{ config('catalogo.empresa') }}</span>
                    <span class="block text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Panel</span>
                </span>
            </a>
            <button type="button" @click="menu = false" aria-label="Cerrar menú" class="flex h-11 w-11 items-center justify-center rounded-full text-slate-700 lg:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 5l14 14M19 5L5 19"/></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3" aria-label="Panel">
            <p class="px-3 pb-1 pt-2 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Menú</p>
            @foreach ($items as [$ruta, $texto, $modulo, $icono])
                @if (! $modulo || $usuario->puede($modulo))
                    @php $activo = request()->routeIs($ruta . '*'); @endphp
                    <a href="{{ route($ruta) }}" @click="menu = false"
                       @class(['group relative flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-semibold transition',
                           'bg-blue-50 text-blue-700' => $activo,
                           'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! $activo])>
                        @if ($activo)
                            <span class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-marca"></span>
                        @endif
                        <svg class="h-5 w-5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icono }}"/></svg>
                        {{ $texto }}
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="border-t border-slate-200 p-4 text-sm">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-blue-100 font-display text-base font-extrabold text-blue-700">{{ mb_strtoupper(mb_substr($usuario->name, 0, 1)) }}</span>
                <div class="min-w-0">
                    <p class="truncate font-bold text-slate-900">{{ $usuario->name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ $usuario->email }}</p>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('catalogo') }}" target="_blank" class="btn-borde flex-1 px-3">Ver sitio</a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="btn-peligro w-full px-3">Salir</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ============ CONTENIDO ============ --}}
    <main class="min-w-0 flex-1 px-5 py-8 lg:px-10 lg:py-10">
        <div class="mx-auto max-w-6xl">
            {{ $slot }}
        </div>
    </main>
</div>

@livewireScripts
</body>
</html>
