<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Red; 
use Illuminate\Validation\Rule;

class RedRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
    {
        $usaSegmentos = $this->boolean('usa_segmentos');
        $direccion = trim($this->input('direccion_base'));

        if (!empty($direccion)) {
            $octetos = explode('.', $direccion);

            if ($usaSegmentos) {
                $octetos = array_pad($octetos, 2, '0');
                $direccionNormalizada = "{$octetos[0]}.{$octetos[1]}";
            } else {
                $octetos = array_pad($octetos, 3, '0');
                $direccionNormalizada = "{$octetos[0]}.{$octetos[1]}." . ($octetos[2] ?? '0');
            }

            $this->merge([
                'direccion_base' => $direccionNormalizada,
            ]);
        }
    }


    public function rules(): array
    {

        $routeParam = $this->route('rede');

        
    
        $uniqueRule = Rule::unique('redes', 'direccion_base')
                          ->ignore($routeParam)
                          ->whereNull('deleted_at'); 

        $usaSegmentos = $this->boolean('usa_segmentos');
        $regex = $usaSegmentos
            ? '/^(\d{1,3}\.\d{1,3})$/' // 2 octetos exactos
            : '/^(\d{1,3}\.\d{1,3}\.\d{1,3})$/'; // 3 octetos exactos

        return [
            'direccion_base' => [
                'required',
                'string',
                "regex:$regex",
                $uniqueRule,
            ],
            'descripcion' => 'nullable|string|max:255',
            'usa_segmentos' => 'sometimes|boolean',
            'hosts_reservados' => [
                'nullable',
                'string',
                'regex:/^(\d{1,3}(\s*,\s*\d{1,3})*)?$/' 
            ],

            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ];
    }

    public function messages(): array
    {
        return [
            'direccion_base.required' => 'Debe ingresar la dirección base de la red.',
            'direccion_base.regex' => 'El formato de la dirección no es válido para el tipo de red (asegúrese de que los octetos sean correctos).',
            'direccion_base.unique' => 'Esta dirección de red ya está registrada.',
            'hosts_reservados.regex' => 'El formato de hosts reservados debe ser números separados por coma (ej. 1, 126, 254).',
            'roles.*.exists' => 'El rol seleccionado no es válido.'
        ];
    }

 
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            
            $direccionNormalizada = $this->input('direccion_base');
            $usaSegmentos = $this->boolean('usa_segmentos');

            $routeParam = $this->route('rede');
            $numericId = $routeParam instanceof Red ? $routeParam->id : $routeParam;

            if ($validator->errors()->has('direccion_base')) {
                return;
            }

            $octetos = explode('.', $direccionNormalizada);

            if ($usaSegmentos) {
                $prefijoRelacionado = "{$octetos[0]}.{$octetos[1]}."; 
            } else {
                $prefijoRelacionado = "{$octetos[0]}.{$octetos[1]}"; 
            }

            $conflicto = Red::query()
                ->where(function ($q) use ($usaSegmentos, $prefijoRelacionado) {
                    if ($usaSegmentos) {
                        $q->where('usa_segmentos', false)
                          ->where('direccion_base', 'like', "$prefijoRelacionado%");
                    } else {
                        $q->where('usa_segmentos', true)
                          ->where('direccion_base', $prefijoRelacionado);
                    }
                })
                ->when($numericId, fn($q) => $q->where('id', '!=', $numericId))
                ->whereNull('deleted_at')
                ->exists();

            if ($conflicto) {
                $validator->errors()->add(
                    'direccion_base',
                    'Conflicto: Ya existe una red segmentada/no segmentada que usa esta base.'
                );
            }
        });
    }
}