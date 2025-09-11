<?php

namespace App\Http\Requests;

use App\Models\GrupoTrabajo;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGrupoTrabajoRequest extends FormRequest
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
        $grupoId = $this->route('grupo_trabajo')?->id ?? $this->route('grupoTrabajo')?->id;
        
        return [
            'nombre' => 'required|string|max:255|unique:grupos_trabajo,nombre,' . $grupoId,
            'descripcion' => 'nullable|string',
            'lider_id' => 'required|exists:users,id',
            'activo' => 'boolean',
            'color' => 'required|in:' . implode(',', array_keys(GrupoTrabajo::COLORES)),
            'especialidad' => 'required|in:' . implode(',', array_keys(GrupoTrabajo::ESPECIALIDADES)),
            'miembros' => 'nullable|array',
            'miembros.*' => 'exists:users,id',
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
            'nombre.required' => 'El nombre del grupo es obligatorio.',
            'nombre.unique' => 'Ya existe un grupo con este nombre.',
            'lider_id.required' => 'Debe seleccionar un líder para el grupo.',
            'lider_id.exists' => 'El líder seleccionado no es válido.',
            'color.required' => 'Debe seleccionar un color para el grupo.',
            'color.in' => 'El color seleccionado no es válido.',
            'especialidad.required' => 'Debe seleccionar una especialidad.',
            'especialidad.in' => 'La especialidad seleccionada no es válida.',
            'miembros.array' => 'Los miembros deben ser una lista válida.',
            'miembros.*.exists' => 'Uno o más miembros seleccionados no son válidos.',
        ];
    }
}
