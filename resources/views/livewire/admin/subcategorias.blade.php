<div>
    <x-aviso/>

    <h1 class="titulo-pagina">Subcategorías</h1>
    <p class="subtitulo-pagina">Dentro de cada categoría: por ejemplo, en Ventanas → Sistema Nova, Serie 25…</p>

    <div class="mt-8 grid gap-8 lg:grid-cols-[360px_1fr]">
        <form wire:submit="guardar" class="flex flex-col gap-4 self-start tarjeta p-6">
            <p class="font-semibold">{{ $editandoId ? 'Editar subcategoría' : 'Nueva subcategoría' }}</p>

            <label class="etiqueta">
                Categoría
                <select wire:model="categoria_id" class="campo">
                    <option value="">Elige una categoría</option>
                    @foreach ($categorias as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('categoria_id') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Nombre
                <input type="text" wire:model="nombre" placeholder="Ej. Sistema Nova" class="campo">
                @error('nombre') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Descripción (opcional)
                <textarea wire:model="descripcion" rows="3" class="campo"></textarea>
                @error('descripcion') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Orden dentro de la categoría (0 = primero)
                <input type="number" min="0" max="9999" wire:model="orden" class="campo">
                @error('orden') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" wire:model="activo" class="h-5 w-5 rounded border-slate-300 text-marca">
                Visible en el sitio
            </label>

            <div class="flex gap-3">
                <button type="submit" class="btn-primario flex-1">Guardar</button>
                @if ($editandoId)
                    <button type="button" wire:click="cancelar" class="btn-borde">Cancelar</button>
                @endif
            </div>
        </form>

        <div>
            <select wire:model.live="filtro" aria-label="Filtrar por categoría" class="campo mb-4 max-w-xs">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
            </select>

            <div class="overflow-x-auto tarjeta">
                <table class="w-full min-w-[560px] text-left text-sm">
                    <thead class="tabla-cabecera">
                        <tr>
                            <th class="px-4 py-3">Subcategoría</th>
                            <th class="px-4 py-3">Categoría</th>
                            <th class="px-4 py-3">Proyectos</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($subcategorias as $s)
                            <tr wire:key="sub-{{ $s->id }}" class="transition hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <p class="font-medium">{{ $s->nombre }}</p>
                                    @if ($s->descripcion) <p class="text-slate-600">{{ $s->descripcion }}</p> @endif
                                </td>
                                <td class="px-4 py-3">{{ $s->categoria->nombre }}</td>
                                <td class="px-4 py-3">{{ $s->proyectos_count }}</td>
                                <td class="px-4 py-3">{{ $s->activo ? 'Visible' : 'Oculta' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" wire:click="editar({{ $s->id }})" class="btn-borde">Editar</button>
                                        <button type="button" wire:click="eliminar({{ $s->id }})" wire:confirm="¿Eliminar esta subcategoría? Sus proyectos quedarán sin subcategoría." class="btn-peligro">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-12 text-center text-slate-600">Aún no hay subcategorías.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
