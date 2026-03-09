<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   public function rules(): array
    {
        $id = $this->route('id') ?? $this->route('rol');

        return [
            'nombre_rol' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\pL\s0-9]+$/u', 
                Rule::unique('roles', 'nombre_rol')
                ->ignore($id)
                ->whereNull('deleted_at'),
            ],
            'permisos'   => 'required|array',
            'permisos.*' => 'exists:permisos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_rol.required' => 'Debes ingresar el nombre del rol.',
            'nombre_rol.regex' => 'El nombre del rol solo puede contener letras, números y espacios.',
            'nombre_rol.unique' => 'Ya existe un rol con ese nombre.',
            'permisos.required' => 'Debes seleccionar al menos un permiso.',
            'permisos.*.exists' => 'El permiso seleccionado no es válido.',
        ];
    }
}
