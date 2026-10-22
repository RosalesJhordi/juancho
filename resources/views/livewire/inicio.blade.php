<div class="relative z-10 flex w-full max-w-xl flex-col items-center text-center">

    <div class="animate-subir">
        <img src="{{ asset('img/logo-juday-glass.png') }}"
             alt="{{ config('catalogo.empresa') }} — Aluminio y vidrio"
             width="900" height="900"
             class="w-full max-w-[21rem] rounded-[2rem] shadow-2xl shadow-slate-900/20 ring-1 ring-slate-200 sm:max-w-sm">
    </div>

    <p class="animate-subir mt-8 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-[11px] font-bold uppercase tracking-[0.22em] text-slate-500 [animation-delay:.1s]">
        Diseño <span class="h-3 w-px bg-sol"></span> Fabricación <span class="h-3 w-px bg-sol"></span> Instalación
    </p>

    <div class="animate-subir mt-8 flex w-full max-w-sm flex-col items-center gap-4 [animation-delay:.2s]">
        <a href="{{ route('cliente') }}" class="btn-primario group min-h-14 w-full text-base">
            Continuar como cliente
            <svg class="h-5 w-5 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>

        @if (auth()->user()?->puedeEntrar())
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-500 underline-offset-4 hover:text-slate-900 hover:underline">Ir al panel de administración</a>
        @else
            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 underline-offset-4 hover:text-slate-900 hover:underline">Acceso administrador</a>
        @endif
    </div>
</div>
