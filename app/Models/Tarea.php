<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tareas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'tipo',
        'estado',
        'completada',
        'user_id',
        'empleado_id',
        'orden_trabajo_id',
        'movil_id',
        'observaciones'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completada' => 'boolean',
    ];

    // Estados de la tarea
    const ESTADOS = [
        'pendiente'  => 'Pendiente',
        'asignada'   => 'Asignada',
        'en_proceso' => 'En Proceso',
        'completada' => 'Completada',
        'cancelada'  => 'Cancelada'
    ];

    // Tipos de tarea
    const TIPOS = [
        'instalacion'   => 'Instalación',
        'reconexion'    => 'Reconexión',
        'service'       => 'Service/Mantenimiento',
        'desconexion'   => 'Desconexión',
        'mantenimiento' => 'Mantenimiento',
        'soporte'       => 'Soporte Técnico'
    ];

    /**
     * Relación: Una tarea pertenece a un empleado
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    /**
     * Relación: Una tarea pertenece a un móvil (1:1)
     */
    public function movil()
    {
        return $this->belongsTo(GrupoTrabajo::class, 'movil_id');
    }

    /**
     * Relación: Una tarea pertenece a una orden de trabajo
     */
    public function ordenTrabajo()
    {
        return $this->belongsTo(OrdenTrabajo::class, 'orden_trabajo_id');
    }

    /**
     * Get the user that owns the task (legacy).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
