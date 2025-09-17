<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use App\Models\User;
use App\Models\Cliente;
use App\Models\GrupoTrabajo;
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
        $query = OrdenTrabajo::with(['cliente', 'grupoTrabajo', 'tecnico']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('tipo_servicio')) {
            $query->where('tipo_servicio', $request->tipo_servicio);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_orden', 'like', "%{$search}%")
                  ->orWhere('direccion', 'like', "%{$search}%")
                  ->orWhere('descripcion_trabajo', 'like', "%{$search}%")
                  ->orWhereHas('cliente', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%");
                  });
            });
        }

        // Ordenar por fecha de ingreso descendente por defecto
        $ordenes = $query->orderBy('fecha_ingreso', 'desc')->paginate(15);

        // Datos para filtros
        $estados = OrdenTrabajo::ESTADOS;
        $prioridades = OrdenTrabajo::PRIORIDADES;
        $tiposServicio = OrdenTrabajo::TIPOS_SERVICIO;

        return view('ordenes_trabajo.index', compact('ordenes', 'estados', 'prioridades', 'tiposServicio'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::activos()->get();
        $gruposTrabajo = GrupoTrabajo::where('activo', true)->get();
        $tecnicos = User::all();
        $vehiculos = Vehiculo::with('modelo.marca')->get();
        $estados = OrdenTrabajo::ESTADOS;
        $prioridades = OrdenTrabajo::PRIORIDADES;
        $tiposServicio = OrdenTrabajo::TIPOS_SERVICIO;

        return view('ordenes_trabajo.create', compact('clientes', 'gruposTrabajo', 'tecnicos', 'vehiculos', 'estados', 'prioridades', 'tiposServicio'));
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
        
        // Generar número de orden automático
        $data['numero_orden'] = OrdenTrabajo::generarNumeroOrden();
        
        // Establecer fecha de ingreso
        $data['fecha_ingreso'] = now();
        
        // Establecer estado inicial
        $data['estado'] = 'nueva';

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
        $ordenTrabajo->load(['cliente', 'grupoTrabajo', 'tecnico', 'tareas']);

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
        $clientes = Cliente::activos()->get();
        $gruposTrabajo = GrupoTrabajo::where('activo', true)->get();
        $tecnicos = User::all();
        $vehiculos = Vehiculo::with('modelo.marca')->get();
        $estados = OrdenTrabajo::ESTADOS;
        $prioridades = OrdenTrabajo::PRIORIDADES;
        $tiposServicio = OrdenTrabajo::TIPOS_SERVICIO;

        return view('ordenes_trabajo.edit', compact('ordenTrabajo', 'clientes', 'gruposTrabajo', 'tecnicos', 'vehiculos', 'estados', 'prioridades', 'tiposServicio'));
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
            'estado' => 'required|in:' . implode(',', array_keys(OrdenTrabajo::ESTADOS)),
            'motivo_no_terminada' => 'nullable|string|max:500'
        ]);

        $data = ['estado' => $request->estado];

        // Actualizar fechas según el estado
        switch ($request->estado) {
            case 'vista':
                $data['fecha_asignacion'] = now();
                break;
            case 'en_proceso':
                $data['fecha_asignacion'] = $ordenTrabajo->fecha_asignacion ?: now();
                break;
            case 'terminada':
            case 'no_terminada':
                $data['fecha_finalizacion'] = now();
                if ($request->estado === 'no_terminada' && $request->motivo_no_terminada) {
                    $data['motivo_no_terminada'] = $request->motivo_no_terminada;
                }
                break;
        }

        $ordenTrabajo->update($data);

        return back()->with('success', 'Estado actualizado exitosamente.');
    }
}
