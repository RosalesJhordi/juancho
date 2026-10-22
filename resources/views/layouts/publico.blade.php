<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    @include('layouts.partials.head')
</head>
<body class="flex min-h-screen flex-col bg-white font-sans text-slate-900 antialiased">
@php
    $enlaces = [['cliente', 'Opciones'], ['catalogo', 'Catálogo']];
    $asesoria = \App\Support\Whatsapp::asesoria();
    $hayWhatsapp = \App\Support\Whatsapp::configurado();
@endphp

{{-- ============ CABECERA ============ --}}
<header x-data="{ abierto: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur-md">
    <div class="barra-marca"></div>

    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-6">
        <a href="{{ route('inicio') }}" class="flex items-center gap-3">
            <img src="{{ asset('img/favicon.png') }}" alt="" width="40" height="40" class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200">
            <span class="leading-tight">
                <span class="block font-display text-lg font-extrabold tracking-tight">{{ config('catalogo.empresa') }}</span>
                <span class="hidden text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 sm:block">Aluminio y vidrio</span>
            </span>
        </a>

        <nav class="hidden items-center gap-1 md:flex" aria-label="Principal">
            @foreach ($enlaces as [$ruta, $texto])
                <a href="{{ route($ruta) }}"
                   @class(['relative px-4 py-2 text-sm font-semibold transition',
                       'text-slate-900 after:absolute after:inset-x-4 after:-bottom-[1px] after:h-0.5 after:rounded-full after:bg-marca' => request()->routeIs($ruta . '*'),
                       'text-slate-500 hover:text-slate-900' => ! request()->routeIs($ruta . '*')])>
                    {{ $texto }}
                </a>
            @endforeach
            @if ($asesoria)
                <a href="{{ $asesoria }}" target="_blank" rel="noopener" class="btn-wa ml-3 min-h-10 px-5">
                    <x-icono-wa class="h-4 w-4"/> Pedir asesoría
                </a>
            @endif
        </nav>

        <button type="button" @click="abierto = !abierto" :aria-expanded="abierto" aria-label="Abrir menú"
                class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-slate-700 md:hidden">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
        </button>
    </div>

    <nav x-show="abierto" x-cloak x-transition.opacity class="border-t border-slate-100 bg-white px-6 py-3 md:hidden" aria-label="Móvil">
        @foreach ($enlaces as [$ruta, $texto])
            <a href="{{ route($ruta) }}" class="flex min-h-11 items-center text-[15px] font-semibold">{{ $texto }}</a>
        @endforeach
        @if ($asesoria)
            <a href="{{ $asesoria }}" target="_blank" rel="noopener" class="btn-wa my-2 w-full"><x-icono-wa/> Pedir asesoría</a>
        @endif
    </nav>
</header>

<div class="flex-1">
    {{ $slot }}
</div>

{{-- ============ PIE ============ --}}
<footer id="contacto" class="mt-16 border-t border-slate-200 bg-slate-50">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-14 text-sm text-slate-600 md:grid-cols-[1.4fr_1fr_1fr]">
        <div>
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/favicon.png') }}" alt="" width="44" height="44" class="h-11 w-11 rounded-xl object-cover ring-1 ring-slate-200">
                <p class="font-display text-xl font-extrabold text-slate-900">{{ config('catalogo.empresa') }}</p>
            </div>
            <p class="mt-4 max-w-sm leading-relaxed">{{ config('catalogo.descripcion') }}</p>
            <p class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">
                Diseño <span class="h-3 w-px bg-sol"></span> Fabricación <span class="h-3 w-px bg-sol"></span> Instalación
            </p>
        </div>

        <div>
            <p class="font-display text-base font-bold text-slate-900">Contacto</p>
            <ul class="mt-4 space-y-2">
                @if (config('catalogo.ciudad')) <li>{{ config('catalogo.ciudad') }}</li> @endif
                @if (config('catalogo.telefono')) <li>{{ config('catalogo.telefono') }}</li> @endif
                @if (config('catalogo.email')) <li>{{ config('catalogo.email') }}</li> @endif
                @if ($hayWhatsapp)
                    <li><a href="{{ \App\Support\Whatsapp::empresa('Hola, quiero hacer una consulta.') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 font-semibold text-emerald-700 hover:underline"><x-icono-wa class="h-4 w-4"/> Escríbenos por WhatsApp</a></li>
                @endif
            </ul>
        </div>

        <div>
            <p class="font-display text-base font-bold text-slate-900">Explorar</p>
            <ul class="mt-4 space-y-2">
                <li><a href="{{ route('catalogo') }}" class="hover:text-marca">Catálogo</a></li>
                @if ($asesoria)
                    <li><a href="{{ $asesoria }}" target="_blank" rel="noopener" class="hover:text-marca">Pedir asesoría</a></li>
                @endif
                <li><a href="{{ route('login') }}" class="hover:text-marca">Acceso administrador</a></li>
            </ul>
        </div>
    </div>

    <div class="barra-marca opacity-80"></div>
    <div class="px-6 py-4 text-center text-xs text-slate-500">© {{ date('Y') }} {{ config('catalogo.empresa') }}</div>
</footer>

{{-- WhatsApp flotante --}}
@if ($hayWhatsapp)
    <a href="{{ \App\Support\Whatsapp::empresa('Hola, quiero hacer una consulta.') }}" target="_blank" rel="noopener"
       aria-label="Escribir por WhatsApp"
       class="fixed bottom-5 right-5 z-30 flex h-14 items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 text-sm font-bold text-white shadow-xl shadow-emerald-600/30 transition hover:-translate-y-0.5 hover:bg-emerald-700 sm:px-5">
        <x-icono-wa class="h-6 w-6"/>
        <span class="hidden sm:inline">WhatsApp</span>
    </a>
@endif

@livewireScripts
</body>
</html>
