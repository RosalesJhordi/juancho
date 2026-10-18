<div>
    <x-aviso/>

    <h1 class="text-3xl font-extrabold text-violet-800">Sistemas de aluminio</h1>
    <p class="mt-1 text-sm text-slate-600">Corredizo, batiente, fijo… Cada sistema tiene sus propias tarifas.</p>

    <div class="mt-8 grid gap-8 lg:grid-cols-[360px_1fr]">
        <form wire:submit="guardar" class="flex flex-col gap-4 self-start rounded-2xl border-2 border-sky-100 bg-white shadow-sm p-6">
            <p class="font-semibold">{{ $editandoId ? 'Editar sistema' : 'Nuevo sistema' }}</p>

            <label class="etiqueta">
                Nombre
                <input type="text" wire:model="nombre" class="campo">
                @error('nombre') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Descripción (se muestra en el cotizador)
                <textarea wire:model="descripcion" rows="3" class="campo"></textarea>
                @error('descripcion') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" wire:model="activo" class="h-5 w-5 rounded border-sky-300 text-marca">
                Disponible en el cotizador
            </label>

            <div class="flex gap-3">
                <button type="submit" class="btn-primario flex-1">Guardar</button>
                @if ($editandoId)
                    <button type="button" wire:click="cancelar" class="btn-borde">Cancelar</button>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto rounded-2xl border-2 border-sky-100 bg-white shadow-sm">
            <table class="w-full min-w-[520px] text-left text-sm">
                <thead class="bg-sky-100 text-sky-900">
                    <tr>
                        <th class="px-4 py-3 font-bold">Sistema</th>
                        <th class="px-4 py-3 font-bold">Proyectos</th>
                        <th class="px-4 py-3 font-bold">Cotizador</th>
                        <th class="px-4 py-3 text-right font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-100">
                    @forelse ($sistemas as $s)
                        <tr wire:key="sis-{{ $s->id }}">
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $s->nombre }}</p>
                                @if ($s->descripcion) <p class="text-slate-600">{{ $s->descripcion }}</p> @endif
                            </td>
                            <td class="px-4 py-3">{{ $s->proyectos_count }}</td>
                            <td class="px-4 py-3">{{ $s->activo ? 'Activo' : 'Oculto' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="editar({{ $s->id }})" class="btn-borde">Editar</button>
                                    <button type="button" wire:click="eliminar({{ $s->id }})" wire:confirm="¿Eliminar este sistema, sus tarifas y sus opciones?" class="btn-peligro">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-12 text-center text-slate-600">Aún no hay sistemas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
