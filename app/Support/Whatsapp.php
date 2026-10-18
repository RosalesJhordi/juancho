<?php

namespace App\Support;

class Whatsapp
{
    /** Devuelve solo dígitos, con código de país si el número es local. */
    public static function numero(?string $telefono): string
    {
        $d = ltrim(preg_replace('/\D+/', '', (string) $telefono), '0');

        if ($d === '') {
            return '';
        }

        $pais = preg_replace('/\D+/', '', (string) config('catalogo.codigo_pais'));

        if ($pais !== '' && strlen($d) <= 9) {
            $d = $pais . $d;
        }

        return $d;
    }

    public static function enlace(?string $telefono, string $texto = ''): string
    {
        return 'https://wa.me/' . self::numero($telefono)
            . ($texto !== '' ? '?text=' . rawurlencode($texto) : '');
    }

    /** ¿Está configurado el WhatsApp de la empresa? */
    public static function configurado(): bool
    {
        return self::numero(config('catalogo.whatsapp')) !== '';
    }

    public static function empresa(string $texto = ''): string
    {
        return self::enlace(config('catalogo.whatsapp'), $texto);
    }
}
