<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'estado',
        'descripcion',
        'tipo_servicio',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Estados de la solicitud
    const ESTADOS = [
        'pendiente'  => 'Pendiente',
        'procesada'  => 'Procesada',
        'cancelada'  => 'Cancelada'
    ];

    // Tipos de servicio
    const TIPOS_SERVICIO = [
        'instalacion'   => 'Instalación',
        'reconexion'    => 'Reconexión',
        'service'       => 'Service/Mantenimiento',
        'desconexion'   => 'Desconexión',
        'mantenimiento' => 'Mantenimiento Preventivo'
    ];

    /**
     * Relación: Una solicitud pertenece a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Relación: Una solicitud genera una orden de trabajo (1:1)
     */
    public function ordenTrabajo()
    {
        return $this->hasOne(OrdenTrabajo::class, 'solicitud_id');
    }

    /**
     * Scope para solicitudes pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para solicitudes procesadas
     */
    public function scopeProcesadas($query)
    {
        return $query->where('estado', 'procesada');
    }

    /**
     * Accessor para estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    /**
     * Accessor para tipo de servicio formateado
     */
    public function getTipoServicioFormateadoAttribute()
    {
        return self::TIPOS_SERVICIO[$this->tipo_servicio] ?? $this->tipo_servicio;
    }

    /**
     * Verificar si la solicitud puede ser procesada
     */
    public function puedeSerProcesada()
    {
        return $this->estado === 'pendiente' && !$this->ordenTrabajo;
    }

    /**
     * Marcar como procesada
     */
    public function marcarComoProcesada()
    {
        $this->update(['estado' => 'procesada']);
    }
}