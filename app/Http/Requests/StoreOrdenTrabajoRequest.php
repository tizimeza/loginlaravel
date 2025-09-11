<?php

namespace App\Http\Requests;

use App\Models\OrdenTrabajo;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenTrabajoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Permitir acceso autenticado
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'numero_orden' => 'nullable|string|max:50|unique:ordenes_trabajo,numero_orden',
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_telefono' => 'nullable|string|max:20',
            'cliente_email' => 'nullable|email|max:255',
            'descripcion_problema' => 'required|string',
            'fecha_ingreso' => 'required|date',
            'fecha_estimada_entrega' => 'nullable|date|after_or_equal:fecha_ingreso',
            'fecha_entrega_real' => 'nullable|date',
            'estado' => 'required|in:' . implode(',', array_keys(OrdenTrabajo::ESTADOS)),
            'prioridad' => 'required|in:' . implode(',', array_keys(OrdenTrabajo::PRIORIDADES)),
            'costo_estimado' => 'nullable|numeric|min:0|max:999999.99',
            'costo_final' => 'nullable|numeric|min:0|max:999999.99',
            'observaciones' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
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
            'vehiculo_id.required' => 'Debe seleccionar un vehículo.',
            'vehiculo_id.exists' => 'El vehículo seleccionado no es válido.',
            'cliente_nombre.required' => 'El nombre del cliente es obligatorio.',
            'descripcion_problema.required' => 'La descripción del problema es obligatoria.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_estimada_entrega.after_or_equal' => 'La fecha estimada de entrega debe ser posterior o igual a la fecha de ingreso.',
            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'prioridad.required' => 'Debe seleccionar una prioridad.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
            'costo_estimado.numeric' => 'El costo estimado debe ser un número.',
            'costo_estimado.min' => 'El costo estimado no puede ser negativo.',
            'costo_final.numeric' => 'El costo final debe ser un número.',
            'costo_final.min' => 'El costo final no puede ser negativo.',
            'user_id.exists' => 'El técnico seleccionado no es válido.',
        ];
    }
}
