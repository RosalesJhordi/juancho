<?php

namespace App\Support;

/**
 * Paleta de colores del sitio.
 * Las clases están escritas completas (no armadas con texto) para que Tailwind las detecte
 * al compilar. Si usas Vite, agrega './app/**\/*.php' a "content" en tailwind.config.js.
 */
final class Colores
{
    /** Un color por categoría (se reparten según el id). */
    private const PALETA = [
        ['solido' => 'bg-blue-600',    'suave' => 'bg-blue-100 text-blue-800',       'borde' => 'border-blue-200 hover:border-blue-500',       'texto' => 'text-blue-700',    'sombra' => 'shadow-blue-600/30'],
        ['solido' => 'bg-emerald-600', 'suave' => 'bg-emerald-100 text-emerald-800', 'borde' => 'border-emerald-200 hover:border-emerald-500', 'texto' => 'text-emerald-700', 'sombra' => 'shadow-emerald-600/30'],
        ['solido' => 'bg-orange-600',  'suave' => 'bg-orange-100 text-orange-800',   'borde' => 'border-orange-200 hover:border-orange-500',   'texto' => 'text-orange-700',  'sombra' => 'shadow-orange-600/30'],
        ['solido' => 'bg-violet-600',  'suave' => 'bg-violet-100 text-violet-800',   'borde' => 'border-violet-200 hover:border-violet-500',   'texto' => 'text-violet-700',  'sombra' => 'shadow-violet-600/30'],
        ['solido' => 'bg-pink-600',    'suave' => 'bg-pink-100 text-pink-800',       'borde' => 'border-pink-200 hover:border-pink-500',       'texto' => 'text-pink-700',    'sombra' => 'shadow-pink-600/30'],
        ['solido' => 'bg-cyan-600',    'suave' => 'bg-cyan-100 text-cyan-900',       'borde' => 'border-cyan-200 hover:border-cyan-500',       'texto' => 'text-cyan-700',    'sombra' => 'shadow-cyan-600/30'],
    ];

    /** Un color por paso del cotizador. */
    private const PASOS = [
        ['barra' => 'bg-blue-600',    'circulo' => 'bg-blue-600 text-white',    'texto' => 'text-blue-700',    'suave' => 'bg-blue-50 border-blue-200'],
        ['barra' => 'bg-violet-600',  'circulo' => 'bg-violet-600 text-white',  'texto' => 'text-violet-700',  'suave' => 'bg-violet-50 border-violet-200'],
        ['barra' => 'bg-pink-600',    'circulo' => 'bg-pink-600 text-white',    'texto' => 'text-pink-700',    'suave' => 'bg-pink-50 border-pink-200'],
        ['barra' => 'bg-orange-600',  'circulo' => 'bg-orange-600 text-white',  'texto' => 'text-orange-700',  'suave' => 'bg-orange-50 border-orange-200'],
        ['barra' => 'bg-emerald-600', 'circulo' => 'bg-emerald-600 text-white', 'texto' => 'text-emerald-700', 'suave' => 'bg-emerald-50 border-emerald-200'],
    ];

    public static function para(?int $id): array
    {
        return self::PALETA[((int) $id) % count(self::PALETA)];
    }

    public static function paso(int $n): array
    {
        return self::PASOS[max(0, $n - 1) % count(self::PASOS)];
    }
}
