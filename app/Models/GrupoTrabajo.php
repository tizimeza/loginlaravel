<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoTrabajo extends Model
{
    use HasFactory;

    protected $table = 'grupos_trabajo';
    // protected $primaryKey = 'id_movil'; // Comentado porque la tabla usa 'id'

    protected $fillable = [
        'nombre',
        'descripcion',
        'lider_id',
        'vehiculo_id',
        'activo',
        'color',
        'especialidad',
        'capacidad_maxima',
        'zona_trabajo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Especialidades de los móviles (TecnoServi - Servicios de Internet/Telefonía)
    const ESPECIALIDADES = [
        'instalacion'   => 'Instalación de servicio',
        'reconexion'    => 'Reconexión',
        'service'       => 'Mantenimiento / Service',
        'desconexion'   => 'Desconexión',
        'cableado'      => 'Cableado estructurado',
        'fibra_optica'  => 'Fibra Óptica',
        'wifi'          => 'Configuración Wi-Fi',
        'soporte'       => 'Soporte técnico',
        'general'       => 'Servicios generales'
    ];

    // Zonas de trabajo
    const ZONAS_TRABAJO = [
        'centro'        => 'Centro',
        'norte'         => 'Zona Norte',
        'sur'           => 'Zona Sur',
        'este'          => 'Zona Este',
        'oeste'         => 'Zona Oeste',
        'suburbios'     => 'Suburbios',
        'industrial'    => 'Zona Industrial',
        'comercial'     => 'Zona Comercial',
        'residencial'   => 'Zona Residencial'
    ];

    // Colores disponibles para identificar grupos en el dashboard
    const COLORES = [
        'primary'   => 'Azul',
        'success'   => 'Verde',
        'warning'   => 'Amarillo',
        'danger'    => 'Rojo',
        'info'      => 'Celeste',
        'secondary' => 'Gris',
        'dark'      => 'Negro',
        'purple'    => 'Morado',
        'orange'    => 'Naranja'
    ];

    /**
     * Relación: Un grupo tiene un líder (usuario)
     */
    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    /**
     * Relación: Un grupo tiene un vehículo asignado
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    /**
     * Relación: Un móvil tiene muchos empleados (2-3 empleados)
     */
    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'movil_empleado', 'movil_id', 'empleado_id')
                    ->withTimestamps();
    }

    /**
     * Relación: Un grupo tiene muchos miembros (usuarios) - legacy
     */
    public function miembros()
    {
        return $this->belongsToMany(User::class, 'grupo_trabajo_user', 'grupo_trabajo_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Relación: Un móvil puede tener muchas tareas asignadas
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'movil_id');
    }

    /**
     * Relación: Un grupo puede tener muchas órdenes de trabajo asignadas
     */
    public function ordenesAsignadas()
    {
        return $this->hasMany(OrdenTrabajo::class, 'grupo_trabajo_id');
    }

    /**
     * Scope para grupos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por especialidad
     */
    public function scopeConEspecialidad($query, $especialidad)
    {
        return $query->where('especialidad', $especialidad);
    }

    /**
     * Accessor para obtener la especialidad formateada
     */
    public function getEspecialidadFormateadaAttribute()
    {
        return self::ESPECIALIDADES[$this->especialidad] ?? $this->especialidad;
    }

    /**
     * Accessor para obtener el color formateado
     */
    public function getColorFormateadoAttribute()
    {
        return self::COLORES[$this->color] ?? $this->color;
    }

    /**
     * Accessor para obtener el número de miembros
     */
    public function getNumeroMiembrosAttribute()
    {
        return $this->miembros()->count();
    }

    /**
     * Accessor para obtener el número de órdenes activas
     */
    public function getOrdenesActivasAttribute()
    {
        return $this->ordenesAsignadas()
                    ->whereIn('estado', ['nueva', 'vista', 'en_proceso'])
                    ->count();
    }

    /**
     * Verificar si un usuario es miembro del grupo
     */
    public function esMiembro($userId)
    {
        return $this->miembros()->where('user_id', $userId)->exists();
    }

    /**
     * Verificar si un usuario es el líder del grupo
     */
    public function esLider($userId)
    {
        return $this->lider_id == $userId;
    }

    /**
     * Agregar miembro al grupo
     */
    public function agregarMiembro($userId)
    {
        if (!$this->esMiembro($userId)) {
            $this->miembros()->attach($userId);
        }
    }

    /**
     * Remover miembro del grupo
     */
    public function removerMiembro($userId)
    {
        $this->miembros()->detach($userId);
    }

    /**
     * Obtener estadísticas del grupo
     */
    public function getEstadisticas()
    {
        return [
            'total_miembros'      => $this->numero_miembros,
            'ordenes_activas'     => $this->ordenes_activas,
            'ordenes_completadas' => $this->ordenesAsignadas()->where('estado', 'terminada')->count(),
            'ordenes_fallidas'    => $this->ordenesAsignadas()->where('estado', 'no_terminada')->count(),
        ];
    }
}
