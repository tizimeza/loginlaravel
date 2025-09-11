<?php

namespace App\Http\Controllers;

use App\Models\GrupoTrabajo;
use App\Models\User;
use App\Http\Requests\StoreGrupoTrabajoRequest;
use App\Http\Requests\UpdateGrupoTrabajoRequest;
use Illuminate\Http\Request;

class GrupoTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = GrupoTrabajo::with(['lider', 'miembros']);

        // Filtros
        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $grupos = $query->orderBy('nombre')->paginate(12);

        // Datos para filtros
        $especialidades = GrupoTrabajo::ESPECIALIDADES;

        return view('grupos_trabajo.index', compact('grupos', 'especialidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuarios = User::all();
        $especialidades = GrupoTrabajo::ESPECIALIDADES;
        $colores = GrupoTrabajo::COLORES;

        return view('grupos_trabajo.create', compact('usuarios', 'especialidades', 'colores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGrupoTrabajoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGrupoTrabajoRequest $request)
    {
        $data = $request->validated();
        
        $grupo = GrupoTrabajo::create($data);

        // Agregar miembros seleccionados
        if ($request->has('miembros') && is_array($request->miembros)) {
            $grupo->miembros()->sync($request->miembros);
        }

        return redirect()->route('grupos_trabajo.index')
            ->with('success', 'Grupo de trabajo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return \Illuminate\Http\Response
     */
    public function show(GrupoTrabajo $grupoTrabajo)
    {
        $grupoTrabajo->load(['lider', 'miembros', 'ordenesAsignadas.vehiculo']);
        $estadisticas = $grupoTrabajo->getEstadisticas();

        return view('grupos_trabajo.show', compact('grupoTrabajo', 'estadisticas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return \Illuminate\Http\Response
     */
    public function edit(GrupoTrabajo $grupoTrabajo)
    {
        $usuarios = User::all();
        $especialidades = GrupoTrabajo::ESPECIALIDADES;
        $colores = GrupoTrabajo::COLORES;
        $miembrosActuales = $grupoTrabajo->miembros->pluck('id')->toArray();

        return view('grupos_trabajo.edit', compact('grupoTrabajo', 'usuarios', 'especialidades', 'colores', 'miembrosActuales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGrupoTrabajoRequest  $request
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGrupoTrabajoRequest $request, GrupoTrabajo $grupoTrabajo)
    {
        $data = $request->validated();
        
        $grupoTrabajo->update($data);

        // Actualizar miembros
        if ($request->has('miembros') && is_array($request->miembros)) {
            $grupoTrabajo->miembros()->sync($request->miembros);
        } else {
            $grupoTrabajo->miembros()->sync([]);
        }

        return redirect()->route('grupos_trabajo.index')
            ->with('success', 'Grupo de trabajo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return \Illuminate\Http\Response
     */
    public function destroy(GrupoTrabajo $grupoTrabajo)
    {
        // Verificar si tiene órdenes asignadas
        if ($grupoTrabajo->ordenesAsignadas()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el grupo porque tiene órdenes de trabajo asignadas.');
        }

        $grupoTrabajo->delete();

        return redirect()->route('grupos_trabajo.index')
            ->with('success', 'Grupo de trabajo eliminado exitosamente.');
    }

    /**
     * Cambiar estado activo/inactivo
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return \Illuminate\Http\Response
     */
    public function toggleActivo(GrupoTrabajo $grupoTrabajo)
    {
        $grupoTrabajo->update([
            'activo' => !$grupoTrabajo->activo
        ]);

        $estado = $grupoTrabajo->activo ? 'activado' : 'desactivado';
        
        return back()->with('success', "Grupo {$estado} exitosamente.");
    }

    /**
     * Agregar miembro al grupo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return \Illuminate\Http\Response
     */
    public function agregarMiembro(Request $request, GrupoTrabajo $grupoTrabajo)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if (!$grupoTrabajo->esMiembro($request->user_id)) {
            $grupoTrabajo->agregarMiembro($request->user_id);
            $usuario = User::find($request->user_id);
            return back()->with('success', "Usuario {$usuario->name} agregado al grupo exitosamente.");
        }

        return back()->with('error', 'El usuario ya es miembro del grupo.');
    }

    /**
     * Remover miembro del grupo
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function removerMiembro(GrupoTrabajo $grupoTrabajo, User $user)
    {
        if ($grupoTrabajo->esLider($user->id)) {
            return back()->with('error', 'No se puede remover al líder del grupo.');
        }

        $grupoTrabajo->removerMiembro($user->id);
        
        return back()->with('success', "Usuario {$user->name} removido del grupo exitosamente.");
    }
}