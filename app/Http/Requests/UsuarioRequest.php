<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
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
        $id = $this->route('usuario'); 
        
        if ($id instanceof \App\Models\Usuario) {
            $id = $id->id;
        }

        return [
            'nombre' => [
                'required', 'string', 'max:100',
                Rule::unique('usuarios', 'nombre')
                ->ignore($id)
                ->whereNull('deleted_at'),
            ],
            'codigo' => [
                'required', 'integer', 'digits_between:6,9',
                Rule::unique('usuarios', 'codigo')
                ->ignore($id)
                ->whereNull('deleted_at'),
            ],
            'password' => $this->isMethod('post') 
                ? 'required|min:6|confirmed'
                : 'nullable|min:6|confirmed',
            
            'dependencia_id' => 'required|exists:dependencias,id',

            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ];
    }
    
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.unique' => 'Ya existe un usuario con ese nombre.',

            'codigo.required' => 'El código es obligatorio.',
            'codigo.integer' => 'El código debe ser numérico.',
            'codigo.digits_between' => 'El código debe tener entre 6 y 9 dígitos.',
            'codigo.unique' => 'Ya existe un usuario con ese código.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'dependencia_id.required' => 'Debe seleccionar una dependencia.',
            'dependencia_id.exists' => 'La dependencia seleccionada no es válida.',
            'roles.*.exists' => 'El rol seleccionado no es válido.',
        ];
    }
}