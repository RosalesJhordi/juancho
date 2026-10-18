@props(['titulo', 'etiqueta' => null, 'desde' => '#2563EB', 'hasta' => '#7C3AED'])

<section class="relative overflow-hidden text-white" style="background:linear-gradient(120deg,{{ $desde }},{{ $hasta }})">
    <span class="pointer-events-none absolute -right-10 -top-12 h-48 w-48 rounded-full bg-yellow-300/70" aria-hidden="true"></span>
    <span class="pointer-events-none absolute -bottom-12 right-44 h-32 w-32 rounded-full bg-pink-400/60" aria-hidden="true"></span>
    <span class="pointer-events-none absolute -left-8 bottom-2 h-20 w-20 rounded-full bg-cyan-300/50" aria-hidden="true"></span>

    <div class="relative mx-auto max-w-6xl px-6 py-12">
        @if ($etiqueta)
            <span class="inline-flex rounded-full bg-white/20 px-4 py-1 text-sm font-semibold">{{ $etiqueta }}</span>
        @endif
        <h1 class="mt-3 font-display text-4xl font-extrabold sm:text-5xl">{{ $titulo }}</h1>
        {{ $slot }}
    </div>
</section>
