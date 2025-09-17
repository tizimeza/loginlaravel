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
            // numero_orden se genera automáticamente en el modelo, no se valida aquí
            'cliente_id' => 'required|exists:clientes,id',
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'cliente_telefono' => 'nullable|string|max:20',
            'cliente_email' => 'nullable|email|max:255',
            'descripcion_problema' => 'required|string',
            'fecha_ingreso' => 'required|date',
            'fecha_estimada_entrega' => 'nullable|date|after_or_equal:fecha_ingreso',
            'fecha_entrega_real' => 'nullable|date',
            'estado' => 'nullable|in:' . implode(',', array_keys(OrdenTrabajo::ESTADOS)),
            'prioridad' => 'required|in:' . implode(',', array_keys(OrdenTrabajo::PRIORIDADES)),
            'costo_estimado' => 'nullable|numeric|min:0|max:999999.99',
            'costo_final' => 'nullable|numeric|min:0|max:999999.99',
            'observaciones' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            // Validación de tareas
            'tareas' => 'nullable|array',
            'tareas.*.tarea_plantilla_id' => 'required|exists:tareas,id',
            'tareas.*.empleado_id' => 'nullable|exists:users,id',
            'tareas.*.movil_id' => 'nullable|exists:grupos_trabajo,id',
            'tareas.*.observaciones' => 'nullable|string|max:500',
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
            // Mensajes para tareas
            'tareas.array' => 'Las tareas deben ser un arreglo válido.',
            'tareas.*.tarea_plantilla_id.required' => 'Debe seleccionar una tarea plantilla.',
            'tareas.*.tarea_plantilla_id.exists' => 'La tarea plantilla seleccionada no es válida.',
            'tareas.*.empleado_id.exists' => 'El técnico seleccionado no es válido.',
            'tareas.*.movil_id.exists' => 'El grupo de trabajo seleccionado no es válido.',
            'tareas.*.observaciones.max' => 'Las observaciones de la tarea no pueden exceder los 500 caracteres.',
        ];
    }
}
