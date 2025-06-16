<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tareas = Tarea::all();
        return view('tareas.index', compact('tareas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $tarea = new Tarea();
        $tarea->nombre = $request->nombre;
        $tarea->save();

        return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente');
    }

    public function edit($id)
    {
        $tarea = Tarea::findOrFail($id);
        return view('tareas.edit', compact('tarea'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'completada' => 'boolean'
        ]);

        $tarea = Tarea::findOrFail($id);
        $tarea->nombre = $request->nombre;
        $tarea->completada = $request->completada ?? false;
        $tarea->save();

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada exitosamente');
    }

    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id);
        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada exitosamente');
    }
}