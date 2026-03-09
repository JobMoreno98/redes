<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Red;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class SegmentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id') ?? $this->route('segmento');

        return [
            'segmento' => [
                'required',
                'integer',
                'between:0,255',
                Rule::unique('segmentos')
                    ->ignore($id)
                    ->where(fn ($query) => $query->where('red_id', $this->input('red_id')))
                    ->whereNull('deleted_at'),
            ],
            'red_id' => [
                'required',
                'exists:redes,id',
            ],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $red = Red::find($this->input('red_id'));

            if ($red && !$red->usa_segmentos) {
                $validator->errors()->add('red_id', 'La red seleccionada no permite segmentos.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'segmento.required' => 'Debe ingresar el número de segmento.',
            'segmento.integer' => 'El segmento debe ser un número entero.',
            'segmento.between' => 'El segmento debe estar entre 0 y 255.',
            'segmento.unique' => 'Este segmento ya existe en la red seleccionada.',
            'red_id.required' => 'Debe seleccionar una red.',
            'red_id.exists' => 'La red seleccionada no es válida.',
        ];
    }
}
