<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Modelo;
use App\Models\Marca;
use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;

class VehiculoController extends Controller
{
    /**
     * Muestra una lista de todos los vehículos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehiculos = Vehiculo::with('modelo.marca')->paginate(10);
        return view('vehiculos.index', compact('vehiculos'));
    }

    /**
     * Muestra el formulario para crear un nuevo vehículo.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modelos = Modelo::with('marca')->get();
        return view('vehiculos.create', compact('modelos'));
    }

    /**
     * Almacena un nuevo vehículo en la base de datos.
     *
     * @param  \App\Http\Requests\StoreVehiculoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVehiculoRequest $request)
    {
        $data = $request->validated();
        
        // Manejar la subida de imagen
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $data['imagen'] = $imagen->storeAs('vehiculos', $nombreImagen, 'public');
        }
        
        Vehiculo::create($data);
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo creado exitosamente.');
    }

    /**
     * Muestra los detalles de un vehículo específico.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function show(Vehiculo $vehiculo)
    {
        // Cargar las relaciones 'modelo' y, a través de modelo, 'marca'.
        // Es más eficiente que usar ->load() después.
        $vehiculo->load('modelo.marca');

        // Retornamos una vista y le pasamos la variable $vehiculo
        return view('vehiculos.show', [
            'vehiculo' => $vehiculo
        ]);
    }

    /**
     * Muestra el formulario para editar un vehículo.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehiculo $vehiculo)
    {
        $modelos = Modelo::with('marca')->get();
        return view('vehiculos.edit', compact('vehiculo', 'modelos'));
    }

    /**
     * Actualiza un vehículo específico en la base de datos.
     *
     * @param  \App\Http\Requests\UpdateVehiculoRequest  $request
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo)
    {
        $data = $request->validated();
        
        // Manejar la subida de nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($vehiculo->imagen && \Storage::disk('public')->exists($vehiculo->imagen)) {
                \Storage::disk('public')->delete($vehiculo->imagen);
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $data['imagen'] = $imagen->storeAs('vehiculos', $nombreImagen, 'public');
        }
        
        $vehiculo->update($data);
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado exitosamente.');
    }

    /**
     * Elimina un vehículo específico de la base de datos.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehiculo $vehiculo)
    {
        // Eliminar imagen si existe
        if ($vehiculo->imagen && \Storage::disk('public')->exists($vehiculo->imagen)) {
            \Storage::disk('public')->delete($vehiculo->imagen);
        }
        
        $vehiculo->delete();
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado exitosamente.');
    }
}