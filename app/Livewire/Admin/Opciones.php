<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AutorizaModulo;
use App\Livewire\Concerns\MensajesEs;
use App\Models\Opcion;
use App\Models\Sistema;
use App\Models\Tarifa;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Opciones y accesorios')]
class Opciones extends Component
{
    use AutorizaModulo, MensajesEs;

    protected string $modulo = 'opciones';

    public ?int $editandoId = null;

    public array $form = [
        'tipo'           => 'vidrio',
        'nombre'         => '',
        'precio'         => '0',
        'unidad_calculo' => 'm2',
        'sistema_id'     => '',
        'activo'         => true,
    ];

    protected function rules(): array
    {
        return [
            'form.tipo'           => ['required', 'in:' . implode(',', array_keys(Opcion::TIPOS))],
            'form.nombre'         => ['required', 'string', 'max:120'],
            'form.precio'         => ['required', 'numeric', 'min:0', 'max:99999999'],
            'form.unidad_calculo' => ['required', 'in:' . implode(',', array_keys(Tarifa::UNIDADES))],
            'form.sistema_id'     => ['nullable', 'exists:sistemas,id'],
            'form.activo'         => ['boolean'],
        ];
    }

    public function editar(int $id): void
    {
        $o = Opcion::findOrFail($id);

        $this->editandoId = $o->id;
        $this->form = [
            'tipo'           => $o->tipo,
            'nombre'         => $o->nombre,
            'precio'         => (string) $o->precio,
            'unidad_calculo' => $o->unidad_calculo,
            'sistema_id'     => (string) ($o->sistema_id ?? ''),
            'activo'         => (bool) $o->activo,
        ];
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate();

        $datos = [
            'tipo'           => $this->form['tipo'],
            'nombre'         => trim($this->form['nombre']),
            'precio'         => (float) $this->form['precio'],
            'unidad_calculo' => $this->form['unidad_calculo'],
            'sistema_id'     => $this->form['sistema_id'] !== '' ? (int) $this->form['sistema_id'] : null,
            'activo'         => (bool) $this->form['activo'],
        ];

        $this->editandoId
            ? Opcion::findOrFail($this->editandoId)->update($datos)
            : Opcion::create($datos);

        $this->cancelar();
        session()->now('ok', 'Opción guardada.');
    }

    public function cancelar(): void
    {
        $this->editandoId = null;
        $this->form = [
            'tipo' => 'vidrio', 'nombre' => '', 'precio' => '0',
            'unidad_calculo' => 'm2', 'sistema_id' => '', 'activo' => true,
        ];
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        Opcion::findOrFail($id)->delete();

        if ($this->editandoId === $id) {
            $this->cancelar();
        }

        session()->now('ok', 'Opción eliminada.');
    }

    public function render()
    {
        return view('livewire.admin.opciones', [
            'opciones' => Opcion::with('sistema')->orderBy('tipo')->orderBy('nombre')->get()->groupBy('tipo'),
            'sistemas' => Sistema::orderBy('nombre')->get(),
        ]);
    }
}
