<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tareas = Tarea::where('user_id', auth()->id())->get();
        $vehiculos = Vehiculo::with('modelo.marca')->get();
        
        return view('home', compact('tareas', 'vehiculos'));
    }
}
