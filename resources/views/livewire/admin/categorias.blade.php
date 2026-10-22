<div>
    <x-aviso/>

    <h1 class="titulo-pagina">Categorías</h1>
    <p class="subtitulo-pagina">Ventanas, puertas, mamparas, muro cortina, etc. Cada una puede tener sus <a href="{{ route('admin.subcategorias') }}" class="font-semibold text-marca underline underline-offset-4">subcategorías</a> (sistema Nova, serie 25…).</p>

    <div class="mt-8 grid gap-8 lg:grid-cols-[360px_1fr]">
        <form wire:submit="guardar" class="flex flex-col gap-4 self-start tarjeta p-6">
            <p class="font-semibold">{{ $editandoId ? 'Editar categoría' : 'Nueva categoría' }}</p>

            <label class="etiqueta">
                Nombre
                <input type="text" wire:model="nombre" class="campo">
                @error('nombre') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Descripción (opcional)
                <textarea wire:model="descripcion" rows="3" class="campo"></textarea>
                @error('descripcion') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Orden en el sitio (0 = primero)
                <input type="number" min="0" max="9999" wire:model="orden" class="campo">
                @error('orden') <span class="error">{{ $message }}</span> @enderror
            </label>

            <div class="flex gap-3">
                <button type="submit" class="btn-primario flex-1">Guardar</button>
                @if ($editandoId)
                    <button type="button" wire:click="cancelar" class="btn-borde">Cancelar</button>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto tarjeta">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="tabla-cabecera">
                    <tr>
                        <th class="px-4 py-3">Categoría</th>
                        <th class="px-4 py-3">Subcategorías</th>
                        <th class="px-4 py-3">Proyectos</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categorias as $c)
                        <tr wire:key="cat-{{ $c->id }}" class="transition hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $c->nombre }}</p>
                                @if ($c->descripcion) <p class="text-slate-600">{{ $c->descripcion }}</p> @endif
                            </td>
                            <td class="px-4 py-3">{{ $c->subcategorias_count }}</td>
                            <td class="px-4 py-3">{{ $c->proyectos_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="editar({{ $c->id }})" class="btn-borde">Editar</button>
                                    <button type="button" wire:click="eliminar({{ $c->id }})" wire:confirm="¿Eliminar esta categoría y sus subcategorías? Sus proyectos quedarán sin categoría." class="btn-peligro">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-12 text-center text-slate-600">Aún no hay categorías.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
