<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Vehiculo;
use App\Models\OrdenTrabajo;
use App\Models\Cliente;
use App\Models\Stock;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $user = auth()->user();

        // Estadísticas generales
        $stats = [
            'total_ordenes' => OrdenTrabajo::count(),
            'ordenes_pendientes' => OrdenTrabajo::where('estado', 'pendiente')->count(),
            'ordenes_en_proceso' => OrdenTrabajo::where('estado', 'en_proceso')->count(),
            'ordenes_completadas' => OrdenTrabajo::where('estado', 'completado')->count(),
            'total_clientes' => Cliente::count(),
            'total_vehiculos' => Vehiculo::count(),
            'productos_bajo_stock' => Stock::whereRaw('cantidad_actual < cantidad_minima')->count(),
        ];

        // Órdenes por estado (para gráfico)
        $ordenesPorEstado = OrdenTrabajo::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // Órdenes del mes actual
        $ordenesMes = OrdenTrabajo::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Actividad reciente (últimos 10 cambios)
        $actividadReciente = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Productos con stock bajo
        $productosStockBajo = Stock::whereRaw('cantidad_actual < cantidad_minima')
            ->orderBy('cantidad_actual', 'asc')
            ->take(5)
            ->get();

        // Órdenes recientes según el rol del usuario
        if ($user->hasRole('tecnico')) {
            // Técnico: solo sus órdenes
            $ordenesRecientes = OrdenTrabajo::where('user_id', $user->id)
                ->with(['cliente', 'tecnico', 'vehiculo'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            // Admin y Supervisor: todas las órdenes
            $ordenesRecientes = OrdenTrabajo::with(['cliente', 'tecnico', 'vehiculo'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        // Vehículos (solo para admin y supervisor)
        $vehiculos = [];
        if ($user->hasAnyRole(['admin', 'supervisor'])) {
            $vehiculos = Vehiculo::with('modelo.marca')->take(5)->get();
        }

        return view('home', compact(
            'stats',
            'ordenesPorEstado',
            'ordenesMes',
            'actividadReciente',
            'productosStockBajo',
            'ordenesRecientes',
            'vehiculos'
        ));
    }
}
