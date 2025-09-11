<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use App\Models\User;
use App\Http\Requests\StoreOrdenTrabajoRequest;
use App\Http\Requests\UpdateOrdenTrabajoRequest;
use Illuminate\Http\Request;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OrdenTrabajo::with(['vehiculo.modelo.marca', 'tecnico']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_orden', 'like', "%{$search}%")
                  ->orWhere('cliente_nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion_problema', 'like', "%{$search}%");
            });
        }

        // Ordenar por fecha de ingreso descendente por defecto
        $ordenes = $query->orderBy('fecha_ingreso', 'desc')->paginate(15);

        // Datos para filtros
        $estados = OrdenTrabajo::ESTADOS;
        $prioridades = OrdenTrabajo::PRIORIDADES;

        return view('ordenes_trabajo.index', compact('ordenes', 'estados', 'prioridades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehiculos = Vehiculo::with('modelo.marca')->get();
        $tecnicos = User::all();
        $estados = OrdenTrabajo::ESTADOS;
        $prioridades = OrdenTrabajo::PRIORIDADES;

        return view('ordenes_trabajo.create', compact('vehiculos', 'tecnicos', 'estados', 'prioridades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrdenTrabajoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdenTrabajoRequest $request)
    {
        $data = $request->validated();
        
        // Generar número de orden automático si no se proporcionó
        if (empty($data['numero_orden'])) {
            $data['numero_orden'] = OrdenTrabajo::generarNumeroOrden();
        }

        OrdenTrabajo::create($data);

        return redirect()->route('ordenes_trabajo.index')
            ->with('success', 'Orden de trabajo creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrdenTrabajo  $ordenTrabajo
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenTrabajo $ordenTrabajo)
    {
        $ordenTrabajo->load(['vehiculo.modelo.marca', 'tecnico', 'tareas']);

        return view('ordenes_trabajo.show', compact('ordenTrabajo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrdenTrabajo  $ordenTrabajo
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenTrabajo $ordenTrabajo)
    {
        $vehiculos = Vehiculo::with('modelo.marca')->get();
        $tecnicos = User::all();
        $estados = OrdenTrabajo::ESTADOS;
        $prioridades = OrdenTrabajo::PRIORIDADES;

        return view('ordenes_trabajo.edit', compact('ordenTrabajo', 'vehiculos', 'tecnicos', 'estados', 'prioridades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrdenTrabajoRequest  $request
     * @param  \App\Models\OrdenTrabajo  $ordenTrabajo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrdenTrabajoRequest $request, OrdenTrabajo $ordenTrabajo)
    {
        $data = $request->validated();

        $ordenTrabajo->update($data);

        return redirect()->route('ordenes_trabajo.index')
            ->with('success', 'Orden de trabajo actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrdenTrabajo  $ordenTrabajo
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdenTrabajo $ordenTrabajo)
    {
        $ordenTrabajo->delete();

        return redirect()->route('ordenes_trabajo.index')
            ->with('success', 'Orden de trabajo eliminada exitosamente.');
    }

    /**
     * Cambiar el estado de una orden de trabajo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenTrabajo  $ordenTrabajo
     * @return \Illuminate\Http\Response
     */
    public function cambiarEstado(Request $request, OrdenTrabajo $ordenTrabajo)
    {
        $request->validate([
            'estado' => 'required|in:' . implode(',', array_keys(OrdenTrabajo::ESTADOS))
        ]);

        $ordenTrabajo->update([
            'estado' => $request->estado,
            'fecha_entrega_real' => $request->estado === 'entregado' ? now() : $ordenTrabajo->fecha_entrega_real
        ]);

        return back()->with('success', 'Estado actualizado exitosamente.');
    }
}
