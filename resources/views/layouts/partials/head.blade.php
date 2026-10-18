<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ ($title ?? null) ? $title . ' · ' : '' }}{{ config('catalogo.empresa') }}</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

{{--
    Tailwind por CDN para empezar de inmediato.
    En producción: instala Tailwind con Vite y mueve estas clases a resources/css/app.css
    (ver README, sección "Antes de publicar en internet").
--}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Instrument Sans"', 'system-ui', 'sans-serif'],
                    display: ['"Bricolage Grotesque"', '"Instrument Sans"', 'system-ui', 'sans-serif'],
                },
                // Cambia 'marca' para recolorear botones y enlaces principales de todo el sitio
                colors: { marca: '#2563EB', sol: '#FFC22E' },
            },
        },
    };
</script>
<style type="text/tailwindcss">
    @layer base {
        h1, h2, h3 { font-family: 'Bricolage Grotesque', 'Instrument Sans', system-ui, sans-serif; letter-spacing: -0.02em; }
    }
    @layer components {
        .campo { @apply min-h-11 w-full rounded-xl border-2 border-sky-200 bg-white px-3 text-[15px] text-slate-900 transition focus:border-marca focus:outline-none focus:ring-4 focus:ring-blue-200; }
        textarea.campo { @apply min-h-0 py-2.5; }
        .btn-primario { @apply inline-flex min-h-11 items-center justify-center rounded-full bg-marca px-6 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition hover:-translate-y-0.5 hover:bg-blue-700 disabled:opacity-60; }
        .btn-sol { @apply inline-flex min-h-11 items-center justify-center rounded-full bg-sol px-6 text-sm font-bold text-slate-900 shadow-lg shadow-amber-500/30 transition hover:-translate-y-0.5 hover:brightness-95 disabled:opacity-60; }
        .btn-borde { @apply inline-flex min-h-11 items-center justify-center rounded-full border-2 border-marca bg-white px-5 text-sm font-semibold text-marca transition hover:bg-marca hover:text-white disabled:opacity-60; }
        .btn-peligro { @apply inline-flex min-h-11 items-center justify-center rounded-full border-2 border-rose-300 bg-white px-5 text-sm font-semibold text-rose-600 transition hover:bg-rose-500 hover:text-white; }
        .etiqueta { @apply flex flex-col gap-1.5 text-[13px] font-semibold text-slate-700; }
        .error { @apply text-xs font-medium text-rose-600; }
    }
</style>
<style>[x-cloak] { display: none !important; }</style>

@livewireStyles
