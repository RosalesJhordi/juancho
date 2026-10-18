@php $cp = \App\Support\Colores::paso($paso); @endphp

<div>
    <x-encabezado titulo="Cotiza tu proyecto" etiqueta="Cotizador" desde="#10B981" hasta="#2563EB">
        <p class="mt-3 max-w-xl text-lg text-white/90">Cinco pasos y tienes un presupuesto estimado.</p>
    </x-encabezado>

    <div class="mx-auto w-full max-w-3xl px-6 py-10">

        @if ($enviado)
            {{-- ============ CONFIRMACIÓN (cuando no hay WhatsApp configurado) ============ --}}
            <div class="rounded-3xl bg-emerald-50 p-8 text-center ring-2 ring-emerald-200">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500 text-white">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                </span>
                <p class="mt-4 font-display text-2xl font-extrabold">¡Solicitud recibida!</p>
                <p class="mt-2 text-slate-600">Registramos tu solicitud #{{ $cotizacionId }}. Te contactaremos para confirmar medidas, materiales e instalación.</p>
                <a href="{{ route('proyectos') }}" class="btn-primario mt-6 min-h-12">Seguir viendo proyectos</a>
            </div>
        @else

            {{-- ============ INDICADOR DE PASOS ============ --}}
            <ol class="grid grid-cols-5 gap-2" aria-label="Pasos">
                @foreach ($pasos as $n => $nombrePaso)
                    @php $pc = \App\Support\Colores::paso($n); @endphp
                    <li wire:key="paso-{{ $n }}">
                        <button type="button" wire:click="irA({{ $n }})" @disabled($n >= $paso)
                                class="w-full text-left disabled:cursor-default">
                            <span @class(['block h-2.5 rounded-full', $pc['barra'] => $n <= $paso, 'bg-slate-200' => $n > $paso])></span>
                            <span class="mt-2 flex items-center gap-2">
                                <span @class(['flex h-7 w-7 flex-none items-center justify-center rounded-full text-xs font-extrabold',
                                    $pc['circulo'] => $n <= $paso,
                                    'bg-slate-200 text-slate-500' => $n > $paso])>{{ $n }}</span>
                                <span @class(['hidden text-sm font-bold sm:inline',
                                    $pc['texto'] => $n === $paso,
                                    'text-slate-500' => $n !== $paso])>{{ $nombrePaso }}</span>
                            </span>
                        </button>
                    </li>
                @endforeach
            </ol>

            {{-- Modelo elegido (pasos 2 a 5) --}}
            @if ($paso > 1 && $this->proyectoSel)
                <div class="mt-6 flex items-center gap-4 rounded-2xl bg-white p-3 shadow-md ring-2 ring-blue-100">
                    <div class="h-14 w-20 flex-none overflow-hidden rounded-xl bg-slate-100">
                        @if ($this->proyectoSel->portada_url)
                            <img src="{{ $this->proyectoSel->portada_url }}" alt="" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-700">Modelo de referencia</p>
                        <p class="truncate font-bold">{{ $this->proyectoSel->nombre }}</p>
                    </div>
                </div>
            @endif

            {{-- Cada paso tiene su propio color de fondo --}}
            <div class="mt-6 rounded-3xl border-2 p-5 sm:p-8 {{ $cp['suave'] }}">

                {{-- ============ PASO 1 · MODELO ============ --}}
                @if ($paso === 1)
                    <h2 class="font-display text-2xl font-extrabold">Elige un modelo de referencia</h2>
                    <p class="mt-1 text-sm text-slate-600">Es opcional. Si no ves el que buscas, continúa sin modelo.</p>

                    <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        @foreach ($proyectos as $p)
                            <button type="button" wire:key="m-{{ $p->id }}" wire:click="elegirProyecto({{ $p->id }})"
                                    @class(['flex flex-col overflow-hidden rounded-2xl border-2 bg-white text-left shadow-sm transition hover:-translate-y-1 hover:shadow-lg',
                                        'border-marca ring-4 ring-blue-200' => $proyectoId === $p->id,
                                        'border-transparent' => $proyectoId !== $p->id])>
                                <div class="aspect-[4/3] w-full overflow-hidden bg-slate-100">
                                    @if ($p->portada_url)
                                        <img src="{{ $p->portada_url }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                    @else
                                        <x-placeholder class="h-full w-full"/>
                                    @endif
                                </div>
                                <span class="px-3 py-3 text-sm font-bold leading-tight">{{ $p->nombre }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button type="button" wire:click="elegirProyecto(null)" class="btn-borde min-h-12">Continuar sin modelo</button>
                        @if ($proyectoId)
                            <button type="button" wire:click="siguiente" class="btn-primario min-h-12">Continuar</button>
                        @endif
                    </div>
                @endif

                {{-- ============ PASO 2 · SISTEMA ============ --}}
                @if ($paso === 2)
                    <h2 class="font-display text-2xl font-extrabold">Elige el sistema</h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-3">
                        @forelse ($sistemas as $s)
                            @php $cs = \App\Support\Colores::para($s->id); @endphp
                            <button type="button" wire:key="s-{{ $s->id }}" wire:click="elegirSistema({{ $s->id }})"
                                    @class(['flex flex-col gap-2 rounded-2xl border-2 bg-white p-5 text-left shadow-sm transition hover:-translate-y-1 hover:shadow-lg',
                                        'border-marca ring-4 ring-blue-200' => $sistemaId === $s->id,
                                        'border-transparent' => $sistemaId !== $s->id])>
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl text-white {{ $cs['solido'] }}">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M12 4v16M4 12h16"/></svg>
                                </span>
                                <span class="font-display text-lg font-bold">{{ $s->nombre }}</span>
                                @if ($s->descripcion)
                                    <span class="text-sm leading-relaxed text-slate-600">{{ $s->descripcion }}</span>
                                @endif
                            </button>
                        @empty
                            <p class="text-slate-600 sm:col-span-3">Aún no hay sistemas disponibles.</p>
                        @endforelse
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <button type="button" wire:click="irA(1)" class="btn-borde min-h-12">Atrás</button>
                        @if ($sistemaId)
                            <button type="button" wire:click="siguiente" class="btn-primario min-h-12">Continuar</button>
                        @endif
                    </div>
                @endif

                {{-- ============ PASO 3 · MEDIDAS ============ --}}
                @if ($paso === 3)
                    <h2 class="font-display text-2xl font-extrabold">Indica las medidas</h2>
                    <p class="mt-1 text-sm text-slate-600">En metros. Ejemplo: 1.50</p>

                    <div class="mt-5 grid gap-4 rounded-2xl bg-white p-5 shadow-sm sm:grid-cols-3">
                        <label class="etiqueta">
                            Ancho (m)
                            <input type="text" inputmode="decimal" wire:model="ancho" placeholder="1.50" class="campo">
                            @error('ancho') <span class="error">{{ $message }}</span> @enderror
                        </label>
                        <label class="etiqueta">
                            Alto (m)
                            <input type="text" inputmode="decimal" wire:model="alto" placeholder="1.20" class="campo">
                            @error('alto') <span class="error">{{ $message }}</span> @enderror
                        </label>
                        <label class="etiqueta">
                            Cantidad
                            <input type="number" min="1" max="100" wire:model="cantidad" class="campo">
                            @error('cantidad') <span class="error">{{ $message }}</span> @enderror
                        </label>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button type="button" wire:click="irA(2)" class="btn-borde min-h-12">Atrás</button>
                        <button type="button" wire:click="siguiente" class="btn-primario min-h-12">Continuar</button>
                    </div>
                @endif

                {{-- ============ PASO 4 · OPCIONES ============ --}}
                @if ($paso === 4)
                    <h2 class="font-display text-2xl font-extrabold">Elige las opciones</h2>
                    <p class="mt-1 text-sm text-slate-600">Según disponibilidad para el sistema {{ $this->sistema?->nombre }}.</p>

                    @php
                        $unidades = ['m2' => 'm²', 'ml' => 'metro lineal', 'unidad' => 'unidad'];
                        $colorTipo = [
                            'vidrio'      => 'bg-cyan-100 text-cyan-900',
                            'color'       => 'bg-pink-100 text-pink-800',
                            'accesorio'   => 'bg-violet-100 text-violet-800',
                            'instalacion' => 'bg-emerald-100 text-emerald-800',
                        ];
                    @endphp

                    <div class="mt-5 flex flex-col gap-5">
                        @foreach (\App\Models\Opcion::TIPOS as $tipo => $titulo)
                            @php $lista = $opcionesDisponibles->get($tipo, collect()); @endphp

                            @if ($lista->isNotEmpty())
                                <fieldset wire:key="grupo-{{ $tipo }}" class="rounded-2xl bg-white p-5 shadow-sm">
                                    <legend class="rounded-full px-3 py-1 text-sm font-bold {{ $colorTipo[$tipo] }}">{{ $titulo }}</legend>
                                    <div class="mt-2 flex flex-col">
                                        @if ($tipo !== 'accesorio')
                                            <label class="flex min-h-11 items-center gap-3 text-[15px]">
                                                <input type="radio" wire:model="unica.{{ $tipo }}" value="" class="h-5 w-5 text-marca">
                                                Ninguno
                                            </label>
                                        @endif

                                        @foreach ($lista as $o)
                                            <label wire:key="op-{{ $o->id }}" class="flex min-h-11 items-center justify-between gap-3 text-[15px]">
                                                <span class="flex items-center gap-3">
                                                    @if ($tipo === 'accesorio')
                                                        <input type="checkbox" wire:model="accesorios" value="{{ $o->id }}" class="h-5 w-5 rounded text-marca">
                                                    @else
                                                        <input type="radio" wire:model="unica.{{ $tipo }}" value="{{ $o->id }}" class="h-5 w-5 text-marca">
                                                    @endif
                                                    {{ $o->nombre }}
                                                </span>
                                                <span class="text-sm font-semibold text-slate-500">
                                                    {{ $o->precio > 0 ? '+ ' . \App\Support\Formato::dinero($o->precio) . ' / ' . ($unidades[$o->unidad_calculo] ?? '') : 'Incluido' }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </fieldset>
                            @endif
                        @endforeach

                        @if ($opcionesDisponibles->isEmpty())
                            <p class="text-slate-600">No hay opciones adicionales para este sistema. Puedes continuar.</p>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button type="button" wire:click="irA(3)" class="btn-borde min-h-12">Atrás</button>
                        <button type="button" wire:click="siguiente" class="btn-primario min-h-12">Ver presupuesto</button>
                    </div>
                @endif

                {{-- ============ PASO 5 · PRESUPUESTO ============ --}}
                @if ($paso === 5)
                    @php $r = $this->resultado; @endphp

                    @if (! $r)
                        <p class="text-slate-600">Revisa las medidas para calcular el presupuesto.</p>
                        <button type="button" wire:click="irA(3)" class="btn-borde mt-4 min-h-12">Volver a medidas</button>
                    @else
                        <h2 class="font-display text-2xl font-extrabold">Tu presupuesto estimado</h2>

                        <div class="mt-5 overflow-hidden rounded-2xl bg-white shadow-lg">
                            <div class="px-6 py-5 text-white" style="background:linear-gradient(120deg,#10B981,#2563EB)">
                                <p class="text-sm font-semibold text-white/90">Total estimado</p>
                                <p class="font-display text-4xl font-extrabold">{{ $r['sin_tarifa'] ? 'Por confirmar' : \App\Support\Formato::dinero($r['total']) }}</p>
                            </div>

                            <dl class="grid gap-x-6 gap-y-3 border-b border-slate-100 p-5 text-sm sm:grid-cols-2">
                                <div class="flex justify-between gap-4"><dt class="text-slate-500">Sistema</dt><dd class="font-bold">{{ $this->sistema->nombre }}</dd></div>
                                <div class="flex justify-between gap-4"><dt class="text-slate-500">Medidas</dt><dd class="font-bold">{{ \App\Support\Formato::num($ancho) }} × {{ \App\Support\Formato::num($alto) }} m</dd></div>
                                <div class="flex justify-between gap-4"><dt class="text-slate-500">Área</dt><dd class="font-bold">{{ \App\Support\Formato::num($r['area']) }} m²</dd></div>
                                <div class="flex justify-between gap-4"><dt class="text-slate-500">Cantidad</dt><dd class="font-bold">{{ $r['cantidad'] }}</dd></div>
                            </dl>

                            @if ($r['area_facturable'] > $r['area'])
                                <p class="bg-amber-50 px-5 py-3 text-sm font-medium text-amber-900">
                                    Se cobra un mínimo de {{ \App\Support\Formato::num($r['area_facturable']) }} m² por unidad.
                                </p>
                            @endif

                            <div class="divide-y divide-slate-100">
                                @forelse ($r['lineas'] as $l)
                                    <div class="flex items-start justify-between gap-4 px-5 py-3 text-sm">
                                        <div>
                                            <p class="font-bold">{{ $l['concepto'] }}</p>
                                            <p class="text-slate-500">{{ $l['detalle'] }}</p>
                                        </div>
                                        <p class="whitespace-nowrap font-bold text-blue-700">{{ \App\Support\Formato::dinero($l['monto']) }}</p>
                                    </div>
                                @empty
                                    <p class="px-5 py-4 text-sm text-slate-600">El precio de este sistema se confirma por WhatsApp.</p>
                                @endforelse
                            </div>
                        </div>

                        <p class="mt-3 rounded-xl bg-amber-100 px-4 py-3 text-sm font-medium text-amber-900">El precio es referencial hasta confirmar medidas, materiales e instalación.</p>

                        {{-- Datos de contacto --}}
                        <form wire:submit="enviar" class="mt-6 flex flex-col gap-4 rounded-2xl bg-white p-6 shadow-lg">
                            <p class="font-display text-lg font-bold">Tus datos</p>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="etiqueta">
                                    Nombre
                                    <input type="text" wire:model="nombre" autocomplete="name" class="campo">
                                    @error('nombre') <span class="error">{{ $message }}</span> @enderror
                                </label>
                                <label class="etiqueta">
                                    Teléfono
                                    <input type="tel" wire:model="telefono" autocomplete="tel" class="campo">
                                    @error('telefono') <span class="error">{{ $message }}</span> @enderror
                                </label>
                            </div>

                            <label class="etiqueta">
                                Correo (opcional)
                                <input type="email" wire:model="email" autocomplete="email" class="campo">
                                @error('email') <span class="error">{{ $message }}</span> @enderror
                            </label>

                            <label class="etiqueta">
                                Comentarios (opcional)
                                <textarea wire:model="mensaje" rows="3" placeholder="Ubicación, fechas, dudas…" class="campo"></textarea>
                                @error('mensaje') <span class="error">{{ $message }}</span> @enderror
                            </label>

                            <div class="flex flex-wrap gap-3">
                                <button type="button" wire:click="irA(4)" class="btn-borde min-h-12">Atrás</button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="enviar"
                                        class="inline-flex min-h-12 flex-1 items-center justify-center rounded-full bg-emerald-500 px-6 text-[15px] font-bold text-slate-900 shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5 hover:bg-emerald-400 disabled:opacity-60">
                                    <span wire:loading.remove wire:target="enviar">{{ $hayWhatsapp ? 'Enviar por WhatsApp' : 'Enviar solicitud' }}</span>
                                    <span wire:loading wire:target="enviar">Enviando…</span>
                                </button>
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        @endif
    </div>
</div>
