<div>
    <x-aviso/>

    <h1 class="text-3xl font-extrabold text-violet-800">Categorías</h1>
    <p class="mt-1 text-sm text-slate-600">Ventanas, puertas, mamparas, divisiones de vidrio, etc.</p>

    <div class="mt-8 grid gap-8 lg:grid-cols-[360px_1fr]">
        <form wire:submit="guardar" class="flex flex-col gap-4 self-start rounded-2xl border-2 border-sky-100 bg-white shadow-sm p-6">
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

            <div class="flex gap-3">
                <button type="submit" class="btn-primario flex-1">Guardar</button>
                @if ($editandoId)
                    <button type="button" wire:click="cancelar" class="btn-borde">Cancelar</button>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto rounded-2xl border-2 border-sky-100 bg-white shadow-sm">
            <table class="w-full min-w-[480px] text-left text-sm">
                <thead class="bg-sky-100 text-sky-900">
                    <tr>
                        <th class="px-4 py-3 font-bold">Categoría</th>
                        <th class="px-4 py-3 font-bold">Proyectos</th>
                        <th class="px-4 py-3 text-right font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-100">
                    @forelse ($categorias as $c)
                        <tr wire:key="cat-{{ $c->id }}">
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $c->nombre }}</p>
                                @if ($c->descripcion) <p class="text-slate-600">{{ $c->descripcion }}</p> @endif
                            </td>
                            <td class="px-4 py-3">{{ $c->proyectos_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="editar({{ $c->id }})" class="btn-borde">Editar</button>
                                    <button type="button" wire:click="eliminar({{ $c->id }})" wire:confirm="¿Eliminar esta categoría? Sus proyectos quedarán sin categoría." class="btn-peligro">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-12 text-center text-slate-600">Aún no hay categorías.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
