<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\OrdenTrabajo;
use App\Models\GrupoTrabajo;
use App\Models\User;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar lista de tareas (plantillas)
     */
    public function index(Request $request)
    {
        $query = Tarea::query();

        // Filtrar solo tareas plantilla (sin orden_trabajo_id)
        $query->whereNull('orden_trabajo_id');

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }

        $tareas = $query->orderBy('tipo')->orderBy('nombre')->paginate(15);

        $tipos = Tarea::TIPOS;
        $estados = Tarea::ESTADOS;

        return view('tareas.index', compact('tareas', 'tipos', 'estados'));
    }

    /**
     * Mostrar formulario para crear nueva tarea plantilla
     */
    public function create()
    {
        $tipos = Tarea::TIPOS;
        return view('tareas.create', compact('tipos'));
    }

    /**
     * Guardar nueva tarea plantilla
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:' . implode(',', array_keys(Tarea::TIPOS)),
            'estado' => 'required|in:' . implode(',', array_keys(Tarea::ESTADOS))
        ]);

        Tarea::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'estado' => $request->estado,
            'completada' => false
        ]);

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea plantilla creada exitosamente');
    }

    /**
     * Mostrar detalles de una tarea plantilla
     */
    public function show(Tarea $tarea)
    {
        // Asegurarse de que sea una plantilla (sin orden_trabajo_id)
        if ($tarea->orden_trabajo_id) {
            abort(404);
        }

        return view('tareas.show', compact('tarea'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Tarea $tarea)
    {
        // Asegurarse de que sea una plantilla (sin orden_trabajo_id)
        if ($tarea->orden_trabajo_id) {
            abort(404);
        }

        $tipos = Tarea::TIPOS;
        $estados = Tarea::ESTADOS;

        return view('tareas.edit', compact('tarea', 'tipos', 'estados'));
    }

    /**
     * Actualizar tarea plantilla
     */
    public function update(Request $request, Tarea $tarea)
    {
        // Asegurarse de que sea una plantilla (sin orden_trabajo_id)
        if ($tarea->orden_trabajo_id) {
            abort(404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:' . implode(',', array_keys(Tarea::TIPOS)),
            'estado' => 'required|in:' . implode(',', array_keys(Tarea::ESTADOS))
        ]);

        $tarea->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'estado' => $request->estado
        ]);

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea plantilla actualizada exitosamente');
    }

    /**
     * Eliminar tarea plantilla
     */
    public function destroy(Tarea $tarea)
    {
        // Asegurarse de que sea una plantilla (sin orden_trabajo_id)
        if ($tarea->orden_trabajo_id) {
            abort(404);
        }

        $tarea->delete();

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea plantilla eliminada exitosamente');
    }

    /**
     * Asignar tarea plantilla a una orden de trabajo
     */
    public function asignarAOrden(Request $request, Tarea $tarea)
    {
        // Asegurarse de que sea una plantilla
        if ($tarea->orden_trabajo_id) {
            return response()->json(['error' => 'Esta tarea ya está asignada a una orden'], 400);
        }

        $request->validate([
            'orden_trabajo_id' => 'required|exists:ordenes_trabajo,id',
            'empleado_id' => 'nullable|exists:users,id',
            'movil_id' => 'nullable|exists:grupos_trabajo,id'
        ]);

        // Crear nueva instancia de tarea asignada a la orden
        $nuevaTarea = Tarea::create([
            'nombre' => $tarea->nombre,
            'tipo' => $tarea->tipo,
            'estado' => 'pendiente',
            'orden_trabajo_id' => $request->orden_trabajo_id,
            'empleado_id' => $request->empleado_id,
            'movil_id' => $request->movil_id,
            'completada' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea asignada exitosamente a la orden de trabajo',
            'tarea' => $nuevaTarea
        ]);
    }

    /**
     * Mostrar tareas asignadas a una orden específica
     */
    public function tareasDeOrden(OrdenTrabajo $ordenTrabajo)
    {
        $tareas = $ordenTrabajo->tareas()->with(['empleado', 'movil'])->get();

        if (request()->expectsJson()) {
            return response()->json($tareas);
        }

        return view('tareas.orden', compact('ordenTrabajo', 'tareas'));
    }

    /**
     * Actualizar estado de tarea en orden de trabajo
     */
    public function actualizarEstado(Request $request, Tarea $tarea)
    {
        // Solo tareas asignadas a órdenes pueden cambiar estado
        if (!$tarea->orden_trabajo_id) {
            return response()->json(['error' => 'Esta es una tarea plantilla'], 400);
        }

        $request->validate([
            'estado' => 'required|in:' . implode(',', array_keys(Tarea::ESTADOS)),
            'completada' => 'boolean'
        ]);

        $tarea->update([
            'estado' => $request->estado,
            'completada' => $request->completada ?? false
        ]);

        // Verificar si todas las tareas de la orden están completadas
        $ordenTrabajo = $tarea->ordenTrabajo;
        $todasCompletadas = $ordenTrabajo->tareas()->where('completada', false)->count() === 0;

        if ($todasCompletadas && $ordenTrabajo->estado !== 'terminada') {
            $ordenTrabajo->update([
                'estado' => 'terminada',
                'fecha_finalizacion' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Estado de tarea actualizado',
            'tarea' => $tarea
        ]);
    }

    /**
     * Obtener tareas disponibles para asignar a una orden
     */
    public function disponiblesParaOrden(Request $request)
    {
        $ordenTrabajoId = $request->query('orden_trabajo_id');

        // Obtener tipo de servicio de la orden para filtrar tareas relevantes
        $ordenTrabajo = OrdenTrabajo::find($ordenTrabajoId);
        $tipoServicio = $ordenTrabajo ? $ordenTrabajo->tipo_servicio : null;

        $query = Tarea::whereNull('orden_trabajo_id'); // Solo plantillas

        // Si hay tipo de servicio, filtrar tareas relacionadas
        if ($tipoServicio) {
            switch ($tipoServicio) {
                case 'instalacion':
                    $query->whereIn('tipo', ['instalacion', 'service']);
                    break;
                case 'reconexion':
                    $query->whereIn('tipo', ['reconexion', 'service']);
                    break;
                case 'service':
                case 'mantenimiento':
                    $query->whereIn('tipo', ['service', 'mantenimiento', 'soporte']);
                    break;
                case 'desconexion':
                    $query->whereIn('tipo', ['desconexion']);
                    break;
            }
        }

        $tareas = $query->orderBy('tipo')->orderBy('titulo')->get();

        return response()->json($tareas);
    }
}