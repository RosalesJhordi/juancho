@php $color = \App\Support\Colores::para($proyecto->categoria_id); @endphp

<div class="mx-auto w-full max-w-6xl px-6 py-10">

    <nav class="mb-6 text-sm text-slate-600" aria-label="Ruta">
        <a href="{{ route('proyectos') }}" class="font-semibold text-marca hover:underline">Proyectos</a>
        <span aria-hidden="true"> / </span>
        <span class="text-slate-900">{{ $proyecto->nombre }}</span>
    </nav>

    @if ($proyecto->estado !== 'publicado')
        <p class="mb-6 rounded-2xl bg-amber-100 px-4 py-3 text-sm font-semibold text-amber-900">Vista previa: este proyecto está en borrador y el público no lo ve.</p>
    @endif

    <div class="grid gap-10 lg:grid-cols-[1.3fr_1fr]">

        {{-- ============ GALERÍA DE FOTOS ============ --}}
        <div>
            @if ($fotos->isEmpty())
                <x-placeholder class="aspect-[4/3] w-full rounded-3xl"/>
            @else
                <div x-data="{ activa: 0, fotos: @js($fotos->pluck('url')->values()) }">
                    <div class="aspect-[4/3] w-full overflow-hidden rounded-3xl border-4 border-white bg-slate-100 shadow-xl ring-2 ring-blue-200">
                        <img :src="fotos[activa]" alt="{{ $proyecto->nombre }}" class="h-full w-full object-cover">
                    </div>

                    @if ($fotos->count() > 1)
                        <div class="mt-4 grid grid-cols-5 gap-2 sm:grid-cols-6">
                            <template x-for="(f, i) in fotos" :key="i">
                                <button type="button" @click="activa = i"
                                        :class="activa === i ? 'border-marca ring-2 ring-blue-300' : 'border-transparent opacity-80 hover:opacity-100'"
                                        :aria-label="'Ver foto ' + (i + 1)"
                                        class="aspect-square overflow-hidden rounded-xl border-2 bg-slate-100 transition">
                                    <img :src="f" alt="" class="h-full w-full object-cover">
                                </button>
                            </template>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- ============ INFORMACIÓN ============ --}}
        <div class="flex flex-col gap-6">
            <div>
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $color['suave'] }}">{{ $proyecto->categoria?->nombre ?? 'Proyecto' }}</span>
                <h1 class="mt-3 font-display text-3xl font-extrabold leading-tight sm:text-4xl">{{ $proyecto->nombre }}</h1>
            </div>

            @if ($proyecto->descripcion)
                <p class="whitespace-pre-line text-[15px] leading-relaxed text-slate-700">{{ $proyecto->descripcion }}</p>
            @endif

            @php
                $datos = array_filter([
                    'Sistema'   => $proyecto->sistema?->nombre,
                    'Color'     => $proyecto->color,
                    'Vidrio'    => $proyecto->vidrio,
                    'Medidas'   => $proyecto->medidas,
                    'Ubicación' => $proyecto->ubicacion,
                    'Año'       => $proyecto->anio,
                ]);
                $puntos = ['bg-blue-500', 'bg-emerald-500', 'bg-orange-500', 'bg-violet-500', 'bg-pink-500', 'bg-cyan-500'];
            @endphp

            @if ($datos)
                <dl class="overflow-hidden rounded-2xl border-2 border-sky-100 bg-sky-50 text-sm">
                    @foreach ($datos as $etiqueta => $valor)
                        <div class="flex items-center justify-between gap-4 border-b border-sky-100 px-4 py-3 last:border-b-0">
                            <dt class="flex items-center gap-2 font-semibold text-slate-600">
                                <span class="h-2.5 w-2.5 rounded-full {{ $puntos[$loop->index % count($puntos)] }}"></span>
                                {{ $etiqueta }}
                            </dt>
                            <dd class="text-right font-bold">{{ $valor }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif

            <div class="flex flex-col gap-3">
                <a href="{{ route('cotizar', ['proyecto' => $proyecto->slug]) }}" class="btn-sol min-h-14 text-base">Cotizar este proyecto</a>
                @if (\App\Support\Whatsapp::configurado())
                    <a href="{{ \App\Support\Whatsapp::empresa('Hola, me interesa el proyecto: ' . $proyecto->nombre) }}" target="_blank" rel="noopener"
                       class="inline-flex min-h-12 items-center justify-center rounded-full border-2 border-emerald-500 bg-white px-5 text-sm font-bold text-emerald-700 transition hover:bg-emerald-500 hover:text-white">Consultar por WhatsApp</a>
                @endif
            </div>
        </div>
    </div>

    {{-- ============ RELACIONADOS ============ --}}
    @if ($relacionados->isNotEmpty())
        <section class="mt-16 rounded-3xl bg-amber-50 p-6 sm:p-10">
            <h2 class="font-display text-2xl font-extrabold">Más proyectos</h2>
            <div class="mt-2 h-1.5 w-16 rounded-full bg-orange-500"></div>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relacionados as $r)
                    <x-proyecto-card :proyecto="$r" wire:key="rel-{{ $r->id }}"/>
                @endforeach
            </div>
        </section>
    @endif
</div>
