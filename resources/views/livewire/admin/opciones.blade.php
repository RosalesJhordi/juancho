<div>
    <x-aviso/>

    <h1 class="text-3xl font-extrabold text-violet-800">Opciones y accesorios</h1>
    <p class="mt-1 text-sm text-slate-600">Vidrios, colores, accesorios e instalación que se suman al precio base.</p>

    <div class="mt-8 grid gap-8 lg:grid-cols-[360px_1fr]">
        <form wire:submit="guardar" class="flex flex-col gap-4 self-start rounded-2xl border-2 border-sky-100 bg-white shadow-sm p-6">
            <p class="font-semibold">{{ $editandoId ? 'Editar opción' : 'Nueva opción' }}</p>

            <label class="etiqueta">
                Tipo
                <select wire:model="form.tipo" class="campo">
                    @foreach (\App\Models\Opcion::TIPOS as $valor => $texto)
                        <option value="{{ $valor }}">{{ $texto }}</option>
                    @endforeach
                </select>
                @error('form.tipo') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Nombre
                <input type="text" wire:model="form.nombre" placeholder="Ej. Vidrio templado 8 mm" class="campo">
                @error('form.nombre') <span class="error">{{ $message }}</span> @enderror
            </label>

            <div class="grid grid-cols-2 gap-4">
                <label class="etiqueta">
                    Precio ({{ config('catalogo.moneda') }})
                    <input type="text" inputmode="decimal" wire:model="form.precio" class="campo">
                    @error('form.precio') <span class="error">{{ $message }}</span> @enderror
                </label>
                <label class="etiqueta">
                    Se cobra
                    <select wire:model="form.unidad_calculo" class="campo">
                        @foreach (\App\Models\Tarifa::UNIDADES as $valor => $texto)
                            <option value="{{ $valor }}">{{ $texto }}</option>
                        @endforeach
                    </select>
                    @error('form.unidad_calculo') <span class="error">{{ $message }}</span> @enderror
                </label>
            </div>

            <label class="etiqueta">
                Disponible para
                <select wire:model="form.sistema_id" class="campo">
                    <option value="">Todos los sistemas</option>
                    @foreach ($sistemas as $s)
                        <option value="{{ $s->id }}">Solo {{ $s->nombre }}</option>
                    @endforeach
                </select>
                @error('form.sistema_id') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" wire:model="form.activo" class="h-5 w-5 rounded border-sky-300 text-marca">
                Opción activa
            </label>

            <div class="flex gap-3">
                <button type="submit" class="btn-primario flex-1">Guardar</button>
                @if ($editandoId)
                    <button type="button" wire:click="cancelar" class="btn-borde">Cancelar</button>
                @endif
            </div>
        </form>

        <div class="flex flex-col gap-6">
            @foreach (\App\Models\Opcion::TIPOS as $tipo => $titulo)
                @php $lista = $opciones->get($tipo, collect()); @endphp

                @if ($lista->isNotEmpty())
                    <section wire:key="tipo-{{ $tipo }}">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.1em] text-slate-600">{{ $titulo }}</h2>
                        <div class="mt-2 divide-y divide-sky-100 rounded-2xl border-2 border-sky-100 bg-white shadow-sm">
                            @foreach ($lista as $o)
                                <div wire:key="op-{{ $o->id }}" class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 text-sm">
                                    <div>
                                        <p class="font-medium">{{ $o->nombre }} @unless ($o->activo) <span class="text-slate-500">(inactiva)</span> @endunless</p>
                                        <p class="text-slate-600">
                                            {{ $o->precio > 0 ? \App\Support\Formato::dinero($o->precio) . ' · ' . (\App\Models\Tarifa::UNIDADES[$o->unidad_calculo] ?? '') : 'Incluido' }}
                                            · {{ $o->sistema ? 'Solo ' . $o->sistema->nombre : 'Todos los sistemas' }}
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="editar({{ $o->id }})" class="btn-borde">Editar</button>
                                        <button type="button" wire:click="eliminar({{ $o->id }})" wire:confirm="¿Eliminar esta opción?" class="btn-peligro">Eliminar</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach

            @if ($opciones->isEmpty())
                <p class="border border-dashed border-sky-200 px-6 py-12 text-center text-slate-600">Aún no hay opciones.</p>
            @endif
        </div>
    </div>
</div>
