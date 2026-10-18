
<div>

    {{-- ============ PORTADA ============ --}}
    <section class="relative overflow-hidden text-white"
        style="background:linear-gradient(135deg,#263746 0%,#36566B 55%,#52788A 100%)">

        <span class="pointer-events-none absolute -left-16 -top-16 h-60 w-60 rounded-full bg-slate-300/10"
            aria-hidden="true"></span>
        <span class="pointer-events-none absolute -bottom-24 left-1/3 h-72 w-72 rounded-full bg-sky-200/10"
            aria-hidden="true"></span>
        <span class="pointer-events-none absolute right-8 top-8 h-24 w-24 rounded-full bg-white/10"
            aria-hidden="true"></span>

        <div class="relative mx-auto grid max-w-6xl items-center gap-12 px-6 py-16 lg:grid-cols-2 lg:py-24">

            <div>
                <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-sm font-semibold">
                    {{ config('catalogo.empresa') }}
                </span>

                <h1 class="mt-5 font-display text-4xl font-extrabold leading-[1.05] sm:text-6xl">
                    Ventanas, puertas en
                    <span class="text-sky-200">aluminio y vidrio</span>
                </h1>

                <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/85">
                    {{ config('catalogo.descripcion') }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('proyectos') }}"
                        class="btn-sol min-h-12 px-8 text-[15px]">
                        Ver proyectos
                    </a>

                    <a href="{{ route('cotizar') }}"
                        class="inline-flex min-h-12 items-center justify-center rounded-full border-2 border-white/70 px-8 text-[15px] font-bold text-white transition hover:bg-white hover:text-slate-700">
                        Cotizar ahora
                    </a>
                </div>
            </div>

            @php
                $hero = $destacados->take(3);

                $sombras = [
                    'shadow-[10px_10px_0_#CBD5E1]',
                    'shadow-[6px_6px_0_#A8BBC8]',
                    'shadow-[6px_6px_0_#D1D9DF]',
                ];
            @endphp

            <div class="grid grid-cols-2 gap-5">
                @forelse ($hero as $i => $p)
                    <a href="{{ route('proyectos.ver', $p->slug) }}"
                        wire:key="hero-{{ $p->id }}"
                        @class([
                            'block overflow-hidden rounded-3xl border-4 border-white/80 bg-white/10 transition duration-300 hover:-translate-y-1',
                            $sombras[$i] ?? '',
                            'col-span-2 aspect-[16/10]' => $i === 0,
                            'aspect-[4/3]' => $i > 0,
                        ])>

                        @if ($p->portada_url)
                            <img src="{{ $p->portada_url }}"
                                alt="{{ $p->nombre }}"
                                class="h-full w-full object-cover">
                        @else
                            <x-placeholder class="h-full w-full"/>
                        @endif

                    </a>
                @empty
                    <x-placeholder
                        class="col-span-2 aspect-[16/10] rounded-3xl border-4 border-white/80 shadow-[10px_10px_0_#CBD5E1]"/>
                @endforelse
            </div>

        </div>
    </section>


    {{-- ============ CATEGORÍAS ============ --}}
    @if ($categorias->isNotEmpty())
        <section class="mx-auto max-w-6xl px-6 pt-16">

            <h2 class="font-display text-3xl font-extrabold">
                Categorías
            </h2>

            <div class="mt-2 h-1.5 w-16 rounded-full bg-slate-500"></div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($categorias as $c)
                    @php $col = \App\Support\Colores::para($c->id); @endphp

                    <a href="{{ route('proyectos', ['categoria' => $c->slug]) }}"
                        wire:key="cat-{{ $c->id }}"
                        class="flex items-center justify-between gap-3 rounded-2xl px-6 py-7 text-white shadow-md transition duration-300 hover:-translate-y-1 {{ $col['solido'] }} {{ $col['sombra'] }}">

                        <span class="font-display text-xl font-bold leading-tight">
                            {{ $c->nombre }}
                        </span>

                        <span class="flex h-10 min-w-10 items-center justify-center rounded-full border border-slate-200 bg-white px-3 text-sm font-extrabold text-slate-700">
                            {{ $c->proyectos_count }}
                        </span>
                    </a>
                @endforeach
            </div>

        </section>
    @endif


    {{-- ============ PROYECTOS DESTACADOS ============ --}}
    <section class="mt-16 bg-slate-50">
        <div class="mx-auto max-w-6xl px-6 py-16">

            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="font-display text-3xl font-extrabold">
                        Proyectos destacados
                    </h2>

                    <div class="mt-2 h-1.5 w-16 rounded-full bg-slate-500"></div>
                </div>

                <a href="{{ route('proyectos') }}" class="btn-borde">
                    Ver todos →
                </a>
            </div>

            @if ($destacados->isEmpty())
                <div class="mt-8 rounded-2xl border-2 border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-600">
                    Pronto publicaremos nuestros trabajos.
                </div>
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($destacados as $p)
                        <x-proyecto-card :proyecto="$p" wire:key="dest-{{ $p->id }}"/>
                    @endforeach
                </div>
            @endif

        </div>
    </section>


    {{-- ============ CÓMO COTIZAR ============ --}}
    <section class="mx-auto max-w-6xl px-6 py-16">

        <h2 class="font-display text-3xl font-extrabold">
            Cotiza en minutos
        </h2>

        <div class="mt-2 h-1.5 w-16 rounded-full bg-sky-700"></div>

        @php
            $pasosInicio = [
                [
                    'Elige un modelo',
                    'Selecciona uno de nuestros proyectos como referencia.',
                    'bg-slate-50 border-slate-200',
                    'bg-slate-600',
                ],
                [
                    'Define el sistema',
                    'Corredizo, batiente o fijo, según lo que necesites.',
                    'bg-sky-50 border-sky-100',
                    'bg-sky-800',
                ],
                [
                    'Indica las medidas',
                    'Ancho, alto y cantidad.',
                    'bg-cyan-50 border-cyan-100',
                    'bg-cyan-800',
                ],
                [
                    'Recibe tu presupuesto',
                    'Ves el precio estimado y nos escribes por WhatsApp.',
                    'bg-gray-50 border-gray-200',
                    'bg-gray-700',
                ],
            ];
        @endphp

        <ol class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($pasosInicio as $n => [$titulo, $texto, $caja, $circulo])
                <li class="rounded-2xl border p-6 {{ $caja }}">

                    <span class="flex h-12 w-12 items-center justify-center rounded-full font-display text-xl font-extrabold text-white {{ $circulo }}">
                        {{ $n + 1 }}
                    </span>

                    <p class="mt-4 font-display text-lg font-bold">
                        {{ $titulo }}
                    </p>

                    <p class="mt-1.5 text-sm leading-relaxed text-slate-600">
                        {{ $texto }}
                    </p>

                </li>
            @endforeach
        </ol>

    </section>


    {{-- ============ LLAMADA A LA ACCIÓN ============ --}}
    <section class="relative overflow-hidden text-white"
        style="background:linear-gradient(120deg,#263746 0%,#405D70 100%)">

        <span class="pointer-events-none absolute -right-12 -top-12 h-52 w-52 rounded-full bg-white/5"
            aria-hidden="true"></span>

        <span class="pointer-events-none absolute -bottom-16 left-24 h-40 w-40 rounded-full bg-sky-200/10"
            aria-hidden="true"></span>

        <div class="relative mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-6 px-6 py-14">

            <div>
                <h2 class="font-display text-3xl font-extrabold">
                    ¿Tienes un proyecto en mente?
                </h2>

                <p class="mt-2 text-lg text-white/85">
                    Cuéntanos qué necesitas y te enviamos un presupuesto.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">

                <a href="{{ route('cotizar') }}"
                    class="inline-flex min-h-12 items-center justify-center rounded-full bg-white px-8 text-[15px] font-bold text-slate-700 shadow-md transition hover:-translate-y-0.5 hover:bg-slate-100">
                    Cotizar mi proyecto
                </a>

                @if (\App\Support\Whatsapp::configurado())
                    <a href="{{ \App\Support\Whatsapp::empresa('Hola, quiero hacer una consulta.') }}"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex min-h-12 items-center justify-center rounded-full border-2 border-white/70 px-8 text-[15px] font-bold text-white transition hover:bg-white hover:text-slate-700">
                        WhatsApp
                    </a>
                @endif

            </div>
        </div>
    </section>

</div>
