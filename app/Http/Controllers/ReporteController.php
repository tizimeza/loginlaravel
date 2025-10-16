<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Stock;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Generar PDF de una orden de trabajo específica
     */
    public function ordenTrabajoPDF($id)
    {
        $orden = OrdenTrabajo::with(['cliente', 'tecnico', 'vehiculo', 'grupoTrabajo', 'tareas'])
            ->findOrFail($id);

        // Verificar autorización
        $this->authorize('view', $orden);

        $pdf = PDF::loadView('reportes.orden-trabajo', compact('orden'));

        return $pdf->download('orden-trabajo-' . $orden->numero_orden . '.pdf');
    }

    /**
     * Generar PDF del inventario completo
     */
    public function inventarioPDF()
    {
        // Solo admin y supervisor pueden generar este reporte
        if (!auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403, 'No tienes permisos para generar este reporte');
        }

        $productos = Stock::orderBy('nombre')->get();

        $stats = [
            'total_productos' => $productos->count(),
            'valor_total' => $productos->sum(function($p) {
                return ($p->cantidad_actual ?? 0) * ($p->precio_venta ?? 0);
            }),
            'productos_bajo_stock' => $productos->filter(function($p) {
                return ($p->cantidad_actual ?? 0) < ($p->cantidad_minima ?? 0);
            })->count(),
        ];

        $pdf = PDF::loadView('reportes.inventario', compact('productos', 'stats'));

        return $pdf->download('inventario-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generar PDF de órdenes por periodo
     */
    public function ordenesPorPeriodoPDF(Request $request)
    {
        // Validar fechas
        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        // Solo admin y supervisor
        if (!auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403, 'No tienes permisos para generar este reporte');
        }

        $ordenes = OrdenTrabajo::with(['cliente', 'tecnico'])
            ->whereBetween('created_at', [$request->fecha_desde, $request->fecha_hasta])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_ordenes' => $ordenes->count(),
            'completadas' => $ordenes->where('estado', 'completado')->count(),
            'pendientes' => $ordenes->where('estado', 'pendiente')->count(),
            'en_proceso' => $ordenes->where('estado', 'en_proceso')->count(),
            'total_facturado' => $ordenes->sum('costo_final'),
        ];

        $pdf = PDF::loadView('reportes.ordenes-periodo', compact('ordenes', 'stats', 'request'));

        return $pdf->download('ordenes-' . $request->fecha_desde . '-a-' . $request->fecha_hasta . '.pdf');
    }

    /**
     * Generar PDF de clientes
     */
    public function clientesPDF()
    {
        // Solo admin y supervisor
        if (!auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403, 'No tienes permisos para generar este reporte');
        }

        $clientes = Cliente::with(['ordenesTrabajo'])->orderBy('nombre')->get();

        $stats = [
            'total_clientes' => $clientes->count(),
            'clientes_activos' => $clientes->where('activo', true)->count(),
            'clientes_premium' => $clientes->where('tipo_cliente', 'empresa')->count(),
        ];

        $pdf = PDF::loadView('reportes.clientes', compact('clientes', 'stats'));

        return $pdf->download('clientes-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generar PDF de vehículos
     */
    public function vehiculosPDF()
    {
        // Solo admin y supervisor
        if (!auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403, 'No tienes permisos para generar este reporte');
        }

        // Obtener todos los vehículos ordenados por patente
        $vehiculos = Vehiculo::orderBy('patente')->get();

        $stats = [
            'total_vehiculos' => $vehiculos->count(),
            'vehiculos_activos' => $vehiculos->count(), // Todos están activos si no hay campo 'activo'
        ];

        $pdf = PDF::loadView('reportes.vehiculos', compact('vehiculos', 'stats'));

        return $pdf->download('vehiculos-' . now()->format('Y-m-d') . '.pdf');
    }
}
