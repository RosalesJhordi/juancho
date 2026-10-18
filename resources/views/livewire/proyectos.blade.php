<div>

    <x-encabezado titulo="Proyectos" etiqueta="Portafolio" desde="#2563EB" hasta="#7C3AED">
        <p class="mt-3 max-w-xl text-lg text-white/90">Todos nuestros trabajos. Filtra por categoría, sistema o color.</p>
    </x-encabezado>

    <div class="mx-auto w-full max-w-6xl px-6 py-10">

        {{-- ============ BUSCADOR Y FILTROS ============ --}}
        <div class="grid gap-3 rounded-2xl border-2 border-sky-100 bg-sky-50 p-4 sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr_auto]">
            <input type="search" wire:model.live.debounce.400ms="buscar" placeholder="Buscar proyecto…"
                   aria-label="Buscar proyecto" class="campo">

            <select wire:model.live="categoria" aria-label="Categoría" class="campo">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->slug }}">{{ $c->nombre }}</option>
                @endforeach
            </select>

            <select wire:model.live="sistema" aria-label="Sistema" class="campo">
                <option value="">Todos los sistemas</option>
                @foreach ($sistemas as $s)
                    <option value="{{ $s->slug }}">{{ $s->nombre }}</option>
                @endforeach
            </select>

            <select wire:model.live="color" aria-label="Color" class="campo">
                <option value="">Todos los colores</option>
                @foreach ($colores as $col)
                    <option value="{{ $col }}">{{ $col }}</option>
                @endforeach
            </select>

            <button type="button" wire:click="limpiar" @disabled(! $hayFiltros) class="btn-borde">Limpiar</button>
        </div>

        <p class="mt-6 text-sm font-semibold text-slate-600">
            <span class="rounded-full bg-violet-100 px-3 py-1 text-violet-800">{{ $proyectos->total() }} {{ $proyectos->total() === 1 ? 'proyecto' : 'proyectos' }}</span>
        </p>

        {{-- ============ CATÁLOGO ============ --}}
        @if ($proyectos->isEmpty())
            <div class="mt-4 rounded-2xl border-2 border-dashed border-sky-300 bg-sky-50 px-6 py-20 text-center text-slate-600">
                No encontramos proyectos con esos filtros.
            </div>
        @else
            <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($proyectos as $p)
                    <x-proyecto-card :proyecto="$p" wire:key="proy-{{ $p->id }}"/>
                @endforeach
            </div>

            <div class="mt-10">{{ $proyectos->links() }}</div>
        @endif
    </div>
</div>
