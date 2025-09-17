<?php

namespace App\Http\Requests;

use App\Models\OrdenTrabajo;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdenTrabajoRequest extends FormRequest
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
        $ordenId = $this->route('orden_trabajo')?->id ?? $this->route('ordenTrabajo')?->id;
        
        return [
            'numero_orden' => 'required|string|max:50|unique:ordenes_trabajo,numero_orden,' . $ordenId,
            'cliente_id' => 'required|exists:clientes,id',
            'vehiculo_id' => 'required|exists:vehiculos,id',
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
            'numero_orden.required' => 'El número de orden es obligatorio.',
            'numero_orden.unique' => 'Este número de orden ya existe.',
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            'vehiculo_id.required' => 'Debe seleccionar un vehículo.',
            'vehiculo_id.exists' => 'El vehículo seleccionado no es válido.',
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
