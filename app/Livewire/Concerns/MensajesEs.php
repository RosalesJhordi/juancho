<?php

namespace App\Livewire\Concerns;

/** Mensajes de validación en español para los componentes Livewire. */
trait MensajesEs
{
    protected function messages(): array
    {
        return [
            'required'     => 'Este campo es obligatorio.',
            'string'       => 'Texto no válido.',
            'numeric'      => 'Ingresa un número válido.',
            'integer'      => 'Ingresa un número entero.',
            'email'        => 'Correo no válido.',
            'exists'       => 'Selección no válida.',
            'in'           => 'Selección no válida.',
            'date'         => 'Fecha no válida.',
            'boolean'      => 'Valor no válido.',
            'digits'       => 'Debe tener :digits dígitos.',
            'unique'       => 'Ya existe un registro con ese valor.',
            'image'        => 'El archivo debe ser una imagen.',
            'mimes'        => 'Formato permitido: :values.',
            'min.string'   => 'Mínimo :min caracteres.',
            'max.string'   => 'Máximo :max caracteres.',
            'min.numeric'  => 'El valor mínimo es :min.',
            'max.numeric'  => 'El valor máximo es :max.',
            'max.file'     => 'El archivo supera los :max KB.',
            'max.array'    => 'Máximo :max archivos a la vez.',
        ];
    }
}
