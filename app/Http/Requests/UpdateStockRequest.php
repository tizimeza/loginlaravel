<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
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
        $stockId = $this->route('stock')?->id;
        
        return [
            'codigo' => 'required|string|max:50|unique:stock,codigo,' . $stockId,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:' . implode(',', array_keys(Stock::CATEGORIAS)),
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'cantidad_actual' => 'required|integer|min:0',
            'cantidad_minima' => 'required|integer|min:1',
            'cantidad_maxima' => 'nullable|integer|min:1',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'ubicacion' => 'nullable|string|max:100',
            'proveedor' => 'nullable|string|max:100',
            'activo' => 'boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'codigo.required' => 'El código del producto es obligatorio.',
            'codigo.unique' => 'Ya existe un producto con este código.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria.required' => 'Debe seleccionar una categoría.',
            'categoria.in' => 'La categoría seleccionada no es válida.',
            'cantidad_actual.required' => 'La cantidad actual es obligatoria.',
            'cantidad_actual.min' => 'La cantidad actual no puede ser negativa.',
            'cantidad_minima.required' => 'La cantidad mínima es obligatoria.',
            'cantidad_minima.min' => 'La cantidad mínima debe ser al menos 1.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_compra.min' => 'El precio de compra no puede ser negativo.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.min' => 'El precio de venta no puede ser negativo.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'imagen.max' => 'La imagen no debe ser mayor a 2MB.',
        ];
    }
}
