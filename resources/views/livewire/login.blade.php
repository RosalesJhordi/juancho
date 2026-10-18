<div class="relative overflow-hidden bg-violet-50 px-6 py-16">
    <span class="pointer-events-none absolute -left-10 top-10 h-40 w-40 rounded-full bg-yellow-300/70" aria-hidden="true"></span>
    <span class="pointer-events-none absolute -right-10 bottom-10 h-52 w-52 rounded-full bg-pink-300/60" aria-hidden="true"></span>
    <span class="pointer-events-none absolute right-1/4 top-6 h-20 w-20 rounded-full bg-cyan-300/60" aria-hidden="true"></span>

    <div class="relative mx-auto w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="px-8 py-7 text-white" style="background:linear-gradient(120deg,#7C3AED,#EC4899)">
            <span class="inline-flex rounded-full bg-white/20 px-4 py-1 text-sm font-semibold">Panel privado</span>
            <h1 class="mt-3 font-display text-3xl font-extrabold">Iniciar sesión</h1>
        </div>

        <form wire:submit="entrar" class="flex flex-col gap-4 p-8">
            <label class="etiqueta">
                Correo
                <input type="email" wire:model="email" autocomplete="username" class="campo" autofocus>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="etiqueta">
                Clave
                <input type="password" wire:model="password" autocomplete="current-password" class="campo">
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label class="flex items-center gap-3 text-sm font-medium">
                <input type="checkbox" wire:model="recordar" class="h-5 w-5 rounded border-slate-300 text-marca">
                Mantener la sesión iniciada
            </label>

            <button type="submit" wire:loading.attr="disabled" class="btn-primario min-h-12 text-[15px]">
                <span wire:loading.remove wire:target="entrar">Entrar</span>
                <span wire:loading wire:target="entrar">Entrando…</span>
            </button>
        </form>
    </div>
</div>
