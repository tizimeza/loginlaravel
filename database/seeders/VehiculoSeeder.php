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
        // Crear las 3 furgonetas específicas de TecnoServi
        $furgonetas = [
            [
                'patente' => 'ABC 123',
                'tipo_vehiculo' => 'transit',
                'marca' => 'Ford',
                'modelo' => 'Transit',
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

        foreach ($furgonetas as $furgonetaData) {
            // Buscar o crear el modelo correspondiente
            $marca = \App\Models\Marca::where('nombre', $furgonetaData['marca'])->first();

            if ($marca) {
                $modelo = \App\Models\Modelo::firstOrCreate(
                    [
                        'nombre' => $furgonetaData['modelo'],
                        'marca_id' => $marca->id
                    ]
                );

                // Agregar el modelo_id a los datos del vehículo
                $furgonetaData['modelo_id'] = $modelo->id;

                // Crear el vehículo
                Vehiculo::firstOrCreate(
                    ['patente' => $furgonetaData['patente']],
                    $furgonetaData
                );

                $this->command->info("Vehículo creado: {$furgonetaData['marca']} {$furgonetaData['modelo']} - {$furgonetaData['patente']}");
            } else {
                $this->command->error("No se encontró la marca: {$furgonetaData['marca']} para el vehículo {$furgonetaData['patente']}");
            }
        }

        $this->command->info('Proceso de creación de furgonetas de TecnoServi completado.');
    }
}
