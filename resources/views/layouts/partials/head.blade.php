<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
<meta name="theme-color" content="#ffffff">
<title>{{ ($title ?? null) ? $title . ' · ' : '' }}{{ config('catalogo.empresa') }}</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                // Colores del logo: azul (principal), amarillo y rojo (acentos)
                colors: { marca: '#2563EB', sol: '#FFC22E', rojo: '#E11D2E' },
                keyframes: {
                    subir: { '0%': { opacity: '0', transform: 'translateY(14px)' }, '100%': { opacity: '1', transform: 'none' } },
                    aparecer: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                },
                animation: {
                    subir: 'subir .6s cubic-bezier(.2,.7,.2,1) both',
                    aparecer: 'aparecer .25s ease-out both',
                },
            },
        },
    };
</script>
<style type="text/tailwindcss">
    @layer base {
        h1, h2, h3 { font-family: 'Bricolage Grotesque', 'Instrument Sans', system-ui, sans-serif; letter-spacing: -0.02em; }
        :focus-visible { outline: 2px solid #2563EB; outline-offset: 2px; }
    }
    @layer components {
        /* Formularios */
        .campo { @apply min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3.5 text-[15px] text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-marca focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:bg-slate-100 disabled:text-slate-400; }
        textarea.campo { @apply min-h-0 py-2.5; }
        .etiqueta { @apply flex flex-col gap-1.5 text-[13px] font-semibold text-slate-700; }
        .error { @apply text-xs font-medium text-rose-600; }

        /* Botones */
        .btn-primario { @apply inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-marca px-6 text-sm font-bold text-white shadow-md shadow-blue-600/25 transition hover:-translate-y-0.5 hover:bg-blue-700 disabled:opacity-60; }
        .btn-sol { @apply inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-sol px-6 text-sm font-bold text-slate-900 shadow-md shadow-amber-500/25 transition hover:-translate-y-0.5 hover:brightness-95 disabled:opacity-60; }
        .btn-wa { @apply inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-emerald-600 px-6 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:-translate-y-0.5 hover:bg-emerald-700 disabled:opacity-60; }
        .btn-borde { @apply inline-flex min-h-11 items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:border-marca hover:text-marca disabled:opacity-60; }
        .btn-peligro { @apply inline-flex min-h-11 items-center justify-center gap-2 rounded-full border border-rose-200 bg-white px-5 text-sm font-semibold text-rose-600 transition hover:border-rose-500 hover:bg-rose-500 hover:text-white; }

        /* Panel */
        .tarjeta { @apply rounded-2xl border border-slate-200 bg-white shadow-sm; }
        .titulo-pagina { @apply font-display text-3xl font-extrabold tracking-tight text-slate-900; }
        .subtitulo-pagina { @apply mt-1 text-sm text-slate-500; }
        .tabla-cabecera { @apply bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-500; }

        /* Etiquetas pequeñas */
        .chip { @apply inline-flex min-h-10 flex-none items-center rounded-full px-4 text-sm font-semibold ring-1 transition; }
    }
</style>
<style>
    [x-cloak] { display: none !important; }

    /* Las tres líneas del logo: rojo · amarillo · azul */
    .barra-marca { height: 4px; background: linear-gradient(90deg, #E11D2E 0 33.33%, #FACC15 0 66.66%, #2563EB 0); }

    /* Cuadrícula tenue, como un plano de ventana */
    .cuadricula {
        background-image:
            linear-gradient(to right, rgba(15, 23, 42, .045) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(15, 23, 42, .045) 1px, transparent 1px);
        background-size: 44px 44px;
    }

    .sin-barra { scrollbar-width: none; }
    .sin-barra::-webkit-scrollbar { display: none; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
    }
</style>

@livewireStyles
 @livewireScripts
