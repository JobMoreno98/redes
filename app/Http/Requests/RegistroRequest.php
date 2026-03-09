<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Red;
use Illuminate\Validation\Rule;
use App\Models\Dispositivo;

class RegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $registroId = $this->route('id');
        $redId = $this->input('red_id');
        $red = $redId ? Red::find($redId) : null;

        $rules = [
            'ip' => [
                'required', 'ipv4', 'max:15',
                Rule::unique('registros', 'ip')->ignore($registroId)->whereNull('deleted_at'),
               
                   //validación dinámica
                function ($attribute, $value, $fail) use ($red) {
                    if (!$red) {
                        return;
                    }
                    
                    //obtener el último octeto de la IP
                    $octetos = explode('.', $value);
                    $host = end($octetos);

                    //obtener los hosts reservados de la red
                    $hostsReservados = $red->hosts_reservados ?? [];

                    // Comprobar si el host está en la lista de reservados
                    if (in_array($host, $hostsReservados)) {
                        $fail("El host '{$host}' está reservado para esta red y no puede ser asignado.");
                    }
                },
            ],
            'mac' => [
                'nullable', 'regex:/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/',
                Rule::unique('registros', 'mac')->ignore($registroId)->whereNull('deleted_at'),
            ],
            'numero_serie' => [
                'nullable', 'string', 'max:100',
                Rule::unique('registros', 'numero_serie')->ignore($registroId)->whereNull('deleted_at'),
            ],
            
            'descripcion' => 'nullable|string|max:255',
            'responsable' => 'required|string|max:100',
            'dependencia_id' => 'required|exists:dependencias,id',
            'red_id' => 'required|exists:redes,id',

            'tipo_dispositivo_id' => [
                'required',
                'exists:dispositivos,id',
                
                function ($attribute, $value, $fail) use ($redId) {
                    if (!$redId) {
                        return;
                    }
                    
                    $dispositivoValido = Dispositivo::where('id', $value)
                                                       ->where('red_id', $redId)
                                                       ->exists();
                                                       
                    if (!$dispositivoValido) {
                        $fail('El dispositivo seleccionado no pertenece a la red seleccionada.');
                    }
                }
            ],
        ];

        if ($red && $red->usa_segmentos) {
            $rules['segmento_id'] = 'required|exists:segmentos,id';
        } else {
            $rules['segmento_id'] = 'nullable';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
    
            'ip.required' => 'La dirección IP es obligatoria.',
            'ip.ipv4' => 'La dirección IP no tiene un formato válido.',
            'ip.unique' => 'Esta dirección IP ya está registrada.',
            'mac.regex' => 'El formato de la MAC debe ser como 00:1A:2B:3C:4D:5E.',
            'mac.unique' => 'Esta dirección MAC ya está registrada.',
            'numero_serie.unique' => 'Este número de serie ya está registrado.',
            'responsable.required' => 'Debe indicar el responsable.',
            'dependencia_id.required' => 'Debe seleccionar una dependencia.',
            'red_id.required' => 'Debe seleccionar una red.',
            'red_id.exists' => 'La red seleccionada no es válida.',
            'segmento_id.required' => 'Debe seleccionar un segmento (la red usa segmentos).',
            'segmento_id.exists' => 'El segmento seleccionado no es válido.',
            'tipo_dispositivo_id.required' => 'Debe seleccionar un tipo de dispositivo.',
            'tipo_dispositivo_id.exists' => 'El tipo de dispositivo seleccionado no es válido.',
        ];
    }
}