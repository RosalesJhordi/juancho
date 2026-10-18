<?php

namespace App\Services;

use App\Models\Opcion;
use App\Models\Sistema;
use App\Support\Formato;

/**
 * Calcula el precio estimado de una cotización.
 *
 *  - Sistema: tarifa vigente del sistema elegido.
 *  - Opciones: vidrio, color, accesorios e instalación (solo las activas y
 *    disponibles para ese sistema).
 *  - Unidades de cálculo: m2 (área), ml (perímetro) o unidad.
 *  - Se cobra como mínimo config('catalogo.area_minima') m² por unidad.
 *
 * Todo se calcula en el servidor con los precios de la base de datos.
 */
class PrecioService
{
    public function calcular(Sistema $sistema, float $ancho, float $alto, int $cantidad, array $opcionIds = []): array
    {
        $cantidad = max(1, $cantidad);
        $area = round($ancho * $alto, 2);
        $areaFacturable = max($area, (float) config('catalogo.area_minima'));
        $perimetro = round(2 * ($ancho + $alto), 2);

        $lineas = [];
        $tarifa = $sistema->tarifaVigente();

        if ($tarifa) {
            $lineas[] = $this->linea(
                'Sistema ' . $sistema->nombre,
                (float) $tarifa->precio,
                $tarifa->unidad_calculo,
                $areaFacturable,
                $perimetro,
                $cantidad
            );
        }

        $ids = array_values(array_filter(array_map('intval', $opcionIds)));

        $opciones = $ids === [] ? collect() : Opcion::query()
            ->activos()
            ->paraSistema($sistema->id)
            ->whereIn('id', $ids)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        foreach ($opciones as $o) {
            $lineas[] = $this->linea(
                (Opcion::TIPOS[$o->tipo] ?? ucfirst($o->tipo)) . ': ' . $o->nombre,
                (float) $o->precio,
                $o->unidad_calculo,
                $areaFacturable,
                $perimetro,
                $cantidad
            );
        }

        return [
            'area'            => $area,
            'area_facturable' => $areaFacturable,
            'perimetro'       => $perimetro,
            'cantidad'        => $cantidad,
            'lineas'          => $lineas,
            'total'           => round(array_sum(array_column($lineas, 'monto')), 2),
            'sin_tarifa'      => $tarifa === null,
            'opciones'        => $opciones
                ->map(fn ($o) => ['id' => $o->id, 'tipo' => $o->tipo, 'nombre' => $o->nombre])
                ->values()
                ->all(),
        ];
    }

    private function linea(string $concepto, float $precio, string $unidad, float $area, float $perimetro, int $cantidad): array
    {
        $uds = $cantidad . ($cantidad === 1 ? ' ud' : ' uds');

        [$monto, $detalle] = match ($unidad) {
            'ml'     => [$precio * $perimetro * $cantidad, Formato::dinero($precio) . ' × ' . Formato::num($perimetro) . ' m × ' . $uds],
            'unidad' => [$precio * $cantidad, Formato::dinero($precio) . ' × ' . $uds],
            default  => [$precio * $area * $cantidad, Formato::dinero($precio) . ' × ' . Formato::num($area) . ' m² × ' . $uds],
        };

        return [
            'concepto' => $concepto,
            'detalle'  => $precio > 0 ? $detalle : 'Incluido',
            'monto'    => round($monto, 2),
        ];
    }
}
