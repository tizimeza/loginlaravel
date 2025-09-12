<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'patente', 'tipo_vehiculo', 'marca', 'modelo', 'color', 'anio', 
        'capacidad_carga', 'combustible', 'fecha_vencimiento_vtv', 
        'fecha_cambio_neumaticos', 'servicios_pendientes', 'estado', 
        'kilometraje', 'observaciones', 'imagen'
    ];

    protected $casts = [
        'fecha_vencimiento_vtv' => 'date',
        'fecha_cambio_neumaticos' => 'date',
        'capacidad_carga' => 'integer',
        'kilometraje' => 'integer',
    ];

    // Tipos de vehículos de TecnoServi
    const TIPOS_VEHICULO = [
        'transit' => 'Ford Transit',
        'kangoo'  => 'Renault Kangoo',
        'partner' => 'Peugeot Partner'
    ];

    // Estados del vehículo
    const ESTADOS = [
        'disponible'    => 'Disponible',
        'en_uso'        => 'En Uso',
        'mantenimiento' => 'En Mantenimiento',
        'fuera_servicio' => 'Fuera de Servicio'
    ];

    // Marcas disponibles
    const MARCAS = [
        'Ford'    => 'Ford',
        'Renault' => 'Renault',
        'Peugeot' => 'Peugeot'
    ];

    /**
     * Relación: un Vehículo pertenece a un Modelo
     */
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    /**
     * Relación: un Vehículo puede tener muchas órdenes de trabajo
     */
    public function ordenesTrabajos()
    {
        return $this->hasMany(OrdenTrabajo::class);
    }

    /**
     * Scope para vehículos disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }

    /**
     * Scope para vehículos en mantenimiento
     */
    public function scopeEnMantenimiento($query)
    {
        return $query->where('estado', 'mantenimiento');
    }

    /**
     * Accessor para obtener el tipo de vehículo formateado
     */
    public function getTipoVehiculoFormateadoAttribute()
    {
        return self::TIPOS_VEHICULO[$this->tipo_vehiculo] ?? $this->tipo_vehiculo;
    }

    /**
     * Accessor para obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    /**
     * Accessor para obtener el color del estado
     */
    public function getColorEstadoAttribute()
    {
        $colores = [
            'disponible'    => 'success',
            'en_uso'        => 'info',
            'mantenimiento' => 'warning',
            'fuera_servicio' => 'danger'
        ];

        return $colores[$this->estado] ?? 'secondary';
    }

    /**
     * Verificar si el vehículo necesita VTV
     */
    public function necesitaVTV()
    {
        if (!$this->fecha_vencimiento_vtv) {
            return false;
        }
        
        return $this->fecha_vencimiento_vtv->diffInDays(now()) <= 30;
    }

    /**
     * Verificar si el vehículo necesita cambio de neumáticos
     */
    public function necesitaCambioNeumaticos()
    {
        if (!$this->fecha_cambio_neumaticos) {
            return false;
        }
        
        return $this->fecha_cambio_neumaticos->diffInDays(now()) <= 30;
    }

    /**
     * Obtener alertas del vehículo
     */
    public function getAlertasAttribute()
    {
        $alertas = [];
        
        if ($this->necesitaVTV()) {
            $alertas[] = 'VTV próxima a vencer';
        }
        
        if ($this->necesitaCambioNeumaticos()) {
            $alertas[] = 'Cambio de neumáticos próximo';
        }
        
        if ($this->kilometraje > 100000) {
            $alertas[] = 'Kilometraje alto - revisar mantenimiento';
        }
        
        return $alertas;
    }
}
