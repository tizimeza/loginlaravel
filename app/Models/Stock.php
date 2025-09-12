<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria',
        'marca',
        'modelo',
        'cantidad_actual',
        'cantidad_minima',
        'cantidad_maxima',
        'precio_compra',
        'precio_venta',
        'ubicacion',
        'proveedor',
        'activo',
        'imagen'
    ];

    protected $casts = [
        'cantidad_actual' => 'integer',
        'cantidad_minima' => 'integer',
        'cantidad_maxima' => 'integer',
        'precio_compra'   => 'decimal:2',
        'precio_venta'    => 'decimal:2',
        'activo'          => 'boolean',
    ];

    // Categorías disponibles para TecnoServi
    const CATEGORIAS = [
        'routers'       => 'Routers Wi-Fi',
        'modems'        => 'Módems',
        'cables'        => 'Cables Coaxiales / UTP',
        'conectores'    => 'Conectores y Adaptadores',
        'precintos'     => 'Precintos y Sujetadores',
        'grampas'       => 'Grampas',
        'cajas'         => 'Cajas de Conexión',
        'herramientas'  => 'Herramientas de Instalación',
        'antenas'       => 'Antenas y Equipos Externos',
        'otros'         => 'Otros Materiales'
    ];

    /**
     * Scope para productos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para productos con stock bajo
     */
    public function scopeStockBajo($query)
    {
        return $query->whereRaw('cantidad_actual <= cantidad_minima');
    }

    /**
     * Scope para productos sin stock
     */
    public function scopeSinStock($query)
    {
        return $query->where('cantidad_actual', 0);
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopeConCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Accessor para obtener la categoría formateada
     */
    public function getCategoriaFormateadaAttribute()
    {
        return self::CATEGORIAS[$this->categoria] ?? $this->categoria;
    }

    /**
     * Accessor para verificar si tiene stock bajo
     */
    public function getTieneStockBajoAttribute()
    {
        return $this->cantidad_actual <= $this->cantidad_minima;
    }

    /**
     * Accessor para verificar si está sin stock
     */
    public function getEstaVacioAttribute()
    {
        return $this->cantidad_actual == 0;
    }

    /**
     * Accessor para obtener el color del estado del stock
     */
    public function getColorStockAttribute()
    {
        if ($this->esta_vacio) {
            return 'danger';
        } elseif ($this->tiene_stock_bajo) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    /**
     * Accessor para obtener el texto del estado del stock
     */
    public function getEstadoStockAttribute()
    {
        if ($this->esta_vacio) {
            return 'Sin Stock';
        } elseif ($this->tiene_stock_bajo) {
            return 'Stock Bajo';
        } else {
            return 'Stock Normal';
        }
    }

    /**
     * Accessor para calcular el margen de ganancia
     */
    public function getMargenGananciaAttribute()
    {
        if ($this->precio_compra > 0) {
            return (($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100;
        }
        return 0;
    }

    /**
     * Accessor para obtener el valor total del stock
     */
    public function getValorTotalStockAttribute()
    {
        return $this->cantidad_actual * $this->precio_compra;
    }

    /**
     * Método para agregar stock
     */
    public function agregarStock($cantidad, $motivo = null)
    {
        $this->increment('cantidad_actual', $cantidad);

        // Aquí podrías registrar el movimiento en una tabla de historial
        // MovimientoStock::create([...]);

        return $this;
    }

    /**
     * Método para reducir stock
     */
    public function reducirStock($cantidad, $motivo = null)
    {
        if ($this->cantidad_actual >= $cantidad) {
            $this->decrement('cantidad_actual', $cantidad);

            // Aquí podrías registrar el movimiento en una tabla de historial
            // MovimientoStock::create([...]);

            return true;
        }

        return false; // No hay suficiente stock
    }

    /**
     * Verificar si se puede vender una cantidad específica
     */
    public function puedeVender($cantidad)
    {
        return $this->cantidad_actual >= $cantidad;
    }

    /**
     * Obtener productos que necesitan reposición
     */
    public static function necesitanReposicion()
    {
        return self::stockBajo()->activos()->get();
    }

    /**
     * Obtener estadísticas generales del stock
     */
    public static function getEstadisticas()
    {
        return [
            'total_productos'         => self::activos()->count(),
            'productos_sin_stock'     => self::sinStock()->activos()->count(),
            'productos_stock_bajo'    => self::stockBajo()->activos()->count(),
            'valor_total_inventario'  => self::activos()->sum(\DB::raw('cantidad_actual * precio_compra')),
            'productos_inactivos'     => self::where('activo', false)->count(),
        ];
    }
}

