<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // protected $primaryKey = 'id_cliente'; // Comentado porque la tabla usa 'id'

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
        'tipo_cliente',
        'es_premium',
        'documento',
        'observaciones',
        'activo'
    ];

    protected $casts = [
        'es_premium' => 'boolean',
        'activo' => 'boolean',
    ];

    // Tipos de cliente
    const TIPOS_CLIENTE = [
        'residencial' => 'Residencial',
        'comercial' => 'Comercial',
        'empresa' => 'Empresa',
        'hospital' => 'Hospital',
        'critico' => 'Servicio Crítico'
    ];

    /**
     * Relación: Un cliente puede hacer muchas solicitudes
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'cliente_id');
    }

    /**
     * Relación: Un cliente puede tener muchas órdenes de trabajo
     */
    public function ordenesTrabajos()
    {
        return $this->hasMany(OrdenTrabajo::class);
    }

    /**
     * Alias para la relación ordenesTrabajos (singular)
     */
    public function ordenesTrabajo()
    {
        return $this->hasMany(OrdenTrabajo::class);
    }

    /**
     * Scope para clientes premium
     */
    public function scopePremium($query)
    {
        return $query->where('es_premium', true);
    }

    /**
     * Scope para clientes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Accessor para obtener el tipo de cliente formateado
     */
    public function getTipoClienteFormateadoAttribute()
    {
        return self::TIPOS_CLIENTE[$this->tipo_cliente] ?? $this->tipo_cliente;
    }

    /**
     * Accessor para obtener el color del tipo de cliente
     */
    public function getColorTipoAttribute()
    {
        $colores = [
            'residencial' => 'info',
            'comercial' => 'primary',
            'empresa' => 'warning',
            'hospital' => 'danger',
            'critico' => 'danger'
        ];

        return $colores[$this->tipo_cliente] ?? 'secondary';
    }
}
