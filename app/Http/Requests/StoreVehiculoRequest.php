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
            'tipo_vehiculo' => 'required|in:transit,kangoo,partner',
            'marca' => 'required|string|max:50',
            'modelo_id' => 'required|exists:modelos,id',
            'color' => 'required|string|max:50',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'capacidad_carga' => 'required|integer|min:1',
            'combustible' => 'required|in:nafta,diesel,gnc,electrico',
            'fecha_vencimiento_vtv' => 'nullable|date',
            'fecha_cambio_neumaticos' => 'nullable|date',
            'estado' => 'required|in:disponible,en_uso,mantenimiento,fuera_servicio',
            'kilometraje' => 'required|integer|min:0',
            'observaciones' => 'nullable|string',
            'servicios_pendientes' => 'nullable|string',
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
            'tipo_vehiculo.required' => 'El tipo de vehículo es obligatorio.',
            'tipo_vehiculo.in' => 'El tipo de vehículo debe ser: Transit, Kangoo o Partner.',
            'marca.required' => 'La marca es obligatoria.',
            'marca.max' => 'La marca no puede tener más de 50 caracteres.',
            'modelo_id.required' => 'Debe seleccionar un modelo.',
            'modelo_id.exists' => 'El modelo seleccionado no es válido.',
            'color.required' => 'El color es obligatorio.',
            'color.max' => 'El color no puede tener más de 50 caracteres.',
            'anio.required' => 'El año es obligatorio.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año debe ser mayor a 1900.',
            'anio.max' => 'El año no puede ser mayor al próximo año.',
            'capacidad_carga.required' => 'La capacidad de carga es obligatoria.',
            'capacidad_carga.integer' => 'La capacidad de carga debe ser un número entero.',
            'capacidad_carga.min' => 'La capacidad de carga debe ser mayor a 0.',
            'combustible.required' => 'El tipo de combustible es obligatorio.',
            'combustible.in' => 'El combustible debe ser: nafta, diesel, GNC o eléctrico.',
            'fecha_vencimiento_vtv.date' => 'La fecha de vencimiento de VTV debe ser una fecha válida.',
            'fecha_cambio_neumaticos.date' => 'La fecha de cambio de neumáticos debe ser una fecha válida.',
            'estado.required' => 'El estado del vehículo es obligatorio.',
            'estado.in' => 'El estado debe ser: disponible, en uso, mantenimiento o fuera de servicio.',
            'kilometraje.required' => 'El kilometraje es obligatorio.',
            'kilometraje.integer' => 'El kilometraje debe ser un número entero.',
            'kilometraje.min' => 'El kilometraje no puede ser negativo.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'imagen.max' => 'La imagen no debe ser mayor a 2MB.'
        ];
    }
}
