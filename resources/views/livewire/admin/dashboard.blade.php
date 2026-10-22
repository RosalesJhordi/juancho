<div>
    <x-aviso/>

    {{-- Bienvenida --}}
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-sky-50 via-white to-blue-50 p-8 shadow-sm">
        <div class="cuadricula pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_left,black,transparent_70%)]" aria-hidden="true"></div>
        <span class="pointer-events-none absolute -right-10 -top-12 h-48 w-48 rounded-full bg-yellow-200/60 blur-2xl" aria-hidden="true"></span>

        <div class="relative">
            <p class="text-sm font-semibold text-slate-500">Panel de administración</p>
            <h1 class="mt-1 font-display text-3xl font-extrabold tracking-tight sm:text-4xl">Hola, {{ auth()->user()->name }}</h1>
            <p class="mt-2 max-w-lg text-slate-600">Los clientes solo ven las fotos que subes aquí, ordenadas por categoría y subcategoría.</p>

            <div class="mt-6 flex flex-wrap gap-3">
                @if (auth()->user()->puede('proyectos'))
                    <a href="{{ route('admin.proyectos.crear') }}" class="btn-primario min-h-12 px-6">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                        Subir fotos
                    </a>
                @endif
                <a href="{{ route('catalogo') }}" target="_blank" class="btn-borde min-h-12 px-6">Ver catálogo público</a>
            </div>
        </div>
    </section>

    {{-- Números --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $tarjetas = [
                ['Visibles en el catálogo', $publicados, route('admin.proyectos', ['estado' => 'publicado']), 'bg-blue-50 text-blue-600', 'M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12zM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6z'],
                ['Borradores',              $borradores, route('admin.proyectos', ['estado' => 'borrador']),  'bg-amber-50 text-amber-600', 'M4 20h4L19 9l-4-4L4 16zM13 7l4 4'],
                ['Fotos subidas',           $fotos,      route('admin.proyectos'),                            'bg-rose-50 text-rose-600', 'M4 5h16v14H4zM4 15l4-4 4 4 3-3 5 5'],
                ['Categorías / subcategorías', $categorias . ' / ' . $subcategorias, route('admin.subcategorias'), 'bg-emerald-50 text-emerald-600', 'M3 7h6l2 2h10v10H3z'],
            ];
        @endphp

        @foreach ($tarjetas as [$titulo, $valor, $enlace, $color, $icono])
            <a href="{{ $enlace }}" class="tarjeta flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <span class="flex h-12 w-12 flex-none items-center justify-center rounded-2xl {{ $color }}">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icono }}"/></svg>
                </span>
                <span>
                    <span class="block font-display text-3xl font-extrabold leading-none">{{ $valor }}</span>
                    <span class="mt-1 block text-xs font-semibold text-slate-500">{{ $titulo }}</span>
                </span>
            </a>
        @endforeach
    </div>

    @if ($sinFotos->isNotEmpty())
        <div class="mt-6 flex gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm" role="alert">
            <svg class="mt-0.5 h-5 w-5 flex-none text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 9v4M12 17h.01M10.3 4.2L2.8 17.5A2 2 0 0 0 4.5 20.5h15a2 2 0 0 0 1.7-3L13.7 4.2a2 2 0 0 0-3.4 0z"/></svg>
            <div>
                <p class="font-display text-base font-extrabold text-amber-900">Publicados sin fotos</p>
                <p class="mt-1 text-amber-900/90">
                    El público no ve estos proyectos hasta que subas al menos una foto:
                    <strong>{{ $sinFotos->pluck('nombre')->join(', ') }}</strong>.
                </p>
            </div>
        </div>
    @endif

    {{-- Últimos proyectos --}}
    <section class="mt-10">
        <h2 class="font-display text-2xl font-extrabold tracking-tight">Últimos proyectos</h2>

        <div class="tarjeta mt-4 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="tabla-cabecera">
                    <tr>
                        <th class="px-4 py-3">Proyecto</th>
                        <th class="px-4 py-3">Categoría</th>
                        <th class="px-4 py-3">Fotos</th>
                        <th class="px-4 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($ultimos as $p)
                        <tr wire:key="ult-{{ $p->id }}" class="transition hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.proyectos.editar', $p->id) }}" class="flex items-center gap-3">
                                    <span class="h-12 w-16 flex-none overflow-hidden rounded-lg bg-slate-100 ring-1 ring-slate-200">
                                        @if ($p->portada_url)
                                            <img src="{{ $p->portada_url }}" alt="" class="h-full w-full object-cover">
                                        @endif
                                    </span>
                                    <span>
                                        <span class="block font-semibold hover:text-marca">{{ $p->nombre }}</span>
                                        <span class="block text-xs text-slate-500">{{ $p->created_at->format('d/m/Y') }}</span>
                                    </span>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $p->categoria?->nombre ?? '—' }}{{ $p->subcategoria ? ' › ' . $p->subcategoria->nombre : '' }}</td>
                            <td class="px-4 py-3">{{ $p->fotos->count() }}</td>
                            <td class="px-4 py-3"><x-estado-badge :estado="$p->estado"/></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-12 text-center text-slate-500">Todavía no hay proyectos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
