<!DOCTYPE html>
<html lang="es">
<head>
    @include('layouts.partials.head')
    <meta name="robots" content="noindex, nofollow">
</head>
<body class="bg-sky-50 font-sans text-slate-900 antialiased" x-data="{ menu: false }">
@php
    $usuario = auth()->user();
    $arcoiris = 'background:linear-gradient(90deg,#2563EB,#06B6D4,#10B981,#FACC15,#F97316,#EC4899,#8B5CF6)';
    // [ruta, texto, módulo requerido, icono, color del icono]
    $items = [
        ['admin.dashboard',    'Resumen',               null,           'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z',                                                         'bg-blue-500 text-white'],
        ['admin.proyectos',    'Proyectos y fotos',     'proyectos',    'M4 5h16v14H4zM4 15l4-4 4 4 3-3 5 5',                                                                         'bg-violet-500 text-white'],
        ['admin.categorias',   'Categorías',            'categorias',   'M3 7h6l2 2h10v10H3z',                                                                                        'bg-pink-500 text-white'],
        ['admin.sistemas',     'Sistemas',              'sistemas',     'M4 7h10M18 7h2M4 17h2M10 17h10M14 4v6M6 14v6',                                                               'bg-yellow-400 text-slate-900'],
        ['admin.tarifas',      'Tarifas',               'tarifas',      'M12 3v18M17 7.5c0-1.7-2.2-3-5-3s-5 1.3-5 3 2.2 3 5 3 5 1.3 5 3-2.2 3-5 3-5-1.3-5-3',                          'bg-emerald-500 text-white'],
        ['admin.opciones',     'Opciones y accesorios', 'opciones',     'M4 4h16v16H4zM12 8v8M8 12h8',                                                                                'bg-cyan-500 text-white'],
        ['admin.cotizaciones', 'Cotizaciones',          'cotizaciones', 'M6 3h9l4 4v14H6zM9 12h7M9 16h7',                                                                             'bg-orange-500 text-white'],
    ];
@endphp

{{-- Barra superior (móvil) --}}
<div class="sticky top-0 z-30 flex items-center justify-between px-4 py-3 text-white lg:hidden" style="background:linear-gradient(120deg,#2563EB,#7C3AED)">
    <span class="font-display text-base font-extrabold">{{ config('catalogo.empresa') }}</span>
    <button type="button" @click="menu = true" aria-label="Abrir menú" class="flex h-11 w-11 items-center justify-center rounded-full bg-white/20">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
</div>

<div class="lg:flex">
    {{-- Fondo del menú móvil --}}
    <div x-show="menu" x-cloak @click="menu = false" class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden"></div>

    {{-- ============ MENÚ LATERAL ============ --}}
    <aside :class="menu ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-900 text-slate-300 transition-transform lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
        <div class="h-1.5" style="{{ $arcoiris }}"></div>

        <div class="flex items-center justify-between px-5 py-5">
            <span class="flex items-center gap-3">
                <svg class="h-9 w-9" viewBox="0 0 32 32" aria-hidden="true">
                    <rect x="2" y="2" width="12" height="12" rx="3" fill="#2563EB"/>
                    <rect x="18" y="2" width="12" height="12" rx="3" fill="#FACC15"/>
                    <rect x="2" y="18" width="12" height="12" rx="3" fill="#10B981"/>
                    <rect x="18" y="18" width="12" height="12" rx="3" fill="#EC4899"/>
                </svg>
                <span class="font-display text-base font-extrabold text-white">{{ config('catalogo.empresa') }}</span>
            </span>
            <button type="button" @click="menu = false" aria-label="Cerrar menú" class="flex h-11 w-11 items-center justify-center rounded-full text-white lg:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 5l14 14M19 5L5 19"/></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1.5 overflow-y-auto px-3" aria-label="Panel">
            @foreach ($items as [$ruta, $texto, $modulo, $icono, $colorIcono])
                @if (! $modulo || $usuario->puede($modulo))
                    <a href="{{ route($ruta) }}" @click="menu = false"
                       @class(['flex min-h-12 items-center gap-3 rounded-xl px-3 text-sm font-semibold transition',
                           'bg-white/10 text-white ring-1 ring-white/20' => request()->routeIs($ruta . '*'),
                           'hover:bg-white/5 hover:text-white' => ! request()->routeIs($ruta . '*')])>
                        <span class="flex h-9 w-9 flex-none items-center justify-center rounded-lg {{ $colorIcono }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icono }}"/></svg>
                        </span>
                        {{ $texto }}
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="border-t border-white/10 p-4 text-sm">
            <p class="truncate font-bold text-white">{{ $usuario->name }}</p>
            <p class="truncate text-slate-400">{{ $usuario->email }}</p>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('inicio') }}" target="_blank" class="inline-flex min-h-11 flex-1 items-center justify-center rounded-full bg-white/10 text-sm font-semibold text-white transition hover:bg-white/20">Ver sitio</a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="inline-flex min-h-11 w-full items-center justify-center rounded-full bg-rose-500 text-sm font-bold text-white transition hover:bg-rose-600">Salir</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ============ CONTENIDO ============ --}}
    <main class="min-w-0 flex-1 px-5 py-8 lg:px-10 lg:py-10">
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>
