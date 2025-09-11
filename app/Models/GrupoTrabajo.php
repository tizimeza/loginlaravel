<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoTrabajo extends Model
{
    use HasFactory;

    protected $table = 'grupos_trabajo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'lider_id',
        'activo',
        'color',
        'especialidad'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Especialidades disponibles
    const ESPECIALIDADES = [
        'mecanica_general' => 'Mecánica General',
        'electricidad' => 'Electricidad Automotriz',
        'carroceria' => 'Carrocería y Pintura',
        'neumaticos' => 'Neumáticos y Alineación',
        'aire_acondicionado' => 'Aire Acondicionado',
        'frenos' => 'Sistema de Frenos',
        'transmision' => 'Transmisión',
        'motor' => 'Motor',
        'suspension' => 'Suspensión',
        'diagnostico' => 'Diagnóstico Computarizado'
    ];

    // Colores disponibles para identificación visual
    const COLORES = [
        'primary' => 'Azul',
        'success' => 'Verde',
        'warning' => 'Amarillo',
        'danger' => 'Rojo',
        'info' => 'Celeste',
        'secondary' => 'Gris',
        'dark' => 'Negro',
        'purple' => 'Morado',
        'pink' => 'Rosa',
        'orange' => 'Naranja'
    ];

    /**
     * Relación: Un grupo tiene un líder (usuario)
     */
    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    /**
     * Relación: Un grupo tiene muchos miembros (usuarios)
     */
    public function miembros()
    {
        return $this->belongsToMany(User::class, 'grupo_trabajo_user', 'grupo_trabajo_id', 'user_id')
                    ->withTimestamps();
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
                    ->whereIn('estado', ['pendiente', 'en_proceso', 'esperando_repuestos'])
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
            'total_miembros' => $this->numero_miembros,
            'ordenes_activas' => $this->ordenes_activas,
            'ordenes_completadas' => $this->ordenesAsignadas()->where('estado', 'completado')->count(),
            'ordenes_entregadas' => $this->ordenesAsignadas()->where('estado', 'entregado')->count(),
        ];
    }
}
