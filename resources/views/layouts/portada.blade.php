<!DOCTYPE html>
<html lang="es">
<head>
    @include('layouts.partials.head')
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-blue-50 font-sans text-slate-900 antialiased">
<div class="barra-marca fixed inset-x-0 top-0 z-20"></div>

<main class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-6 py-14">
    {{-- Cuadrícula de plano que se desvanece hacia los bordes --}}
    <div class="cuadricula pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_75%)]" aria-hidden="true"></div>
    {{-- Manchas suaves con los colores del logo --}}
    <span class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-red-200/50 blur-3xl" aria-hidden="true"></span>
    <span class="pointer-events-none absolute -right-20 top-1/3 h-80 w-80 rounded-full bg-blue-200/60 blur-3xl" aria-hidden="true"></span>
    <span class="pointer-events-none absolute -bottom-24 left-1/4 h-64 w-64 rounded-full bg-yellow-200/60 blur-3xl" aria-hidden="true"></span>

    {{ $slot }}
</main>

@livewireScripts
</body>
</html>
