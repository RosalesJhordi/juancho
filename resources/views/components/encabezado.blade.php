@props(['titulo', 'etiqueta' => null])

{{-- Encabezado de página pública: fondo claro con cuadrícula de plano --}}
<section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-sky-50 to-white">
    <div class="cuadricula pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent)]" aria-hidden="true"></div>
    <span class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-200/40 blur-3xl" aria-hidden="true"></span>
    <span class="pointer-events-none absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-yellow-200/40 blur-3xl" aria-hidden="true"></span>

    <div class="relative mx-auto max-w-6xl px-6 py-12 sm:py-16">
        @if ($etiqueta)
            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1 text-xs font-bold uppercase tracking-widest text-marca shadow-sm ring-1 ring-slate-200">
                <span class="h-1.5 w-1.5 rounded-full bg-rojo"></span>{{ $etiqueta }}
            </span>
        @endif
        <h1 class="mt-4 font-display text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">{{ $titulo }}</h1>
        {{ $slot }}
    </div>
</section>
