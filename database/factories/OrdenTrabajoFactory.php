<?php

namespace Database\Factories;

use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdenTrabajo>
 */
class OrdenTrabajoFactory extends Factory
{
    protected $model = OrdenTrabajo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'numero_orden' => OrdenTrabajo::generarNumeroOrden(),
            'vehiculo_id' => 1, // Se sobrescribirá en el seeder
            'cliente_nombre' => $this->faker->name(),
            'cliente_telefono' => $this->faker->phoneNumber(),
            'cliente_email' => $this->faker->safeEmail(),
            'descripcion_problema' => $this->faker->paragraph(3),
            'fecha_ingreso' => now()->subDays(rand(1, 30)),
            'fecha_estimada_entrega' => now()->addDays(rand(1, 15)),
            'fecha_entrega_real' => null,
            'estado' => $this->faker->randomElement(array_keys(OrdenTrabajo::ESTADOS)),
            'prioridad' => $this->faker->randomElement(array_keys(OrdenTrabajo::PRIORIDADES)),
            'costo_estimado' => $this->faker->randomFloat(2, 100, 5000),
            'costo_final' => null,
            'observaciones' => $this->faker->optional()->paragraph(),
            'user_id' => 1, // Se sobrescribirá en el seeder
        ];
    }

    /**
     * Estado específico: pendiente
     */
    public function pendiente()
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'pendiente',
                'fecha_entrega_real' => null,
                'costo_final' => null,
            ];
        });
    }

    /**
     * Estado específico: completado
     */
    public function completado()
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'completado',
                'fecha_entrega_real' => now()->subDays(rand(1, 5)),
                'costo_final' => $this->faker->randomFloat(2, 100, 6000),
            ];
        });
    }

    /**
     * Prioridad alta
     */
    public function prioridadAlta()
    {
        return $this->state(function (array $attributes) {
            return [
                'prioridad' => 'alta',
            ];
        });
    }

    /**
     * Prioridad urgente
     */
    public function urgente()
    {
        return $this->state(function (array $attributes) {
            return [
                'prioridad' => 'urgente',
                'estado' => 'en_proceso',
            ];
        });
    }
}
