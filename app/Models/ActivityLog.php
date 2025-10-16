<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_type',
        'subject_id',
        'user_id',
        'event',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que realizó la acción
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación polimórfica con el modelo afectado
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Scope para filtrar por tipo de evento
     */
    public function scopeEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope para filtrar por tipo de modelo
     */
    public function scopeForModel($query, $modelType)
    {
        return $query->where('subject_type', $modelType);
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Obtener una descripción legible del cambio
     */
    public function getFormattedChangesAttribute()
    {
        if (empty($this->old_values) && empty($this->new_values)) {
            return 'Sin cambios registrados';
        }

        $changes = [];
        $newValues = $this->new_values ?? [];
        $oldValues = $this->old_values ?? [];

        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? 'N/A';
            $changes[] = "<strong>{$key}:</strong> {$oldValue} → {$newValue}";
        }

        return implode('<br>', $changes);
    }

    /**
     * Obtener nombre legible del modelo
     */
    public function getModelNameAttribute()
    {
        $modelNames = [
            'App\\Models\\Cliente' => 'Cliente',
            'App\\Models\\OrdenTrabajo' => 'Orden de Trabajo',
            'App\\Models\\Vehiculo' => 'Vehículo',
            'App\\Models\\Stock' => 'Producto',
            'App\\Models\\GrupoTrabajo' => 'Grupo de Trabajo',
            'App\\Models\\User' => 'Usuario',
        ];

        return $modelNames[$this->subject_type] ?? class_basename($this->subject_type);
    }

    /**
     * Obtener icono según el evento
     */
    public function getEventIconAttribute()
    {
        $icons = [
            'created' => 'bi-plus-circle-fill text-success',
            'updated' => 'bi-pencil-fill text-warning',
            'deleted' => 'bi-trash-fill text-danger',
            'restored' => 'bi-arrow-counterclockwise text-info',
        ];

        return $icons[$this->event] ?? 'bi-circle-fill text-secondary';
    }

    /**
     * Obtener texto del evento en español
     */
    public function getEventTextAttribute()
    {
        $events = [
            'created' => 'Creado',
            'updated' => 'Actualizado',
            'deleted' => 'Eliminado',
            'restored' => 'Restaurado',
        ];

        return $events[$this->event] ?? ucfirst($this->event);
    }
}
