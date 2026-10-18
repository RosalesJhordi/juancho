@props(['proyecto'])

@php $color = \App\Support\Colores::para($proyecto->categoria_id); @endphp

<a href="{{ route('proyectos.ver', $proyecto->slug) }}"
   {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-2xl border-2 bg-white shadow-md transition hover:-translate-y-1 hover:shadow-xl ' . $color['borde']]) }}>
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
        @if ($proyecto->portada_url)
            <img src="{{ $proyecto->portada_url }}" alt="{{ $proyecto->nombre }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <x-placeholder class="h-full w-full"/>
        @endif

        <span class="absolute left-3 top-3 rounded-full px-3 py-1 text-xs font-bold {{ $color['suave'] }}">
            {{ $proyecto->categoria?->nombre ?? 'Proyecto' }}
        </span>

        @if ($proyecto->destacado)
            <span class="absolute right-3 top-3 rounded-full bg-sol px-3 py-1 text-xs font-bold text-slate-900">Destacado</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-1 px-5 pb-5 pt-4">
        <span class="font-display text-lg font-bold leading-tight">{{ $proyecto->nombre }}</span>
        @if ($proyecto->sistema)
            <span class="text-sm text-slate-600">Sistema {{ $proyecto->sistema->nombre }}</span>
        @endif
        <span class="mt-auto pt-3 text-sm font-bold {{ $color['texto'] }}">Ver proyecto →</span>
    </div>
</a>
