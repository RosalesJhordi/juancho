<div class="relative z-10 w-full max-w-4xl">

    <a href="{{ route('inicio') }}" class="inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
        Volver al inicio
    </a>

    <div class="animate-subir mt-3 grid overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/10 lg:grid-cols-[1fr_1.05fr]">

        {{-- Marca --}}
        <div class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-br from-sky-50 to-blue-100 p-10 lg:flex">
            <div class="cuadricula pointer-events-none absolute inset-0" aria-hidden="true"></div>
            <span class="pointer-events-none absolute -bottom-16 -right-10 h-52 w-52 rounded-full bg-yellow-200/60 blur-3xl" aria-hidden="true"></span>
            <img src="{{ asset('img/logo-juday-glass.png') }}" alt="{{ config('catalogo.empresa') }}" width="320" height="320" class="relative w-56 rounded-3xl shadow-xl shadow-slate-900/20 ring-1 ring-slate-200">
            <div class="relative">
                <p class="font-display text-2xl font-extrabold leading-tight text-slate-900">Administra tu catálogo</p>
                <p class="mt-2 max-w-xs text-sm leading-relaxed text-slate-600">Sube fotos, ordénalas por categoría y subcategoría, y publícalas cuando estén listas.</p>
            </div>
        </div>

        {{-- Formulario --}}
        <form wire:submit="entrar" class="flex flex-col gap-5 p-8 sm:p-10">
            <div class="flex items-center gap-3 lg:hidden">
                <img src="{{ asset('img/favicon.png') }}" alt="" width="48" height="48" class="h-12 w-12 rounded-xl object-cover ring-1 ring-slate-200">
                <span class="font-display text-lg font-extrabold">{{ config('catalogo.empresa') }}</span>
            </div>

            <div>
                <span class="inline-flex rounded-full bg-blue-50 px-3 py-0.5 text-xs font-bold text-blue-700">Panel privado</span>
                <h1 class="mt-2 font-display text-3xl font-extrabold tracking-tight">Iniciar sesión</h1>
            </div>

            <label class="etiqueta">
                Correo
                <input type="email" wire:model="email" autocomplete="username" class="campo" autofocus>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Clave
                <input type="password" wire:model="password" autocomplete="current-password" class="campo">
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="flex items-center gap-3 text-sm font-medium text-slate-600">
                <input type="checkbox" wire:model="recordar" class="h-5 w-5 rounded border-slate-300 text-marca focus:ring-marca">
                Mantener la sesión iniciada
            </label>

            <button type="submit" wire:loading.attr="disabled" class="btn-primario min-h-12 text-[15px]">
                <span wire:loading.remove wire:target="entrar">Entrar</span>
                <span wire:loading wire:target="entrar">Entrando…</span>
            </button>
        </form>
    </div>
</div>
