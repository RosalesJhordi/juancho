<div>
    <x-aviso/>

    {{-- Bienvenida --}}
    <section class="relative overflow-hidden rounded-3xl p-8 text-white shadow-xl" style="background:linear-gradient(120deg,#7C3AED,#EC4899)">
        <span class="pointer-events-none absolute -right-8 -top-10 h-44 w-44 rounded-full bg-yellow-300/70" aria-hidden="true"></span>
        <span class="pointer-events-none absolute -bottom-12 right-40 h-28 w-28 rounded-full bg-cyan-300/60" aria-hidden="true"></span>

        <div class="relative">
            <h1 class="font-display text-3xl font-extrabold sm:text-4xl">Hola, {{ auth()->user()->name }}</h1>
            <p class="mt-2 max-w-lg text-white/90">Así van tu catálogo y las solicitudes de cotización.</p>

            <div class="mt-6 flex flex-wrap gap-3">
                @if (auth()->user()->puede('proyectos'))
                    <a href="{{ route('admin.proyectos.crear') }}" class="btn-sol min-h-12 px-6">Subir un proyecto</a>
                @endif
                <a href="{{ route('inicio') }}" target="_blank" class="inline-flex min-h-12 items-center justify-center rounded-full border-2 border-white px-6 text-sm font-bold text-white transition hover:bg-white hover:text-violet-700">Ver sitio público</a>
            </div>
        </div>
    </section>

    {{-- Números --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.proyectos', ['estado' => 'publicado']) }}" class="relative overflow-hidden rounded-2xl bg-blue-600 p-6 text-white shadow-lg shadow-blue-600/30 transition hover:-translate-y-1">
            <span class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/15" aria-hidden="true"></span>
            <p class="relative text-sm font-semibold text-blue-100">Proyectos publicados</p>
            <p class="relative mt-2 font-display text-5xl font-extrabold">{{ $publicados }}</p>
        </a>
        <a href="{{ route('admin.proyectos', ['estado' => 'borrador']) }}" class="relative overflow-hidden rounded-2xl bg-yellow-400 p-6 text-slate-900 shadow-lg shadow-yellow-500/30 transition hover:-translate-y-1">
            <span class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/40" aria-hidden="true"></span>
            <p class="relative text-sm font-bold text-slate-800">Borradores</p>
            <p class="relative mt-2 font-display text-5xl font-extrabold">{{ $borradores }}</p>
        </a>
        <a href="{{ route('admin.cotizaciones', ['estado' => 'nueva']) }}" class="relative overflow-hidden rounded-2xl bg-pink-600 p-6 text-white shadow-lg shadow-pink-600/30 transition hover:-translate-y-1">
            <span class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/15" aria-hidden="true"></span>
            <p class="relative text-sm font-semibold text-pink-100">Cotizaciones nuevas</p>
            <p class="relative mt-2 font-display text-5xl font-extrabold">{{ $nuevas }}</p>
        </a>
        <div class="relative overflow-hidden rounded-2xl bg-emerald-600 p-6 text-white shadow-lg shadow-emerald-600/30">
            <span class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/15" aria-hidden="true"></span>
            <p class="relative text-sm font-semibold text-emerald-100">Clientes</p>
            <p class="relative mt-2 font-display text-5xl font-extrabold">{{ $clientes }}</p>
        </div>
    </div>

    @if ($sinTarifa->isNotEmpty())
        <div class="mt-6 rounded-2xl border-2 border-amber-300 bg-amber-100 p-5 text-sm" role="alert">
            <p class="font-display text-base font-extrabold text-amber-900">Falta configurar tarifas</p>
            <p class="mt-1 text-amber-900">
                El cotizador no puede calcular el precio de:
                <strong>{{ $sinTarifa->pluck('nombre')->join(', ') }}</strong>.
                @if (auth()->user()->puede('tarifas'))
                    <a href="{{ route('admin.tarifas') }}" class="font-bold underline underline-offset-4">Configurar tarifas</a>
                @endif
            </p>
        </div>
    @endif

    <section class="mt-10">
        <h2 class="font-display text-2xl font-extrabold">Últimas cotizaciones</h2>
        <div class="mt-2 h-1.5 w-16 rounded-full bg-orange-500"></div>

        <div class="mt-5 overflow-x-auto rounded-2xl border-2 border-sky-100 bg-white shadow-sm">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="bg-sky-100 text-sky-900">
                    <tr>
                        <th class="px-4 py-3 font-bold">#</th>
                        <th class="px-4 py-3 font-bold">Cliente</th>
                        <th class="px-4 py-3 font-bold">Sistema</th>
                        <th class="px-4 py-3 text-right font-bold">Total</th>
                        <th class="px-4 py-3 font-bold">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-100">
                    @forelse ($ultimas as $c)
                        <tr wire:key="ult-{{ $c->id }}" class="transition hover:bg-sky-50">
                            <td class="px-4 py-3 font-bold text-slate-500">{{ $c->id }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $c->cliente?->nombre }}<span class="block text-xs font-normal text-slate-500">{{ $c->created_at->format('d/m/Y H:i') }}</span></td>
                            <td class="px-4 py-3">{{ $c->sistema?->nombre ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-bold text-blue-700">{{ \App\Support\Formato::dinero($c->precio_estimado) }}</td>
                            <td class="px-4 py-3"><x-estado-badge :estado="$c->estado"/></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-slate-600">Todavía no hay cotizaciones.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
