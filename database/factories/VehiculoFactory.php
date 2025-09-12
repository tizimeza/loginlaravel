<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehiculo>
 */
class VehiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tipos = ['transit', 'kangoo', 'partner'];
        $tipo = $this->faker->randomElement($tipos);
        
        // Configuración específica por tipo de furgoneta
        $configuraciones = [
            'transit' => [
                'marca' => 'Ford',
                'modelo' => 'Transit',
                'capacidad_carga' => 1500,
                'combustible' => 'Diesel'
            ],
            'kangoo' => [
                'marca' => 'Renault',
                'modelo' => 'Kangoo',
                'capacidad_carga' => 800,
                'combustible' => 'Diesel'
            ],
            'partner' => [
                'marca' => 'Peugeot',
                'modelo' => 'Partner',
                'capacidad_carga' => 1000,
                'combustible' => 'Diesel'
            ]
        ];
        
        $config = $configuraciones[$tipo];
        
        return [
            'patente' => $this->generarPatente(),
            'tipo_vehiculo' => $tipo,
            'marca' => $config['marca'],
            'modelo' => $config['modelo'],
            'color' => $this->faker->randomElement(['Blanco', 'Azul', 'Gris', 'Negro']),
            'anio' => $this->faker->numberBetween(2018, 2024),
            'capacidad_carga' => $config['capacidad_carga'],
            'combustible' => $config['combustible'],
            'fecha_vencimiento_vtv' => $this->faker->dateTimeBetween('+6 months', '+18 months'),
            'fecha_cambio_neumaticos' => $this->faker->dateTimeBetween('+3 months', '+12 months'),
            'servicios_pendientes' => $this->faker->optional(0.3)->paragraph(),
            'estado' => $this->faker->randomElement(['disponible', 'disponible', 'disponible', 'en_uso']), // Más probabilidad de disponible
            'kilometraje' => $this->faker->numberBetween(10000, 150000),
            'observaciones' => $this->faker->optional(0.4)->paragraph(),
        ];
    }

    /**
     * Generar patente argentina
     */
    private function generarPatente()
    {
        $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeros = '0123456789';
        
        // Formato: ABC 123 o AB 123 CD
        if ($this->faker->boolean()) {
            // Formato viejo: ABC 123
            return $this->faker->randomElement(str_split($letras), 3) . ' ' . 
                   $this->faker->randomElement(str_split($numeros), 3);
        } else {
            // Formato nuevo: AB 123 CD
            return $this->faker->randomElement(str_split($letras), 2) . ' ' . 
                   $this->faker->randomElement(str_split($numeros), 3) . ' ' . 
                   $this->faker->randomElement(str_split($letras), 2);
        }
    }

    /**
     * Estado específico para furgonetas en mantenimiento
     */
    public function enMantenimiento()
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'mantenimiento',
                'servicios_pendientes' => 'Revisión general, cambio de aceite y filtros',
            ];
        });
    }

    /**
     * Estado específico para furgonetas fuera de servicio
     */
    public function fueraServicio()
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'fuera_servicio',
                'servicios_pendientes' => 'Reparación mayor requerida',
            ];
        });
    }
}
