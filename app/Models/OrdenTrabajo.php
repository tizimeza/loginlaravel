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
        'solicitud_id',
        'tipo_servicio',
        'cliente_id',
        'direccion',
        'cliente_telefono',
        'cliente_email',
        'descripcion_trabajo',
        'fecha_ingreso',
        'fecha_asignacion',
        'fecha_finalizacion',
        'estado',
        'prioridad',
        'observaciones',
        'motivo_no_terminada',
        'es_cliente_premium',
        'coordenadas_gps',
        'telefono_contacto',
        'horario_preferido',
        'user_id', // Técnico asignado
        'grupo_trabajo_id' // Grupo asignado
    ];

    protected $casts = [
        'fecha_ingreso'       => 'datetime',
        'fecha_asignacion'    => 'datetime',
        'fecha_finalizacion'  => 'datetime',
        'fecha_estimada_entrega' => 'datetime',
        'fecha_entrega_real'  => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    // Estados posibles según workflow de TecnoServi
    const ESTADOS = [
        'nueva'        => 'Nueva',
        'vista'        => 'Vista',
        'en_proceso'   => 'En Proceso',
        'terminada'    => 'Terminada',
        'no_terminada' => 'No Terminada'
    ];

    // Prioridades
    const PRIORIDADES = [
        'normal'   => 'Normal',
        'alta'     => 'Alta',
        'critica'  => 'Crítica (Cliente Premium)'
    ];

    // Tipos de servicio de TecnoServi
    const TIPOS_SERVICIO = [
        'instalacion'   => 'Instalación',
        'reconexion'    => 'Reconexión',
        'service'       => 'Service/Mantenimiento',
        'desconexion'   => 'Desconexión',
        'mantenimiento' => 'Mantenimiento Preventivo'
    ];

    /**
     * Relación: Una orden de trabajo se genera a partir de una solicitud (1:1)
     */
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    /**
     * Relación: Una orden de trabajo pertenece a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Relación: Una orden de trabajo pertenece a un usuario (técnico)
     */
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: Una orden de trabajo pertenece a un grupo de trabajo
     */
    public function grupoTrabajo()
    {
        return $this->belongsTo(GrupoTrabajo::class, 'grupo_trabajo_id');
    }

    /**
     * Relación: Una orden puede tener muchas tareas asociadas
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'orden_trabajo_id');
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
     * Accessor para obtener el tipo de servicio formateado
     */
    public function getTipoServicioFormateadoAttribute()
    {
        return self::TIPOS_SERVICIO[$this->tipo_servicio] ?? $this->tipo_servicio;
    }

    /**
     * Accessor para obtener color de estado en dashboard
     */
    public function getColorEstadoAttribute()
    {
        $colores = [
            'nueva'        => 'secondary',
            'vista'        => 'info',
            'en_proceso'   => 'warning',
            'terminada'    => 'success',
            'no_terminada' => 'danger',
        ];

        return $colores[$this->estado] ?? 'secondary';
    }

    /**
     * Accessor para obtener color de la prioridad
     */
    public function getColorPrioridadAttribute()
    {
        $colores = [
            'normal'  => 'success',
            'alta'    => 'warning',
            'critica' => 'danger'
        ];

        return $colores[$this->prioridad] ?? 'secondary';
    }

    /**
     * Método estático para obtener color de estado
     */
    public static function getColorEstado($estado)
    {
        $colores = [
            'nueva'        => 'secondary',
            'vista'        => 'info',
            'en_proceso'   => 'warning',
            'terminada'    => 'success',
            'no_terminada' => 'danger',
        ];

        return $colores[$estado] ?? 'secondary';
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
