<?php

return [
    'empresa'     => env('CATALOGO_EMPRESA', 'RJ ENTERPRISES'),
    'descripcion' => env('CATALOGO_DESCRIPCION', 'Fabricación e instalación de ventanas, puertas, mamparas y divisiones de vidrio y aluminio.'),

    // Número de WhatsApp de la empresa. Si falta el código de país se agrega 'codigo_pais'.
    'whatsapp'    => env('CATALOGO_WHATSAPP', ''),
    'codigo_pais' => env('CATALOGO_CODIGO_PAIS', '51'),

    'ciudad'      => env('CATALOGO_CIUDAD', ''),
    'telefono'    => env('CATALOGO_TELEFONO', ''),
    'email'       => env('CATALOGO_EMAIL', ''),

    // Carpeta (dentro de public / public_html) donde se guardan las fotos de los trabajos
    'fotos_carpeta' => env('FOTOS_CARPETA', 'trabajos'),

    // Cotizador
    'moneda'      => env('CATALOGO_MONEDA', 'S/'),
    'area_minima' => (float) env('CATALOGO_AREA_MINIMA', 1), // m² mínimos que se cobran por unidad
];
