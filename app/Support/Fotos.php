<?php

namespace App\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Las fotos se guardan directamente dentro de la carpeta pública del sitio
 * (public / public_html), en la subcarpeta definida por FOTOS_CARPETA (por defecto "trabajos").
 * No hace falta `php artisan storage:link`.
 */
class Fotos
{
    public static function carpeta(): string
    {
        return trim((string) config('catalogo.fotos_carpeta', 'trabajos'), '/');
    }

    public static function disco(): Filesystem
    {
        $raiz = public_path(self::carpeta());

        // Crea la carpeta la primera vez y la protege para que no se ejecute código ahí.
        if (! File::isDirectory($raiz)) {
            File::makeDirectory($raiz, 0755, true);
        }

        $htaccess = $raiz . DIRECTORY_SEPARATOR . '.htaccess';
        if (! File::exists($htaccess)) {
            File::put($htaccess, "<FilesMatch \"\\.(php|phtml|phar|pl|py|cgi)$\">\n    Require all denied\n</FilesMatch>\nOptions -Indexes\n");
        }

        return Storage::build([
            'driver'     => 'local',
            'root'       => $raiz,
            'url'        => rtrim(asset(self::carpeta()), '/'),
            'visibility' => 'public',
            'throw'      => false,
        ]);
    }
}
