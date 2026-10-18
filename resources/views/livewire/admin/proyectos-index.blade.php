<div>
    <x-aviso/>

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-violet-800">Proyectos y fotos</h1>
            <p class="mt-1 text-sm text-slate-600">Sube fotos, completa los datos y publica cuando esté listo.</p>
        </div>
        <a href="{{ route('admin.proyectos.crear') }}" class="btn-primario min-h-12 px-6">Nuevo proyecto</a>
    </div>

    <div class="mt-6 grid gap-3 sm:grid-cols-[1fr_220px]">
        <input type="search" wire:model.live.debounce.400ms="buscar" placeholder="Buscar por nombre…" aria-label="Buscar" class="campo">
        <select wire:model.live="estado" aria-label="Estado" class="campo">
            <option value="">Todos los estados</option>
            <option value="publicado">Publicados</option>
            <option value="borrador">Borradores</option>
        </select>
    </div>

    <div class="mt-6 overflow-x-auto rounded-2xl border-2 border-sky-100 bg-white shadow-sm">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead class="bg-sky-100 text-sky-900">
                <tr>
                    <th class="px-4 py-3 font-bold">Proyecto</th>
                    <th class="px-4 py-3 font-bold">Estado</th>
                    <th class="px-4 py-3 text-right font-bold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sky-100">
                @forelse ($proyectos as $p)
                    <tr wire:key="fila-{{ $p->id }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-20 flex-none overflow-hidden rounded-xl bg-slate-100">
                                    @if ($p->portada_url)
                                        <img src="{{ $p->portada_url }}" alt="" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate font-medium">{{ $p->nombre }}</p>
                                    <p class="text-slate-600">
                                        {{ $p->categoria?->nombre ?? 'Sin categoría' }} · {{ $p->fotos->count() }} {{ $p->fotos->count() === 1 ? 'foto' : 'fotos' }}
                                        @if ($p->destacado) · <span class="text-slate-900">Destacado</span> @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3"><x-estado-badge :estado="$p->estado"/></td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('admin.proyectos.editar', $p->id) }}" class="btn-borde">Editar</a>
                                <button type="button" wire:click="alternarEstado({{ $p->id }})" class="btn-borde">
                                    {{ $p->estado === 'publicado' ? 'Pasar a borrador' : 'Publicar' }}
                                </button>
                                <button type="button" wire:click="alternarDestacado({{ $p->id }})" class="btn-borde">
                                    {{ $p->destacado ? 'Quitar destacado' : 'Destacar' }}
                                </button>
                                <a href="{{ route('proyectos.ver', $p->slug) }}" target="_blank" class="btn-borde">Ver</a>
                                <button type="button" wire:click="eliminar({{ $p->id }})" wire:confirm="¿Eliminar este proyecto y todas sus fotos?" class="btn-peligro">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-14 text-center text-slate-600">No hay proyectos todavía. Crea el primero con “Nuevo proyecto”.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $proyectos->links() }}</div>
</div>
