<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    @include('layouts.partials.head')
</head>
<body class="flex min-h-screen flex-col bg-white font-sans text-slate-900 antialiased">
@php
    $enlaces = [['inicio', 'Inicio'], ['proyectos', 'Proyectos'], ['galeria', 'Galería']];
    $hayWhatsapp = \App\Support\Whatsapp::configurado();
    $arcoiris = 'background:linear-gradient(90deg,#2563EB,#06B6D4,#10B981,#FACC15,#F97316,#EC4899,#8B5CF6)';
@endphp

{{-- ============ CABECERA ============ --}}
<header x-data="{ abierto: false }" class="sticky top-0 z-40 bg-white shadow-sm">
    <div class="h-1.5" style="{{ $arcoiris }}"></div>

    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-3">
        <a href="{{ route('inicio') }}" class="flex items-center gap-3">

<svg class="h-9 w-9" viewBox="0 0 32 32" fill="none" aria-hidden="true">
    <rect x="3" y="3" width="26" height="26" rx="4" fill="#2563EB"/>
    <rect x="7" y="7" width="18" height="18" rx="1" stroke="#FFFFFF" stroke-width="2"/>
    <path d="M16 7V25M7 16H25" stroke="#FFFFFF" stroke-width="1.5"/>
    <circle cx="18.5" cy="14" r="1" fill="#FACC15"/>
</svg>

            <span class="font-display text-lg font-extrabold tracking-tight">{{ config('catalogo.empresa') }}</span>
        </a>

        <nav class="hidden items-center gap-1 md:flex" aria-label="Principal">
            @foreach ($enlaces as [$ruta, $texto])
                <a href="{{ route($ruta) }}"
                   @class(['rounded-full px-4 py-2 text-sm font-semibold transition',
                       'bg-blue-100 text-blue-700' => request()->routeIs($ruta . '*'),
                       'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs($ruta . '*')])>
                    {{ $texto }}
                </a>
            @endforeach
            <a href="{{ route('cotizar') }}" class="btn-sol ml-3">Cotizar</a>
        </nav>

        <button type="button" @click="abierto = !abierto" :aria-expanded="abierto" aria-label="Abrir menú"
                class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 text-blue-700 md:hidden">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
        </button>
    </div>

    <nav x-show="abierto" x-cloak class="border-t border-slate-100 px-6 py-3 md:hidden" aria-label="Móvil">
        @foreach ($enlaces as [$ruta, $texto])
            <a href="{{ route($ruta) }}" class="flex min-h-11 items-center text-[15px] font-semibold">{{ $texto }}</a>
        @endforeach
        <a href="{{ route('cotizar') }}" class="btn-sol mt-2 w-full">Cotizar</a>
    </nav>
</header>

<div class="flex-1">
    {{ $slot }}
</div>

{{-- ============ PIE ============ --}}
<footer id="contacto" class="bg-slate-900 text-slate-300">
    <div class="h-1.5" style="{{ $arcoiris }}"></div>

    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-14 text-sm md:grid-cols-3">
        <div>
            <p class="font-display text-xl font-extrabold text-white">{{ config('catalogo.empresa') }}</p>
            <p class="mt-3 max-w-xs leading-relaxed">{{ config('catalogo.descripcion') }}</p>
        </div>
        <div>
            <p class="font-display text-base font-bold text-yellow-300">Contacto</p>
            <ul class="mt-3 space-y-1.5">
                @if (config('catalogo.ciudad')) <li>{{ config('catalogo.ciudad') }}</li> @endif
                @if (config('catalogo.telefono')) <li>{{ config('catalogo.telefono') }}</li> @endif
                @if (config('catalogo.email')) <li>{{ config('catalogo.email') }}</li> @endif
                @if ($hayWhatsapp)
                    <li><a href="{{ \App\Support\Whatsapp::empresa('Hola, quiero hacer una consulta.') }}" target="_blank" rel="noopener" class="font-semibold text-emerald-300 underline underline-offset-4">Escríbenos por WhatsApp</a></li>
                @endif
            </ul>
        </div>
        <div>
            <p class="font-display text-base font-bold text-pink-300">Explorar</p>
            <ul class="mt-3 space-y-1.5">
                <li><a href="{{ route('proyectos') }}" class="hover:text-white">Proyectos</a></li>
                <li><a href="{{ route('galeria') }}" class="hover:text-white">Galería de fotos</a></li>
                <li><a href="{{ route('cotizar') }}" class="hover:text-white">Cotizar</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-white">Acceso administrador</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 px-6 py-4 text-center text-xs text-slate-400">
        © {{ date('Y') }} {{ config('catalogo.empresa') }}
    </div>
</footer>

{{-- Botón flotante de WhatsApp --}}
@if ($hayWhatsapp)
    <a href="{{ \App\Support\Whatsapp::empresa('Hola, quiero hacer una consulta.') }}" target="_blank" rel="noopener"
       aria-label="Escribir por WhatsApp"
       class="fixed bottom-5 right-5 z-30 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-white shadow-xl shadow-emerald-500/40 transition hover:scale-110 hover:bg-emerald-600">
        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 5h16v11H9l-5 4z"/><path d="M8 9.5h8M8 12.5h5"/>
        </svg>
    </a>
@endif

@livewireScripts
</body>
</html>
