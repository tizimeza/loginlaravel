<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;

class VehiculoController extends Controller
{
    // ... (aquí van los otros métodos como index, create, store, etc.)

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

    // ... (aquí van los otros métodos como edit, update, destroy)
}