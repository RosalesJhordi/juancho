<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AutorizaModulo;
use App\Livewire\Concerns\MensajesEs;
use App\Models\Sistema;
use App\Models\Tarifa;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Tarifas')]
class Tarifas extends Component
{
    use AutorizaModulo, MensajesEs;

    protected string $modulo = 'tarifas';

    public ?int $editandoId = null;

    public array $form = [
        'sistema_id'     => '',
        'precio'         => '',
        'unidad_calculo' => 'm2',
        'vigencia_desde' => '',
        'vigencia_hasta' => '',
        'activo'         => true,
    ];

    protected function rules(): array
    {
        return [
            'form.sistema_id'     => ['required', 'exists:sistemas,id'],
            'form.precio'         => ['required', 'numeric', 'min:0', 'max:99999999'],
            'form.unidad_calculo' => ['required', 'in:' . implode(',', array_keys(Tarifa::UNIDADES))],
            'form.vigencia_desde' => ['nullable', 'date'],
            'form.vigencia_hasta' => ['nullable', 'date'],
            'form.activo'         => ['boolean'],
        ];
    }

    public function editar(int $id): void
    {
        $t = Tarifa::findOrFail($id);

        $this->editandoId = $t->id;
        $this->form = [
            'sistema_id'     => (string) $t->sistema_id,
            'precio'         => (string) $t->precio,
            'unidad_calculo' => $t->unidad_calculo,
            'vigencia_desde' => $t->vigencia_desde?->toDateString() ?? '',
            'vigencia_hasta' => $t->vigencia_hasta?->toDateString() ?? '',
            'activo'         => (bool) $t->activo,
        ];
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $this->validate();

        $desde = $this->form['vigencia_desde'];
        $hasta = $this->form['vigencia_hasta'];

        if ($desde !== '' && $hasta !== '' && $hasta < $desde) {
            $this->addError('form.vigencia_hasta', 'Debe ser posterior a la fecha de inicio.');

            return;
        }

        $datos = [
            'sistema_id'     => (int) $this->form['sistema_id'],
            'precio'         => (float) $this->form['precio'],
            'unidad_calculo' => $this->form['unidad_calculo'],
            'vigencia_desde' => $desde ?: null,
            'vigencia_hasta' => $hasta ?: null,
            'activo'         => (bool) $this->form['activo'],
        ];

        $this->editandoId
            ? Tarifa::findOrFail($this->editandoId)->update($datos)
            : Tarifa::create($datos);

        $this->cancelar();
        session()->now('ok', 'Tarifa guardada.');
    }

    public function cancelar(): void
    {
        $this->editandoId = null;
        $this->form = [
            'sistema_id' => '', 'precio' => '', 'unidad_calculo' => 'm2',
            'vigencia_desde' => '', 'vigencia_hasta' => '', 'activo' => true,
        ];
        $this->resetValidation();
    }

    public function eliminar(int $id): void
    {
        Tarifa::findOrFail($id)->delete();

        if ($this->editandoId === $id) {
            $this->cancelar();
        }

        session()->now('ok', 'Tarifa eliminada.');
    }

    public function render()
    {
        return view('livewire.admin.tarifas', [
            'tarifas'  => Tarifa::with('sistema')->orderBy('sistema_id')->latest('id')->get(),
            'sistemas' => Sistema::orderBy('nombre')->get(),
        ]);
    }
}
