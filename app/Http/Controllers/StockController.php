<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Stock::query();

        // Filtros
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('estado_stock')) {
            switch ($request->estado_stock) {
                case 'sin_stock':
                    $query->where('cantidad_actual', 0);
                    break;
                case 'stock_bajo':
                    $query->whereRaw('cantidad_actual <= cantidad_minima AND cantidad_actual > 0');
                    break;
                case 'stock_normal':
                    $query->whereRaw('cantidad_actual > cantidad_minima');
                    break;
            }
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%");
            });
        }

        $productos = $query->orderBy('nombre')->paginate(20);

        // Datos para filtros
        $categorias = Stock::CATEGORIAS;
        $estadisticas = Stock::getEstadisticas();

        return view('stock.index', compact('productos', 'categorias', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Stock::CATEGORIAS;
        return view('stock.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStockRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStockRequest $request)
    {
        $data = $request->validated();
        
        // Manejar la subida de imagen
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $data['imagen'] = $imagen->storeAs('stock', $nombreImagen, 'public');
        }

        Stock::create($data);

        return redirect()->route('stock.index')
            ->with('success', 'Producto agregado al stock exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        return view('stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        $categorias = Stock::CATEGORIAS;
        return view('stock.edit', compact('stock', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStockRequest  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $data = $request->validated();
        
        // Manejar la subida de nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($stock->imagen && \Storage::disk('public')->exists($stock->imagen)) {
                \Storage::disk('public')->delete($stock->imagen);
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $data['imagen'] = $imagen->storeAs('stock', $nombreImagen, 'public');
        }

        $stock->update($data);

        return redirect()->route('stock.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        // Eliminar imagen si existe
        if ($stock->imagen && \Storage::disk('public')->exists($stock->imagen)) {
            \Storage::disk('public')->delete($stock->imagen);
        }
        
        $stock->delete();

        return redirect()->route('stock.index')
            ->with('success', 'Producto eliminado del stock exitosamente.');
    }

    /**
     * Ajustar stock (agregar o reducir)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function ajustarStock(Request $request, Stock $stock)
    {
        $request->validate([
            'tipo_ajuste' => 'required|in:agregar,reducir',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255'
        ]);

        $cantidad = $request->cantidad;
        $tipo = $request->tipo_ajuste;
        $motivo = $request->motivo ?? 'Ajuste manual';

        if ($tipo === 'agregar') {
            $stock->agregarStock($cantidad, $motivo);
            $mensaje = "Se agregaron {$cantidad} unidades al stock.";
        } else {
            if ($stock->reducirStock($cantidad, $motivo)) {
                $mensaje = "Se redujeron {$cantidad} unidades del stock.";
            } else {
                return back()->with('error', 'No hay suficiente stock para reducir la cantidad solicitada.');
            }
        }

        return back()->with('success', $mensaje);
    }

    /**
     * Ver productos con stock bajo
     *
     * @return \Illuminate\Http\Response
     */
    public function stockBajo()
    {
        $productos = Stock::stockBajo()->activos()->with([])->get();
        
        return view('stock.stock_bajo', compact('productos'));
    }

    /**
     * Toggle estado activo/inactivo
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function toggleActivo(Stock $stock)
    {
        $stock->update([
            'activo' => !$stock->activo
        ]);

        $estado = $stock->activo ? 'activado' : 'desactivado';
        
        return back()->with('success', "Producto {$estado} exitosamente.");
    }
}