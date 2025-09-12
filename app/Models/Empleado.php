<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'documento',
        'fecha_nacimiento',
        'fecha_ingreso',
        'estado',
        'skills',
        'observaciones'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
        'skills' => 'array',
    ];

    // Estados del empleado
    const ESTADOS = [
        'activo'     => 'Activo',
        'inactivo'   => 'Inactivo',
        'vacaciones' => 'En Vacaciones',
        'licencia'   => 'En Licencia'
    ];

    // Skills disponibles
    const SKILLS_DISPONIBLES = [
        'instalacion'   => 'Instalación de servicio',
        'reconexion'    => 'Reconexión',
        'service'       => 'Mantenimiento / Service',
        'desconexion'   => 'Desconexión',
        'cableado'      => 'Cableado estructurado',
        'fibra_optica'  => 'Fibra Óptica',
        'wifi'          => 'Configuración Wi-Fi',
        'soporte'       => 'Soporte técnico',
        'liderazgo'     => 'Liderazgo de equipo',
        'emergencias'   => 'Servicios de emergencia'
    ];

    /**
     * Relación: Un empleado puede estar en varios móviles (many-to-many)
     */
    public function moviles()
    {
        return $this->belongsToMany(GrupoTrabajo::class, 'movil_empleado', 'empleado_id', 'movil_id')
                    ->withTimestamps();
    }

    /**
     * Relación: Un empleado puede ser líder de varios móviles
     */
    public function movilesLiderados()
    {
        return $this->hasMany(GrupoTrabajo::class, 'lider_id');
    }

    /**
     * Relación: Un empleado puede tener muchas tareas asignadas
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'empleado_id');
    }

    /**
     * Scope para empleados activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para empleados disponibles (activos y no en vacaciones)
     */
    public function scopeDisponibles($query)
    {
        return $query->whereIn('estado', ['activo']);
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    /**
     * Accessor para estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    /**
     * Accessor para skills formateadas
     */
    public function getSkillsFormateadasAttribute()
    {
        if (!$this->skills) return [];
        
        return array_map(function($skill) {
            return self::SKILLS_DISPONIBLES[$skill] ?? $skill;
        }, $this->skills);
    }

    /**
     * Verificar si el empleado tiene una skill específica
     */
    public function tieneSkill($skill)
    {
        return in_array($skill, $this->skills ?? []);
    }

    /**
     * Agregar una skill al empleado
     */
    public function agregarSkill($skill)
    {
        $skills = $this->skills ?? [];
        if (!in_array($skill, $skills)) {
            $skills[] = $skill;
            $this->update(['skills' => $skills]);
        }
    }

    /**
     * Remover una skill del empleado
     */
    public function removerSkill($skill)
    {
        $skills = $this->skills ?? [];
        $skills = array_diff($skills, [$skill]);
        $this->update(['skills' => array_values($skills)]);
    }

    /**
     * Verificar si el empleado está disponible para asignación
     */
    public function estaDisponible()
    {
        return $this->estado === 'activo';
    }
}