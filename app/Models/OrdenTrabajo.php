<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    use HasFactory;

    protected $table = 'ordenes_trabajo';

    protected $fillable = [
        'numero_orden',
        'vehiculo_id',
        'cliente_nombre',
        'cliente_telefono',
        'cliente_email',
        'descripcion_problema',
        'fecha_ingreso',
        'fecha_estimada_entrega',
        'fecha_entrega_real',
        'estado',
        'prioridad',
        'costo_estimado',
        'costo_final',
        'observaciones',
        'user_id', // Técnico asignado
        'grupo_trabajo_id' // Grupo asignado
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'fecha_estimada_entrega' => 'datetime',
        'fecha_entrega_real' => 'datetime',
        'costo_estimado' => 'decimal:2',
        'costo_final' => 'decimal:2',
    ];

    // Estados posibles
    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'en_proceso' => 'En Proceso',
        'esperando_repuestos' => 'Esperando Repuestos',
        'completado' => 'Completado',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado'
    ];

    // Prioridades
    const PRIORIDADES = [
        'baja' => 'Baja',
        'media' => 'Media',
        'alta' => 'Alta',
        'urgente' => 'Urgente'
    ];

    /**
     * Relación: Una orden de trabajo pertenece a un vehículo
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Relación: Una orden de trabajo pertenece a un usuario (técnico)
     */
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: Una orden de trabajo puede tener muchas tareas
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'orden_trabajo_id');
    }

    /**
     * Relación: Una orden de trabajo pertenece a un grupo de trabajo
     */
    public function grupoTrabajo()
    {
        return $this->belongsTo(GrupoTrabajo::class, 'grupo_trabajo_id');
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeConEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por prioridad
     */
    public function scopeConPrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    /**
     * Accessor para obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    /**
     * Accessor para obtener la prioridad formateada
     */
    public function getPrioridadFormateadaAttribute()
    {
        return self::PRIORIDADES[$this->prioridad] ?? $this->prioridad;
    }

    /**
     * Accessor para determinar si la orden está atrasada
     */
    public function getEsAtrasadaAttribute()
    {
        if (!$this->fecha_estimada_entrega || $this->estado === 'entregado') {
            return false;
        }
        
        return now() > $this->fecha_estimada_entrega;
    }

    /**
     * Accessor para obtener el color del estado
     */
    public function getColorEstadoAttribute()
    {
        return $this->getColorEstado($this->estado);
    }

    /**
     * Método estático para obtener el color de un estado específico
     */
    public static function getColorEstado($estado)
    {
        $colores = [
            'pendiente' => 'secondary',
            'en_proceso' => 'info',
            'esperando_repuestos' => 'warning',
            'completado' => 'success',
            'entregado' => 'primary',
            'cancelado' => 'danger'
        ];

        return $colores[$estado] ?? 'secondary';
    }

    /**
     * Accessor para obtener el color de la prioridad
     */
    public function getColorPrioridadAttribute()
    {
        $colores = [
            'baja' => 'success',
            'media' => 'info',
            'alta' => 'warning',
            'urgente' => 'danger'
        ];

        return $colores[$this->prioridad] ?? 'secondary';
    }

    /**
     * Generar número de orden automático
     */
    public static function generarNumeroOrden()
    {
        $year = date('Y');
        $ultimaOrden = self::whereYear('created_at', $year)
            ->orderBy('numero_orden', 'desc')
            ->first();

        if (!$ultimaOrden) {
            return $year . '-0001';
        }

        $ultimoNumero = intval(substr($ultimaOrden->numero_orden, -4));
        $nuevoNumero = str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);

        return $year . '-' . $nuevoNumero;
    }
}
