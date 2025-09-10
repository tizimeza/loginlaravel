<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehiculoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'patente' => 'required|string|max:10|unique:vehiculos,patente',
            'color' => 'required|string|max:50',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'modelo_id' => 'required|exists:modelos,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'patente.required' => 'La patente es obligatoria.',
            'patente.unique' => 'Ya existe un vehículo con esta patente.',
            'patente.max' => 'La patente no puede tener más de 10 caracteres.',
            'color.required' => 'El color es obligatorio.',
            'color.max' => 'El color no puede tener más de 50 caracteres.',
            'anio.required' => 'El año es obligatorio.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año debe ser mayor a 1900.',
            'anio.max' => 'El año no puede ser mayor al próximo año.',
            'modelo_id.required' => 'Debe seleccionar un modelo.',
            'modelo_id.exists' => 'El modelo seleccionado no es válido.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'imagen.max' => 'La imagen no debe ser mayor a 2MB.'
        ];
    }
}
