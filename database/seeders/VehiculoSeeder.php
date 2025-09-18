<?php

namespace Database\Seeders;

use App\Models\Vehiculo;
use App\Models\Modelo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener un modelo existente o crear uno por defecto
        $modelo = Modelo::first();
        if (!$modelo) {
            $modelo = Modelo::create([
                'nombre' => 'Modelo Genérico',
                'marca_id' => 1
            ]);
        }

        // Crear las 3 furgonetas específicas de TecnoServi
        $furgonetas = [
            [
                'patente' => 'ABC 123',
                'tipo_vehiculo' => 'transit',
                'marca' => 'Ford',
                'modelo' => 'Transit',
                'modelo_id' => $modelo->id,
                'color' => 'Blanco',
                'anio' => 2022,
                'capacidad_carga' => 1500,
                'combustible' => 'Diesel',
                'fecha_vencimiento_vtv' => now()->addMonths(8),
                'fecha_cambio_neumaticos' => now()->addMonths(6),
                'estado' => 'disponible',
                'kilometraje' => 45000,
                'observaciones' => 'Furgoneta principal para instalaciones grandes'
            ],
            [
                'patente' => 'XY 456 Z',
                'tipo_vehiculo' => 'kangoo',
                'marca' => 'Renault',
                'modelo' => 'Kangoo',
                'modelo_id' => $modelo->id,
                'color' => 'Azul',
                'anio' => 2021,
                'capacidad_carga' => 800,
                'combustible' => 'Diesel',
                'fecha_vencimiento_vtv' => now()->addMonths(12),
                'fecha_cambio_neumaticos' => now()->addMonths(4),
                'estado' => 'disponible',
                'kilometraje' => 62000,
                'observaciones' => 'Ideal para trabajos en zonas urbanas estrechas'
            ],
            [
                'patente' => 'DE 789 F',
                'tipo_vehiculo' => 'partner',
                'marca' => 'Peugeot',
                'modelo' => 'Partner',
                'modelo_id' => $modelo->id,
                'color' => 'Gris',
                'anio' => 2023,
                'capacidad_carga' => 1000,
                'combustible' => 'Diesel',
                'fecha_vencimiento_vtv' => now()->addMonths(15),
                'fecha_cambio_neumaticos' => now()->addMonths(10),
                'estado' => 'disponible',
                'kilometraje' => 18000,
                'observaciones' => 'Furgoneta nueva, excelente estado'
            ]
        ];

        foreach ($furgonetas as $furgoneta) {
            Vehiculo::firstOrCreate(
                ['patente' => $furgoneta['patente']],
                $furgoneta
            );
        }

        $this->command->info('3 furgonetas de TecnoServi creadas exitosamente:');
        $this->command->info('Ford Transit - ABC 123');
        $this->command->info('Renault Kangoo - XY 456 Z');
        $this->command->info('Peugeot Partner - DE 789 F');
    }
}
