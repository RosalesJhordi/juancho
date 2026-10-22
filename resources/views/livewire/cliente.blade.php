<div class="relative z-10 w-full max-w-3xl">

    <div class="animate-subir flex items-center justify-between gap-4">
        <a href="{{ route('inicio') }}" class="inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
            Volver
        </a>
        <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-500 shadow-sm ring-1 ring-slate-200">Paso 2 de 3</span>
    </div>

    <div class="animate-subir mt-6 flex items-center gap-4 [animation-delay:.05s]">
        <img src="{{ asset('img/logo-juday-glass.png') }}" alt="" width="64" height="64" class="h-16 w-16 rounded-2xl object-cover shadow-md ring-1 ring-slate-200">
        <div>
            <p class="text-sm text-slate-500">Bienvenido a</p>
            <h1 class="font-display text-3xl font-extrabold tracking-tight sm:text-4xl">{{ config('catalogo.empresa') }}</h1>
        </div>
    </div>

    <p class="animate-subir mt-10 text-lg font-medium text-slate-600 [animation-delay:.1s]">¿Qué deseas hacer?</p>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">

        <a href="{{ route('catalogo') }}"
           class="animate-subir group relative flex flex-col gap-4 overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-xl hover:shadow-blue-600/10 [animation-delay:.15s]">
            <span class="absolute inset-x-0 top-0 h-1 bg-marca"></span>
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-marca">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v14H4zM4 15l4-4 4 4 3-3 5 5"/></svg>
            </span>
            <span class="font-display text-2xl font-extrabold leading-tight">Ver catálogo</span>
            <span class="text-sm leading-relaxed text-slate-500">Mira nuestros trabajos por sistema y serie. Elige una foto y cotízala.</span>
            <span class="mt-auto inline-flex items-center gap-2 pt-2 text-sm font-bold text-marca">
                Entrar
                <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </span>
        </a>

        @if ($asesoria)
            <a href="{{ $asesoria }}" target="_blank" rel="noopener"
               class="animate-subir group relative flex flex-col gap-4 overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-600/10 [animation-delay:.2s]">
                <span class="absolute inset-x-0 top-0 h-1 bg-emerald-500"></span>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <x-icono-wa class="h-6 w-6"/>
                </span>
                <span class="font-display text-2xl font-extrabold leading-tight">Pedir asesoría y contratar servicio</span>
                <span class="text-sm leading-relaxed text-slate-500">Cuéntanos qué necesitas. Te atendemos directo por WhatsApp.</span>
                <span class="mt-auto inline-flex items-center gap-2 pt-2 text-sm font-bold text-emerald-600">
                    Abrir WhatsApp
                    <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
            </a>
        @endif
    </div>
</div>
