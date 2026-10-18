<div @keydown.escape.window="$wire.cerrar()">
    <x-aviso/>

    <h1 class="text-3xl font-extrabold text-violet-800">Cotizaciones</h1>
    <p class="mt-1 text-sm text-slate-600">Solicitudes enviadas desde el cotizador. Cambia el estado a medida que las atiendes.</p>

    <div class="mt-6 grid gap-3 sm:grid-cols-[1fr_220px]">
        <input type="search" wire:model.live.debounce.400ms="buscar" placeholder="Buscar por nombre o teléfono…" aria-label="Buscar" class="campo">
        <select wire:model.live="estado" aria-label="Estado" class="campo">
            <option value="">Todos los estados</option>
            @foreach (\App\Models\Cotizacion::ESTADOS as $e)
                <option value="{{ $e }}">{{ ucfirst($e) }}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-6 overflow-x-auto rounded-2xl border-2 border-sky-100 bg-white shadow-sm">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="bg-sky-100 text-sky-900">
                <tr>
                    <th class="px-4 py-3 font-bold">#</th>
                    <th class="px-4 py-3 font-bold">Cliente</th>
                    <th class="px-4 py-3 font-bold">Detalle</th>
                    <th class="px-4 py-3 text-right font-bold">Total</th>
                    <th class="px-4 py-3 font-bold">Estado</th>
                    <th class="px-4 py-3 text-right font-bold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sky-100">
                @forelse ($cotizaciones as $c)
                    <tr wire:key="cot-{{ $c->id }}">
                        <td class="px-4 py-3">{{ $c->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $c->cliente?->nombre }}</p>
                            <p class="text-slate-600">{{ $c->cliente?->telefono }}</p>
                            <p class="text-xs text-slate-500">{{ $c->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $c->sistema?->nombre ?? '—' }}<br>
                            {{ \App\Support\Formato::num($c->ancho) }} × {{ \App\Support\Formato::num($c->alto) }} m · {{ $c->cantidad }} ud
                        </td>
                        <td class="px-4 py-3 text-right font-medium">{{ \App\Support\Formato::dinero($c->precio_estimado) }}</td>
                        <td class="px-4 py-3">
                            <select aria-label="Estado de la cotización {{ $c->id }}"
                                    wire:change="cambiarEstado({{ $c->id }}, $event.target.value)"
                                    class="min-h-11 rounded-full border-2 border-sky-200 bg-white px-3 text-sm font-semibold">
                                @foreach (\App\Models\Cotizacion::ESTADOS as $e)
                                    <option value="{{ $e }}" @selected($c->estado === $e)>{{ ucfirst($e) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <button type="button" wire:click="ver({{ $c->id }})" class="btn-borde">Ver</button>
                                <button type="button" wire:click="eliminar({{ $c->id }})" wire:confirm="¿Eliminar esta cotización?" class="btn-peligro">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-14 text-center text-slate-600">No hay cotizaciones con esos filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $cotizaciones->links() }}</div>

    {{-- ============ DETALLE ============ --}}
    @if ($sel)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <button type="button" wire:click="cerrar" aria-label="Cerrar" class="absolute inset-0 h-full w-full cursor-default bg-slate-900/60"></button>

            <div role="dialog" aria-modal="true" aria-label="Cotización {{ $sel->id }}" class="relative max-h-[92vh] w-full max-w-2xl overflow-auto rounded-3xl bg-white p-8 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.1em] text-slate-600">Cotización #{{ $sel->id }} · {{ $sel->created_at->format('d/m/Y H:i') }}</p>
                        <h2 class="mt-1 text-2xl font-extrabold text-violet-800">{{ $sel->cliente?->nombre }}</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ $sel->cliente?->telefono }} @if ($sel->cliente?->email) · {{ $sel->cliente->email }} @endif
                        </p>
                    </div>
                    <button type="button" wire:click="cerrar" aria-label="Cerrar" class="flex h-11 w-11 flex-none items-center justify-center rounded-full border-2 border-sky-200 hover:border-marca">
                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M5 5l14 14M19 5L5 19"/></svg>
                    </button>
                </div>

                <dl class="mt-6 grid gap-x-6 gap-y-3 border-t border-sky-100 pt-4 text-sm sm:grid-cols-2">
                    <div class="flex justify-between gap-4"><dt class="text-slate-600">Modelo</dt><dd class="text-right font-medium">{{ $sel->proyecto?->nombre ?? 'Sin modelo' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-600">Sistema</dt><dd class="font-medium">{{ $sel->sistema?->nombre ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-600">Medidas</dt><dd class="font-medium">{{ \App\Support\Formato::num($sel->ancho) }} × {{ \App\Support\Formato::num($sel->alto) }} m</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-600">Área · Cantidad</dt><dd class="font-medium">{{ \App\Support\Formato::num($sel->area) }} m² · {{ $sel->cantidad }}</dd></div>
                </dl>

                @if (! empty($sel->detalle['lineas']))
                    <div class="mt-5 divide-y divide-sky-100 border border-sky-100 text-sm">
                        @foreach ($sel->detalle['lineas'] as $l)
                            <div class="flex items-start justify-between gap-4 px-4 py-3">
                                <div>
                                    <p class="font-medium">{{ $l['concepto'] }}</p>
                                    <p class="text-slate-600">{{ $l['detalle'] }}</p>
                                </div>
                                <p class="whitespace-nowrap font-medium">{{ \App\Support\Formato::dinero($l['monto']) }}</p>
                            </div>
                        @endforeach
                        <div class="flex items-center justify-between bg-sky-50 px-4 py-3">
                            <p class="font-semibold">Total estimado</p>
                            <p class="text-xl font-semibold">{{ \App\Support\Formato::dinero($sel->precio_estimado) }}</p>
                        </div>
                    </div>
                @endif

                @if ($sel->mensaje)
                    <div class="mt-5 text-sm">
                        <p class="text-slate-600">Comentarios del cliente</p>
                        <p class="mt-1 whitespace-pre-line">{{ $sel->mensaje }}</p>
                    </div>
                @endif

                <div class="mt-8 flex flex-wrap gap-3">
                    @if ($sel->cliente)
                        <a href="{{ \App\Support\Whatsapp::enlace($sel->cliente->telefono, 'Hola ' . $sel->cliente->nombre . ', te escribimos por tu cotización #' . $sel->id . '.') }}"
                           target="_blank" rel="noopener" class="btn-primario min-h-12 px-6">Responder por WhatsApp</a>
                    @endif
                    <button type="button" wire:click="cerrar" class="btn-borde min-h-12 px-6">Cerrar</button>
                </div>
            </div>
        </div>
    @endif
</div>
