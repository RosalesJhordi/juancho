<div>

    <x-encabezado titulo="Catálogo" etiqueta="Nuestros trabajos">
        <p class="mt-3 max-w-xl text-lg text-slate-600">Toca una foto para verla grande y cotizarla directo por WhatsApp.</p>

        @if ($items !== [])
            <div class="mt-6 flex flex-wrap gap-2 text-xs font-bold text-slate-600">
                <span class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-slate-200">{{ count($items) }} {{ count($items) === 1 ? 'foto' : 'fotos' }}</span>
                <span class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-slate-200">{{ count($secciones) }} {{ count($secciones) === 1 ? 'sección' : 'secciones' }}</span>
            </div>
        @endif
    </x-encabezado>

    {{-- ============ FILTROS (se quedan fijos al bajar) ============ --}}
    @if ($categorias->isNotEmpty())
        <div class="sticky top-[68px] z-30 border-b border-slate-200 bg-white/90 backdrop-blur-md">
            <div class="mx-auto max-w-6xl px-6 py-3">
                <div class="sin-barra -mx-6 flex gap-2 overflow-x-auto px-6" role="group" aria-label="Categorías">
                    <button type="button" wire:click="elegir('')"
                            @class(['chip',
                                'bg-slate-900 text-white ring-slate-900' => $categoria === '',
                                'bg-white text-slate-600 ring-slate-200 hover:text-marca hover:ring-marca' => $categoria !== ''])>
                        Todo
                    </button>

                    @foreach ($categorias as $c)
                        <button type="button" wire:key="cat-{{ $c->id }}" wire:click="elegir('{{ $c->slug }}')"
                                @class(['chip',
                                    'bg-marca text-white ring-marca shadow-md shadow-blue-600/25' => $categoria === $c->slug,
                                    'bg-white text-slate-600 ring-slate-200 hover:text-marca hover:ring-marca' => $categoria !== $c->slug])>
                            {{ $c->nombre }}
                        </button>
                    @endforeach
                </div>

                {{-- Ir directo a una sección --}}
                @if (count($secciones) > 1)
                    <div class="sin-barra -mx-6 mt-2 flex items-center gap-1 overflow-x-auto px-6 text-xs">
                        <span class="flex-none pr-1 font-bold uppercase tracking-wide text-slate-400">Ir a</span>
                        @foreach ($secciones as $sec)
                            <a href="#sec-{{ $sec['clave'] }}" class="flex-none rounded-full px-3 py-1.5 font-semibold text-slate-500 transition hover:bg-blue-50 hover:text-marca">{{ $sec['titulo'] }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="mx-auto w-full max-w-6xl px-6 pb-10 pt-6">

        @if ($items === [])
            <div class="mt-10 flex flex-col items-center gap-3 rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-20 text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm ring-1 ring-slate-200">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v14H4zM4 15l4-4 4 4 3-3 5 5"/></svg>
                </span>
                <p class="font-display text-xl font-extrabold">Pronto publicaremos nuestros trabajos</p>
                <p class="max-w-sm text-sm text-slate-500">Mientras tanto, puedes pedir asesoría directamente por WhatsApp.</p>
            </div>
        @else
            {{-- La clave obliga a recrear Alpine cuando cambia la categoría --}}
            <div wire:key="visor-{{ $categoria }}"
                 x-data="{
                    items: @js($items),
                    i: null,
                    x0: 0,
                    abrir(n) { this.i = n; document.body.classList.add('overflow-hidden'); },
                    cerrar() { this.i = null; document.body.classList.remove('overflow-hidden'); },
                    sig() { this.i = (this.i + 1) % this.items.length; },
                    ant() { this.i = (this.i - 1 + this.items.length) % this.items.length; },
                    deslizar(e) { const d = e.changedTouches[0].clientX - this.x0; if (Math.abs(d) > 60) { d < 0 ? this.sig() : this.ant(); } },
                 }"
                 @keydown.escape.window="if (i !== null) cerrar()"
                 @keydown.arrow-right.window="if (i !== null) sig()"
                 @keydown.arrow-left.window="if (i !== null) ant()">

                {{-- ============ SECCIONES ============ --}}
                @foreach ($secciones as $sec)
                    <section wire:key="sec-{{ $sec['clave'] }}" id="sec-{{ $sec['clave'] }}" class="scroll-mt-40 pt-12">
                        <div class="flex flex-wrap items-end justify-between gap-3">
                            <div class="flex items-start gap-4">
                                <span class="font-display text-4xl font-extrabold leading-none text-slate-200">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <div>
                                    @if ($sec['etiqueta'])
                                        <p class="text-xs font-bold uppercase tracking-widest text-marca">{{ $sec['etiqueta'] }}</p>
                                    @endif
                                    <h2 class="font-display text-2xl font-extrabold tracking-tight sm:text-3xl">{{ $sec['titulo'] }}</h2>
                                    @if ($sec['descripcion'])
                                        <p class="mt-1 text-sm text-slate-500">{{ $sec['descripcion'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                {{ count($sec['fotos']) }} {{ count($sec['fotos']) === 1 ? 'foto' : 'fotos' }}
                            </span>
                        </div>
                        <div class="mt-3 flex h-1 w-20 overflow-hidden rounded-full"><span class="flex-1 bg-rojo"></span><span class="flex-1 bg-sol"></span><span class="flex-1 bg-marca"></span></div>

                        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4">
                            @foreach ($sec['fotos'] as $f)
                                <button type="button" wire:key="foto-{{ $f['id'] }}" @click="abrir({{ $f['n'] }})"
                                        aria-label="Ver y cotizar: {{ $f['alt'] }}"
                                        class="group relative aspect-[4/3] overflow-hidden rounded-2xl bg-slate-100 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-900/10">
                                    <img src="{{ $f['url'] }}" alt="{{ $f['alt'] }}" loading="lazy"
                                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                    <span class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-2 bg-gradient-to-t from-slate-900/70 via-slate-900/20 to-transparent px-3 pb-2.5 pt-10 text-left text-white sm:opacity-0 sm:transition sm:group-hover:opacity-100 sm:group-focus-visible:opacity-100">
                                        <span class="truncate text-xs font-semibold">{{ $f['alt'] }}</span>
                                        <span class="flex-none rounded-full bg-emerald-500 px-2.5 py-1 text-[11px] font-bold">Cotizar</span>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </section>
                @endforeach

                {{-- ============ FOTO ELEGIDA + COTIZAR ============ --}}
                <div x-show="i !== null" x-cloak x-transition.opacity
                     class="fixed inset-0 z-50 flex items-end justify-center bg-slate-900/50 backdrop-blur-sm sm:items-center sm:p-6"
                     role="dialog" aria-modal="true" aria-label="Foto ampliada">

                    <button type="button" @click="cerrar()" aria-label="Cerrar" class="absolute inset-0 h-full w-full cursor-default"></button>

                    <template x-if="i !== null">
                        <div class="animate-aparecer relative z-10 flex max-h-[95vh] w-full max-w-6xl flex-col overflow-hidden rounded-t-3xl bg-white shadow-2xl sm:rounded-3xl lg:flex-row"
                             @touchstart.passive="x0 = $event.touches[0].clientX" @touchend.passive="deslizar($event)">

                            {{-- Foto --}}
                            <div class="relative flex min-h-0 flex-1 items-center justify-center bg-slate-100">
                                <img :src="items[i].url" :alt="items[i].titulo" class="max-h-[58vh] w-full object-contain lg:max-h-[90vh]">

                                <button type="button" @click="ant()" aria-label="Anterior" class="absolute left-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-slate-900 shadow-lg ring-1 ring-slate-200 transition hover:scale-105">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
                                </button>
                                <button type="button" @click="sig()" aria-label="Siguiente" class="absolute right-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-slate-900 shadow-lg ring-1 ring-slate-200 transition hover:scale-105">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>

                            {{-- Datos y botón de cotizar --}}
                            <aside class="flex w-full flex-none flex-col gap-4 p-6 lg:w-80 lg:p-7">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold uppercase tracking-widest text-marca" x-show="items[i].ruta" x-text="items[i].ruta"></p>
                                        <h3 class="mt-1 font-display text-xl font-extrabold leading-tight" x-text="items[i].titulo"></h3>
                                    </div>
                                    <button type="button" @click="cerrar()" aria-label="Cerrar" class="flex h-10 w-10 flex-none items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:bg-slate-100">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M5 5l14 14M19 5L5 19"/></svg>
                                    </button>
                                </div>

                                <p class="text-sm text-slate-500">Foto <span class="font-bold text-slate-700" x-text="i + 1"></span> de <span x-text="items.length"></span></p>

                                <div class="mt-auto flex flex-col gap-2 pt-2">
                                    <template x-if="items[i].wa">
                                        <a :href="items[i].wa" target="_blank" rel="noopener" class="btn-wa min-h-14 text-base">
                                            <x-icono-wa class="h-6 w-6"/> Cotizar por WhatsApp
                                        </a>
                                    </template>
                                    <p class="text-center text-xs text-slate-400">Se abre WhatsApp con esta foto y sus datos.</p>
                                </div>
                            </aside>
                        </div>
                    </template>
                </div>
            </div>
        @endif
    </div>
</div>
