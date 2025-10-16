<?php

namespace Database\Seeders;

use App\Models\Vehiculo;
use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar vehículos existentes (opcional)
        Vehiculo::truncate();
        $this->command->info('Vehículos anteriores eliminados.');

        // Vehículos de TecnoServi con datos completos
        $vehiculos = [
            // Ford Transit - Furgoneta principal
            [
                'patente' => 'ABC 123',
                'tipo_vehiculo' => 'transit',
                'marca_nombre' => 'Ford',
                'modelo_nombre' => 'Transit',
                'color' => 'Blanco',
                'anio' => 2022,
                'capacidad_carga' => 1500,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(8),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(6),
                'estado' => 'disponible',
                'kilometraje' => 45000,
                'observaciones' => 'Furgoneta principal para instalaciones grandes. Equipada con herramientas completas.',
                'servicios_pendientes' => null,
                'activo' => true
            ],

            // Renault Kangoo - Urbana
            [
                'patente' => 'XY 456 Z',
                'tipo_vehiculo' => 'kangoo',
                'marca_nombre' => 'Renault',
                'modelo_nombre' => 'Kangoo',
                'color' => 'Azul',
                'anio' => 2021,
                'capacidad_carga' => 800,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(12),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(4),
                'estado' => 'disponible',
                'kilometraje' => 62000,
                'observaciones' => 'Ideal para trabajos en zonas urbanas estrechas. Excelente maniobrabilidad.',
                'servicios_pendientes' => 'Cambio de aceite programado para la próxima semana',
                'activo' => true
            ],

            // Peugeot Partner - Nueva
            [
                'patente' => 'DE 789 F',
                'tipo_vehiculo' => 'kangoo',
                'marca_nombre' => 'Peugeot',
                'modelo_nombre' => 'Partner',
                'color' => 'Gris',
                'anio' => 2023,
                'capacidad_carga' => 1000,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(15),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(10),
                'estado' => 'disponible',
                'kilometraje' => 18000,
                'observaciones' => 'Furgoneta nueva, excelente estado. Ideal para todo tipo de servicios.',
                'servicios_pendientes' => null,
                'activo' => true
            ],

            // Chevrolet Corsa Pick-up
            [
                'patente' => 'GH 234 I',
                'tipo_vehiculo' => 'partner',
                'marca_nombre' => 'Chevrolet',
                'modelo_nombre' => 'Montana',
                'color' => 'Rojo',
                'anio' => 2020,
                'capacidad_carga' => 700,
                'combustible' => 'nafta',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(5),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(3),
                'estado' => 'disponible',
                'kilometraje' => 85000,
                'observaciones' => 'Camioneta para trabajos de mantenimiento rápido. Buena para traslado de equipos.',
                'servicios_pendientes' => 'Revisar frenos',
                'activo' => true
            ],

            // Volkswagen Amarok
            [
                'patente' => 'JK 567 L',
                'tipo_vehiculo' => 'partner',
                'marca_nombre' => 'Volkswagen',
                'modelo_nombre' => 'Amarok',
                'color' => 'Negro',
                'anio' => 2021,
                'capacidad_carga' => 1200,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(9),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(7),
                'estado' => 'en_uso',
                'kilometraje' => 52000,
                'observaciones' => 'Camioneta robusta para instalaciones en zonas alejadas. 4x4.',
                'servicios_pendientes' => null,
                'activo' => true
            ],

            // Fiat Fiorino
            [
                'patente' => 'MN 890 O',
                'tipo_vehiculo' => 'kangoo',
                'marca_nombre' => 'Fiat',
                'modelo_nombre' => 'Fiorino',
                'color' => 'Blanco',
                'anio' => 2019,
                'capacidad_carga' => 600,
                'combustible' => 'nafta',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(3),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(2),
                'estado' => 'disponible',
                'kilometraje' => 95000,
                'observaciones' => 'Utilitario compacto para entregas rápidas y servicios urbanos.',
                'servicios_pendientes' => 'Service de 100.000 km próximo',
                'activo' => true
            ],

            // Mercedes-Benz Sprinter
            [
                'patente' => 'PQ 123 R',
                'tipo_vehiculo' => 'transit',
                'marca_nombre' => 'Mercedes-Benz',
                'modelo_nombre' => 'Sprinter',
                'color' => 'Blanco',
                'anio' => 2022,
                'capacidad_carga' => 1800,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(14),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(8),
                'estado' => 'disponible',
                'kilometraje' => 32000,
                'observaciones' => 'Furgoneta de alta capacidad para proyectos grandes. Climatizada.',
                'servicios_pendientes' => null,
                'activo' => true
            ],

            // Toyota Hilux
            [
                'patente' => 'ST 456 U',
                'tipo_vehiculo' => 'partner',
                'marca_nombre' => 'Toyota',
                'modelo_nombre' => 'Hilux',
                'color' => 'Plateado',
                'anio' => 2021,
                'capacidad_carga' => 1100,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(10),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(5),
                'estado' => 'disponible',
                'kilometraje' => 58000,
                'observaciones' => 'Camioneta doble cabina. Ideal para equipos de trabajo y zonas rurales.',
                'servicios_pendientes' => null,
                'activo' => true
            ],

            // Nissan NP300
            [
                'patente' => 'VW 789 X',
                'tipo_vehiculo' => 'partner',
                'marca_nombre' => 'Nissan',
                'modelo_nombre' => 'NP300',
                'color' => 'Azul',
                'anio' => 2020,
                'capacidad_carga' => 1000,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(6),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(4),
                'estado' => 'mantenimiento',
                'kilometraje' => 72000,
                'observaciones' => 'En mantenimiento por reparación de motor. Regresa la próxima semana.',
                'servicios_pendientes' => 'Reparación de sistema de inyección',
                'activo' => true
            ],

            // Citroen Berlingo
            [
                'patente' => 'YZ 012 A',
                'tipo_vehiculo' => 'kangoo',
                'marca_nombre' => 'Citroen',
                'modelo_nombre' => 'Berlingo',
                'color' => 'Blanco',
                'anio' => 2021,
                'capacidad_carga' => 850,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addMonths(11),
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(6),
                'estado' => 'disponible',
                'kilometraje' => 48000,
                'observaciones' => 'Utilitario versátil para servicios medianos. Buen consumo de combustible.',
                'servicios_pendientes' => null,
                'activo' => true
            ],

            // Vehículo inactivo (ejemplo)
            [
                'patente' => 'BC 345 D',
                'tipo_vehiculo' => 'partner',
                'marca_nombre' => 'Ford',
                'modelo_nombre' => 'Ranger',
                'color' => 'Verde',
                'anio' => 2018,
                'capacidad_carga' => 1050,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->subMonths(2), // VTV vencida
                'fecha_cambio_neumaticos' => Carbon::now()->subMonths(1),
                'estado' => 'fuera_servicio',
                'kilometraje' => 150000,
                'observaciones' => 'Fuera de servicio por alto kilometraje. Pendiente de decisión sobre reemplazo.',
                'servicios_pendientes' => 'Evaluación completa del estado mecánico',
                'activo' => false
            ],

            // Vehículo con VTV próxima a vencer (ejemplo de alerta)
            [
                'patente' => 'EF 678 G',
                'tipo_vehiculo' => 'kangoo',
                'marca_nombre' => 'Renault',
                'modelo_nombre' => 'Kangoo',
                'color' => 'Gris',
                'anio' => 2020,
                'capacidad_carga' => 800,
                'combustible' => 'diesel',
                'fecha_vencimiento_vtv' => Carbon::now()->addDays(20), // VTV próxima a vencer
                'fecha_cambio_neumaticos' => Carbon::now()->addMonths(5),
                'estado' => 'disponible',
                'kilometraje' => 68000,
                'observaciones' => '¡ATENCIÓN! VTV vence pronto. Programar inspección urgente.',
                'servicios_pendientes' => 'Inspección VTV programada',
                'activo' => true
            ],
        ];

        // Crear los vehículos
        foreach ($vehiculos as $vehiculoData) {
            // Buscar la marca
            $marca = Marca::where('nombre', $vehiculoData['marca_nombre'])->first();

            if (!$marca) {
                // Crear la marca si no existe
                $marca = Marca::create(['nombre' => $vehiculoData['marca_nombre']]);
                $this->command->info("Marca creada: {$vehiculoData['marca_nombre']}");
            }

            // Buscar o crear el modelo
            $modelo = Modelo::firstOrCreate(
                [
                    'nombre' => $vehiculoData['modelo_nombre'],
                    'marca_id' => $marca->id
                ]
            );

            // Preparar datos del vehículo
            $vehiculoDataFinal = [
                'patente' => $vehiculoData['patente'],
                'tipo_vehiculo' => $vehiculoData['tipo_vehiculo'],
                'marca' => $vehiculoData['marca_nombre'],
                'modelo' => $vehiculoData['modelo_nombre'],
                'modelo_id' => $modelo->id,
                'color' => $vehiculoData['color'],
                'anio' => $vehiculoData['anio'],
                'capacidad_carga' => $vehiculoData['capacidad_carga'],
                'combustible' => $vehiculoData['combustible'],
                'fecha_vencimiento_vtv' => $vehiculoData['fecha_vencimiento_vtv'],
                'fecha_cambio_neumaticos' => $vehiculoData['fecha_cambio_neumaticos'],
                'estado' => $vehiculoData['estado'],
                'kilometraje' => $vehiculoData['kilometraje'],
                'observaciones' => $vehiculoData['observaciones'],
                'servicios_pendientes' => $vehiculoData['servicios_pendientes'],
            ];

            // Crear el vehículo
            $vehiculo = Vehiculo::create($vehiculoDataFinal);

            $estadoColor = match($vehiculo->estado) {
                'disponible' => '🟢',
                'en_uso' => '🟡',
                'mantenimiento' => '🟠',
                'fuera_servicio' => '🔴',
                default => '⚪'
            };

            $this->command->info("{$estadoColor} Vehículo creado: {$vehiculoData['marca_nombre']} {$vehiculoData['modelo_nombre']} - {$vehiculoData['patente']} ({$vehiculo->estado})");
        }

        $this->command->info('');
        $this->command->info('✅ Proceso de creación de vehículos completado.');
        $this->command->info("📊 Total de vehículos creados: " . count($vehiculos));
        $this->command->info("🟢 Disponibles: " . collect($vehiculos)->where('estado', 'disponible')->count());
        $this->command->info("🟡 En uso: " . collect($vehiculos)->where('estado', 'en_uso')->count());
        $this->command->info("🟠 En mantenimiento: " . collect($vehiculos)->where('estado', 'mantenimiento')->count());
        $this->command->info("🔴 Fuera de servicio: " . collect($vehiculos)->where('estado', 'fuera_servicio')->count());
    }
}
