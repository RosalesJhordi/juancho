<?php

namespace App\Support;

class Formato
{
    public static function dinero(float|int|string|null $n): string
    {
        return config('catalogo.moneda') . ' ' . number_format((float) $n, 2, '.', ',');
    }

    public static function num(float|int|string|null $n, int $decimales = 2): string
    {
        return number_format((float) $n, $decimales, '.', ',');
    }
}
