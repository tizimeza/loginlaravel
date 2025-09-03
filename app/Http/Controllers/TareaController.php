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
        $tareas = Tarea::where('user_id', auth()->id())->get();
        return view('tareas.index', compact('tareas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $tarea = new Tarea();
        $tarea->nombre = $request->nombre;
        $tarea->user_id = auth()->id();
        $tarea->save();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Tarea creada exitosamente']);
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente');
    }

    public function edit($id)
    {
        $tarea = Tarea::where('user_id', auth()->id())->findOrFail($id);
        return view('tareas.edit', compact('tarea'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'completada' => 'boolean'
        ]);

        $tarea = Tarea::where('user_id', auth()->id())->findOrFail($id);
        $tarea->nombre = $request->nombre;
        $tarea->completada = $request->has('completada') ? (bool)$request->completada : false;
        $tarea->save();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Tarea actualizada exitosamente']);
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada exitosamente');
    }

    public function destroy($id)
    {
        $tarea = Tarea::where('user_id', auth()->id())->findOrFail($id);
        $tarea->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Tarea eliminada exitosamente']);
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada exitosamente');
    }
}