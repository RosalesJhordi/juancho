<?php

namespace App\Support;

use App\Models\Proyecto;

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

    /**
     * Mensaje que se envía a la empresa cuando el cliente cotiza una foto.
     * Lleva categoría, subcategoría, datos del trabajo y el enlace a la foto elegida.
     */
    public static function textoConsulta(Proyecto $proyecto, ?string $fotoUrl = null): string
    {
        $lineas = ['Hola ' . config('catalogo.empresa') . ', quiero cotizar este trabajo:', ''];

        $datos = [
            'Categoría'    => $proyecto->categoria?->nombre,
            'Modelo' => $proyecto->subcategoria?->nombre,
            'Color'        => $proyecto->color,
            'Vidrio'       => $proyecto->vidrio,
        ];

        foreach (array_filter($datos) as $etiqueta => $valor) {
            $lineas[] = "• {$etiqueta}: {$valor}";
        }

        if ($fotoUrl) {
            $lineas[] = '• Foto: ' . $fotoUrl;
        }

        $lineas[] = '';
        $lineas[] = '¿Me pueden dar precio y disponibilidad?';

        return implode("\n", $lineas);
    }

    /** Enlace de WhatsApp con el mensaje anterior (vacío si el número no está configurado). */
    public static function consulta(Proyecto $proyecto, ?string $fotoUrl = null): string
    {
        return self::configurado() ? self::empresa(self::textoConsulta($proyecto, $fotoUrl)) : '';
    }

    public static function asesoria(): string
    {
        return self::configurado()
            ? self::empresa('Hola ' . config('catalogo.empresa') . ', quiero pedir asesoría y contratar un servicio.')
            : '';
    }
}
