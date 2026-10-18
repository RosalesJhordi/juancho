<?php

namespace App\Livewire;

use App\Livewire\Concerns\MensajesEs;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Opcion;
use App\Models\Proyecto;
use App\Models\Sistema;
use App\Services\PrecioService;
use App\Support\Formato;
use App\Support\Whatsapp;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Cotizador en 5 pasos:
 * 1 Modelo · 2 Sistema · 3 Medidas · 4 Opciones · 5 Presupuesto + envío por WhatsApp
 */
#[Layout('layouts.publico')]
#[Title('Cotizar')]
class Cotizador extends Component
{
    use MensajesEs;

    private const PASOS = [1 => 'Modelo', 2 => 'Sistema', 3 => 'Medidas', 4 => 'Opciones', 5 => 'Presupuesto'];

    public int $paso = 1;

    public ?int $proyectoId = null;
    public ?int $sistemaId = null;

    public string $ancho = '';
    public string $alto = '';
    public string $cantidad = '1';

    /** Una opción por tipo (ids como texto; '' = ninguna). */
    public array $unica = ['vidrio' => '', 'color' => '', 'instalacion' => ''];
    public array $accesorios = [];

    public string $nombre = '';
    public string $telefono = '';
    public string $email = '';
    public string $mensaje = '';

    public bool $enviado = false;
    public ?int $cotizacionId = null;

    public function mount(): void
    {
        // /cotizar?proyecto=slug llega desde la ficha de un proyecto
        $slug = request()->query('proyecto');

        if (! is_string($slug) || $slug === '') {
            return;
        }

        $proyecto = Proyecto::publicados()->where('slug', $slug)->first();

        if (! $proyecto) {
            return;
        }

        $this->proyectoId = $proyecto->id;

        if ($proyecto->sistema_id && Sistema::activos()->whereKey($proyecto->sistema_id)->exists()) {
            $this->sistemaId = $proyecto->sistema_id;
            $this->paso = 3;
        } else {
            $this->paso = 2;
        }
    }

    /* ---------------------------------------------------------------- navegación */

    public function elegirProyecto(?int $id = null): void
    {
        $this->proyectoId = null;

        if ($id) {
            $proyecto = Proyecto::publicados()->find($id);

            if ($proyecto) {
                $this->proyectoId = $proyecto->id;

                if ($proyecto->sistema_id && Sistema::activos()->whereKey($proyecto->sistema_id)->exists()) {
                    $this->cambiarSistema($proyecto->sistema_id);
                }
            }
        }

        $this->paso = 2;
    }

    public function elegirSistema(int $id): void
    {
        if (! Sistema::activos()->whereKey($id)->exists()) {
            return;
        }

        $this->cambiarSistema($id);
        $this->paso = 3;
    }

    public function siguiente(): void
    {
        if ($this->paso >= 3 && ! $this->sistema) {
            $this->paso = 2;

            return;
        }

        if ($this->paso === 3) {
            $this->ancho = str_replace(',', '.', trim($this->ancho));
            $this->alto = str_replace(',', '.', trim($this->alto));

            $this->validate([
                'ancho'    => ['required', 'numeric', 'min:0.3', 'max:10'],
                'alto'     => ['required', 'numeric', 'min:0.3', 'max:10'],
                'cantidad' => ['required', 'integer', 'min:1', 'max:100'],
            ]);
        }

        $this->paso = min(5, $this->paso + 1);
    }

    public function irA(int $n): void
    {
        if ($n >= 1 && $n < $this->paso) {
            $this->paso = $n;
        }
    }

    private function cambiarSistema(int $id): void
    {
        if ($this->sistemaId !== $id) {
            $this->sistemaId = $id;
            $this->unica = ['vidrio' => '', 'color' => '', 'instalacion' => ''];
            $this->accesorios = [];
        }
    }

    /* ---------------------------------------------------------------- datos */

    #[Computed]
    public function sistema(): ?Sistema
    {
        return $this->sistemaId ? Sistema::activos()->find($this->sistemaId) : null;
    }

    #[Computed]
    public function proyectoSel(): ?Proyecto
    {
        return $this->proyectoId ? Proyecto::publicados()->with('fotos')->find($this->proyectoId) : null;
    }

    #[Computed]
    public function opcionesDisponibles()
    {
        return Opcion::activos()
            ->paraSistema($this->sistemaId)
            ->orderBy('nombre')
            ->get()
            ->groupBy('tipo');
    }

    #[Computed]
    public function resultado(): ?array
    {
        if (! $this->sistema || ! is_numeric($this->ancho) || ! is_numeric($this->alto)) {
            return null;
        }

        return app(PrecioService::class)->calcular(
            $this->sistema,
            (float) $this->ancho,
            (float) $this->alto,
            (int) $this->cantidad,
            $this->opcionIds()
        );
    }

    private function opcionIds(): array
    {
        return array_values(array_filter(array_map(
            'intval',
            array_merge(array_values($this->unica), $this->accesorios)
        )));
    }

    /* ---------------------------------------------------------------- envío */

    public function enviar()
    {
        $this->validate([
            'nombre'   => ['required', 'string', 'min:2', 'max:120'],
            'telefono' => ['required', 'string', 'min:6', 'max:30'],
            'email'    => ['nullable', 'email', 'max:150'],
            'mensaje'  => ['nullable', 'string', 'max:1000'],
        ]);

        $limite = 'cotizar:' . request()->ip();

        if (RateLimiter::tooManyAttempts($limite, 5)) {
            $this->addError('nombre', 'Enviaste varias solicitudes seguidas. Intenta de nuevo en unos minutos.');

            return;
        }

        $res = $this->resultado;

        if (! $this->sistema || ! $res) {
            $this->paso = $this->sistema ? 3 : 2;

            return;
        }

        RateLimiter::hit($limite, 600);

        // El precio se recalcula aquí con los datos de la base: nunca se confía en el navegador.
        $cotizacion = DB::transaction(function () use ($res) {
            $cliente = Cliente::firstOrCreate(
                ['telefono' => trim($this->telefono)],
                ['nombre' => trim($this->nombre), 'email' => $this->email ?: null]
            );

            return Cotizacion::create([
                'proyecto_id'     => $this->proyectoSel?->id,
                'cliente_id'      => $cliente->id,
                'sistema_id'      => $this->sistema->id,
                'ancho'           => (float) $this->ancho,
                'alto'            => (float) $this->alto,
                'cantidad'        => (int) $this->cantidad,
                'area'            => $res['area'],
                'opciones'        => array_column($res['opciones'], 'id'),
                'detalle'         => Arr::only($res, ['area', 'area_facturable', 'perimetro', 'cantidad', 'lineas', 'total', 'sin_tarifa']),
                'precio_estimado' => $res['total'],
                'estado'          => 'nueva',
                'mensaje'         => $this->mensaje ?: null,
            ]);
        });

        $this->cotizacionId = $cotizacion->id;

        if (! Whatsapp::configurado()) {
            $this->enviado = true;

            return;
        }

        $this->redirect(Whatsapp::empresa($this->textoWhatsapp($cotizacion, $res)));
    }

    private function textoWhatsapp(Cotizacion $c, array $res): string
    {
        $lineas = [
            "Hola, quiero cotizar (solicitud #{$c->id}).",
            $this->proyectoSel ? 'Modelo: ' . $this->proyectoSel->nombre : null,
            'Sistema: ' . $this->sistema->nombre,
            'Medidas: ' . Formato::num($c->ancho) . ' × ' . Formato::num($c->alto) . ' m (' . Formato::num($res['area']) . ' m²) · ' . $res['cantidad'] . ' ud',
            $res['opciones'] ? 'Opciones: ' . implode(', ', array_column($res['opciones'], 'nombre')) : null,
            $res['sin_tarifa'] ? 'Precio: por confirmar' : 'Precio estimado: ' . Formato::dinero($res['total']),
            'Nombre: ' . $this->nombre,
            'Teléfono: ' . $this->telefono,
            $this->mensaje ? 'Comentarios: ' . $this->mensaje : null,
        ];

        return implode("\n", array_filter($lineas));
    }

    public function render()
    {
        return view('livewire.cotizador', [
            'pasos'               => self::PASOS,
            'proyectos'           => $this->paso === 1
                ? Proyecto::publicados()->with('fotos')->orderByDesc('destacado')->latest()->take(12)->get()
                : collect(),
            'sistemas'            => $this->paso === 2 ? Sistema::activos()->orderBy('nombre')->get() : collect(),
            'opcionesDisponibles' => $this->paso === 4 ? $this->opcionesDisponibles : collect(),
            'hayWhatsapp'         => Whatsapp::configurado(),
        ]);
    }
}
