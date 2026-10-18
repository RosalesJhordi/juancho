<div>
    <a href="{{ route('admin.proyectos') }}" class="text-sm text-slate-600 hover:text-slate-900">← Volver a proyectos</a>
    <h1 class="mt-2 text-3xl font-extrabold text-violet-800">{{ $proyectoId ? 'Editar proyecto' : 'Nuevo proyecto' }}</h1>

    <form wire:submit="guardar" class="mt-8 grid gap-8 xl:grid-cols-[1fr_420px]">

        {{-- ============ DATOS ============ --}}
        <div class="flex flex-col gap-4 rounded-2xl border-2 border-sky-100 bg-white shadow-sm p-6">
            <label class="etiqueta">
                Nombre del proyecto
                <input type="text" wire:model="form.nombre" placeholder="Ej. Ventanal corredizo en sala" class="campo">
                @error('form.nombre') <span class="error">{{ $message }}</span> @enderror
            </label>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="etiqueta">
                    Categoría
                    <select wire:model="form.categoria_id" class="campo">
                        <option value="">Sin categoría</option>
                        @foreach ($categorias as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    @error('form.categoria_id') <span class="error">{{ $message }}</span> @enderror
                </label>

                <label class="etiqueta">
                    Sistema
                    <select wire:model="form.sistema_id" class="campo">
                        <option value="">Sin sistema</option>
                        @foreach ($sistemas as $s)
                            <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                    @error('form.sistema_id') <span class="error">{{ $message }}</span> @enderror
                </label>

                <label class="etiqueta">
                    Color de aluminio
                    <input type="text" wire:model="form.color" placeholder="Ej. Negro" class="campo">
                    @error('form.color') <span class="error">{{ $message }}</span> @enderror
                </label>

                <label class="etiqueta">
                    Vidrio
                    <input type="text" wire:model="form.vidrio" placeholder="Ej. Templado 8 mm" class="campo">
                    @error('form.vidrio') <span class="error">{{ $message }}</span> @enderror
                </label>

                <label class="etiqueta">
                    Medidas
                    <input type="text" wire:model="form.medidas" placeholder="Ej. 2.40 × 1.50 m" class="campo">
                    @error('form.medidas') <span class="error">{{ $message }}</span> @enderror
                </label>

                <label class="etiqueta">
                    Ubicación
                    <input type="text" wire:model="form.ubicacion" class="campo">
                    @error('form.ubicacion') <span class="error">{{ $message }}</span> @enderror
                </label>

                <label class="etiqueta">
                    Año
                    <input type="text" inputmode="numeric" maxlength="4" wire:model="form.anio" class="campo">
                    @error('form.anio') <span class="error">{{ $message }}</span> @enderror
                </label>

                <label class="etiqueta">
                    Estado
                    <select wire:model="form.estado" class="campo">
                        @foreach (\App\Models\Proyecto::ESTADOS as $valor => $texto)
                            <option value="{{ $valor }}">{{ $texto }}</option>
                        @endforeach
                    </select>
                    @error('form.estado') <span class="error">{{ $message }}</span> @enderror
                </label>
            </div>

            <label class="etiqueta">
                Descripción
                <textarea wire:model="form.descripcion" rows="5" class="campo"></textarea>
                @error('form.descripcion') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" wire:model="form.destacado" class="h-5 w-5 rounded border-sky-300 text-marca">
                Mostrar como destacado en la página de inicio
            </label>
        </div>

        {{-- ============ FOTOS ============ --}}
        <div class="flex flex-col gap-5 self-start rounded-2xl border-2 border-sky-100 bg-white shadow-sm p-6">
            <div>
                <p class="font-semibold">Fotos</p>
                <p class="mt-1 text-sm text-slate-600">Puedes elegir varias a la vez (JPG, PNG o WEBP · máx. 8 MB cada una). La primera será la portada.</p>
            </div>

            <label class="relative flex min-h-24 cursor-pointer flex-col items-center justify-center gap-1 rounded-2xl border-2 border-dashed border-violet-300 bg-violet-50 px-4 py-6 text-center text-sm font-semibold text-violet-800 hover:border-violet-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                <span class="font-medium">Seleccionar fotos</span>
                <input type="file" wire:model="nuevasFotos" multiple accept="image/jpeg,image/png,image/webp"
                       class="absolute inset-0 h-full w-full cursor-pointer opacity-0" aria-label="Seleccionar fotos">
            </label>

            <p wire:loading wire:target="nuevasFotos" class="text-sm text-slate-600">Cargando fotos…</p>

            @error('nuevasFotos') <p class="error">{{ $message }}</p> @enderror
            @error('nuevasFotos.*') <p class="error">{{ $message }}</p> @enderror

            @if ($nuevasFotos)
                <div>
                    <p class="text-sm font-medium">Por guardar ({{ count($nuevasFotos) }})</p>
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @foreach ($nuevasFotos as $i => $foto)
                            <div wire:key="nueva-{{ $i }}" class="relative aspect-square overflow-hidden rounded-xl bg-slate-100">
                                @if ($foto->isPreviewable())
                                    <img src="{{ $foto->temporaryUrl() }}" alt="" class="h-full w-full object-cover">
                                @endif
                                <button type="button" wire:click="quitarNueva({{ $i }})" aria-label="Quitar foto"
                                        class="absolute right-1 top-1 flex h-9 w-9 items-center justify-center bg-white text-slate-900 shadow">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M5 5l14 14M19 5L5 19"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($fotos->isNotEmpty())
                <div>
                    <p class="text-sm font-medium">Fotos guardadas ({{ $fotos->count() }})</p>
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        @foreach ($fotos as $f)
                            <div wire:key="foto-{{ $f->id }}" class="flex flex-col gap-2">
                                <div class="relative aspect-[4/3] overflow-hidden rounded-xl bg-slate-100">
                                    <img src="{{ $f->url }}" alt="" class="h-full w-full object-cover">
                                    @if ($f->portada)
                                        <span class="absolute left-1 top-1 rounded-full bg-violet-600 px-2.5 py-0.5 text-xs font-bold text-white">Portada</span>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    @unless ($f->portada)
                                        <button type="button" wire:click="hacerPortada({{ $f->id }})" class="btn-borde flex-1 px-2 text-xs">Portada</button>
                                    @endunless
                                    <button type="button" wire:click="eliminarFoto({{ $f->id }})" wire:confirm="¿Eliminar esta foto?" class="btn-peligro flex-1 px-2 text-xs">Eliminar</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ============ ACCIONES ============ --}}
        <div class="flex flex-wrap gap-3 xl:col-span-2">
            <button type="submit" wire:loading.attr="disabled" wire:target="guardar,nuevasFotos" class="btn-primario min-h-12 px-8 text-[15px]">
                <span wire:loading.remove wire:target="guardar">Guardar proyecto</span>
                <span wire:loading wire:target="guardar">Guardando…</span>
            </button>
            <a href="{{ route('admin.proyectos') }}" class="btn-borde min-h-12 px-8">Cancelar</a>
        </div>
    </form>
</div>
