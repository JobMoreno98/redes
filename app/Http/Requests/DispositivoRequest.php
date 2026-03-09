<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DispositivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id') ?? $this->route('dispositivo');

        return [
            'tipo_dispositivo' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\pL\s0-9]+$/u',
                Rule::unique('dispositivos')
                ->ignore($id)
                ->whereNull('deleted_at'),
            ],

            'red_id' => [
                'required',
                'integer',
                'exists:redes,id'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_dispositivo.required' => 'Debe ingresar el nombre del tipo de dispositivo.',
            'tipo_dispositivo.regex' => 'El tipo de dispositivo solo puede contener letras, números y espacios.',
            'tipo_dispositivo.unique' => 'Ya existe un tipo de dispositivo con ese nombre.',
            'red_id.required' => 'Debe seleccionar una red asociada.',
            'red_id.exists' => 'La red seleccionada no es válida.'
        ];
    }
}
