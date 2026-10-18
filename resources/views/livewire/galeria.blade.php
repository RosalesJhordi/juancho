<div>

    <x-encabezado titulo="Galería" etiqueta="Fotos" desde="#EC4899" hasta="#F97316">
        <p class="mt-3 max-w-xl text-lg text-white/90">Toca una foto para verla en grande.</p>
    </x-encabezado>

    <div class="mx-auto w-full max-w-6xl px-6 py-10">

        {{-- Filtros por categoría --}}
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="filtrar('')"
                    @class(['min-h-11 rounded-full px-5 text-sm font-bold transition',
                        'bg-slate-900 text-white' => $categoria === '',
                        'bg-slate-100 text-slate-700 hover:bg-slate-200' => $categoria !== ''])>
                Todas
            </button>
            @foreach ($categorias as $c)
                @php $col = \App\Support\Colores::para($c->id); @endphp
                <button type="button" wire:key="gcat-{{ $c->id }}" wire:click="filtrar('{{ $c->slug }}')"
                        @class(['min-h-11 rounded-full px-5 text-sm font-bold transition',
                            $col['solido'] . ' text-white' => $categoria === $c->slug,
                            $col['suave'] . ' hover:brightness-95' => $categoria !== $c->slug])>
                    {{ $c->nombre }}
                </button>
            @endforeach
        </div>

        @if ($fotos->isEmpty())
            <div class="mt-8 rounded-2xl border-2 border-dashed border-pink-300 bg-pink-50 px-6 py-20 text-center text-slate-600">
                Aún no hay fotos en esta categoría.
            </div>
        @else
            @php
                $items = $fotos->getCollection()->map(fn ($f) => [
                    'url'    => $f->url,
                    'titulo' => $f->proyecto->nombre,
                    'enlace' => route('proyectos.ver', $f->proyecto->slug),
                ])->values();
                $aros = ['hover:ring-blue-500', 'hover:ring-emerald-500', 'hover:ring-orange-500', 'hover:ring-violet-500', 'hover:ring-pink-500', 'hover:ring-cyan-500'];
            @endphp

            {{-- La clave obliga a recrear Alpine cuando cambia la página o el filtro --}}
            <div wire:key="lightbox-{{ $fotos->currentPage() }}-{{ $categoria }}"
                 x-data="{
                    items: @js($items),
                    i: null,
                    abrir(n) { this.i = n; document.body.classList.add('overflow-hidden'); },
                    cerrar() { this.i = null; document.body.classList.remove('overflow-hidden'); },
                    sig() { this.i = (this.i + 1) % this.items.length; },
                    ant() { this.i = (this.i - 1 + this.items.length) % this.items.length; },
                 }"
                 @keydown.escape.window="if (i !== null) cerrar()"
                 @keydown.arrow-right.window="if (i !== null) sig()"
                 @keydown.arrow-left.window="if (i !== null) ant()">

                <div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($fotos as $n => $foto)
                        <button type="button" wire:key="foto-{{ $foto->id }}" @click="abrir({{ $n }})"
                                aria-label="Ampliar foto de {{ $foto->proyecto->nombre }}"
                                class="group aspect-square overflow-hidden rounded-2xl bg-slate-100 shadow-md ring-4 ring-transparent transition hover:-translate-y-1 {{ $aros[$foto->proyecto->categoria_id % count($aros)] }}">
                            <img src="{{ $foto->url }}" alt="{{ $foto->proyecto->nombre }}" loading="lazy"
                                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        </button>
                    @endforeach
                </div>

                {{-- Visor ampliado --}}
                <div x-show="i !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/95 p-4" role="dialog" aria-modal="true" aria-label="Foto ampliada">
                    <button type="button" @click="cerrar()" aria-label="Cerrar" class="absolute inset-0 h-full w-full cursor-default"></button>

                    <template x-if="i !== null">
                        <figure class="relative z-10 flex max-h-full max-w-5xl flex-col items-center gap-4">
                            <img :src="items[i].url" :alt="items[i].titulo" class="max-h-[78vh] w-auto max-w-full rounded-2xl border-4 border-white object-contain">
                            <figcaption class="flex items-center gap-4 text-sm text-white">
                                <span class="font-semibold" x-text="items[i].titulo"></span>
                                <a :href="items[i].enlace" class="rounded-full bg-sol px-4 py-1.5 font-bold text-slate-900">Ver proyecto</a>
                            </figcaption>
                        </figure>
                    </template>

                    <button type="button" @click="ant()" aria-label="Anterior" class="absolute left-3 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-pink-500 text-white shadow-lg">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
                    </button>
                    <button type="button" @click="sig()" aria-label="Siguiente" class="absolute right-3 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-pink-500 text-white shadow-lg">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <button type="button" @click="cerrar()" aria-label="Cerrar" class="absolute right-3 top-3 z-10 flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-900 shadow-lg">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M5 5l14 14M19 5L5 19"/></svg>
                    </button>
                </div>
            </div>

            <div class="mt-10">{{ $fotos->links() }}</div>
        @endif
    </div>
</div>
