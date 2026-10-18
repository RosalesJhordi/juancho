<div>
    <x-aviso/>

    <h1 class="text-3xl font-extrabold text-violet-800">Tarifas</h1>
    <p class="mt-1 text-sm text-slate-600">Precio base de cada sistema. El cotizador usa la tarifa activa y dentro de su vigencia.</p>

    <div class="mt-8 grid gap-8 lg:grid-cols-[360px_1fr]">
        <form wire:submit="guardar" class="flex flex-col gap-4 self-start rounded-2xl border-2 border-sky-100 bg-white shadow-sm p-6">
            <p class="font-semibold">{{ $editandoId ? 'Editar tarifa' : 'Nueva tarifa' }}</p>

            <label class="etiqueta">
                Sistema
                <select wire:model="form.sistema_id" class="campo">
                    <option value="">Elige un sistema</option>
                    @foreach ($sistemas as $s)
                        <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                    @endforeach
                </select>
                @error('form.sistema_id') <span class="error">{{ $message }}</span> @enderror
            </label>

            <div class="grid grid-cols-2 gap-4">
                <label class="etiqueta">
                    Precio ({{ config('catalogo.moneda') }})
                    <input type="text" inputmode="decimal" wire:model="form.precio" class="campo">
                    @error('form.precio') <span class="error">{{ $message }}</span> @enderror
                </label>

                <label class="etiqueta">
                    Unidad de cálculo
                    <select wire:model="form.unidad_calculo" class="campo">
                        @foreach (\App\Models\Tarifa::UNIDADES as $valor => $texto)
                            <option value="{{ $valor }}">{{ $texto }}</option>
                        @endforeach
                    </select>
                    @error('form.unidad_calculo') <span class="error">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <label class="etiqueta">
                    Vigente desde
                    <input type="date" wire:model="form.vigencia_desde" class="campo">
                    @error('form.vigencia_desde') <span class="error">{{ $message }}</span> @enderror
                </label>
                <label class="etiqueta">
                    Vigente hasta
                    <input type="date" wire:model="form.vigencia_hasta" class="campo">
                    @error('form.vigencia_hasta') <span class="error">{{ $message }}</span> @enderror
                </label>
            </div>

            <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" wire:model="form.activo" class="h-5 w-5 rounded border-sky-300 text-marca">
                Tarifa activa
            </label>

            <div class="flex gap-3">
                <button type="submit" class="btn-primario flex-1">Guardar</button>
                @if ($editandoId)
                    <button type="button" wire:click="cancelar" class="btn-borde">Cancelar</button>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto rounded-2xl border-2 border-sky-100 bg-white shadow-sm">
            <table class="w-full min-w-[620px] text-left text-sm">
                <thead class="bg-sky-100 text-sky-900">
                    <tr>
                        <th class="px-4 py-3 font-bold">Sistema</th>
                        <th class="px-4 py-3 font-bold">Precio</th>
                        <th class="px-4 py-3 font-bold">Vigencia</th>
                        <th class="px-4 py-3 font-bold">Estado</th>
                        <th class="px-4 py-3 text-right font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-100">
                    @forelse ($tarifas as $t)
                        <tr wire:key="tar-{{ $t->id }}">
                            <td class="px-4 py-3 font-medium">{{ $t->sistema?->nombre ?? '—' }}</td>
                            <td class="px-4 py-3">
                                {{ \App\Support\Formato::dinero($t->precio) }}
                                <span class="block text-slate-600">{{ \App\Models\Tarifa::UNIDADES[$t->unidad_calculo] ?? $t->unidad_calculo }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                @if ($t->vigencia_desde || $t->vigencia_hasta)
                                    {{ $t->vigencia_desde?->format('d/m/Y') ?? 'siempre' }} → {{ $t->vigencia_hasta?->format('d/m/Y') ?? 'sin fin' }}
                                @else
                                    Sin límite
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($t->estaVigente())
                                    <x-estado-badge estado="publicado"/>
                                    <span class="sr-only">Vigente</span>
                                @else
                                    <x-estado-badge estado="borrador"/>
                                    <span class="sr-only">No vigente</span>
                                @endif
                                <span class="mt-1 block text-xs text-slate-600">{{ $t->estaVigente() ? 'Vigente' : 'No vigente' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="editar({{ $t->id }})" class="btn-borde">Editar</button>
                                    <button type="button" wire:click="eliminar({{ $t->id }})" wire:confirm="¿Eliminar esta tarifa?" class="btn-peligro">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-slate-600">Aún no hay tarifas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
