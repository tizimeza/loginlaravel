<?php

namespace App\Http\Requests;

use App\Models\Cliente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClienteRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clientes,email',
            'telefono' => 'required|string|max:20|unique:clientes,telefono',
            'direccion' => 'required|string|max:500',
            'tipo_cliente' => ['required', Rule::in(array_keys(Cliente::TIPOS_CLIENTE))],
            'es_premium' => 'boolean',
            'documento' => 'nullable|string|max:50|unique:clientes,documento',
            'observaciones' => 'nullable|string|max:1000',
            'activo' => 'boolean'
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
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Ya existe un cliente con este email.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'telefono.unique' => 'Ya existe un cliente con este teléfono.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede tener más de 500 caracteres.',
            'tipo_cliente.required' => 'Debe seleccionar un tipo de cliente.',
            'tipo_cliente.in' => 'El tipo de cliente seleccionado no es válido.',
            'documento.max' => 'El documento no puede tener más de 50 caracteres.',
            'documento.unique' => 'Ya existe un cliente con este documento.',
            'observaciones.max' => 'Las observaciones no pueden tener más de 1000 caracteres.'
        ];
    }
}
