<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear clientes de ejemplo para TecnoServi - Misiones
        $clientes = [
            [
                'nombre' => 'Hospital Escuela de Agudos Dr. Ramón Madariaga',
                'email' => 'sistemas@hospitalmadariaga.gob.ar',
                'telefono' => '0376-444-2500',
                'direccion' => 'Av. López y Planes 1515, Posadas',
                'tipo_cliente' => 'hospital',
                'es_premium' => true,
                'documento' => '30-71234567-8',
                'observaciones' => 'Cliente crítico - Servicio 24/7',
                'activo' => true
            ],
            [
                'nombre' => 'Papel Misionero S.A.',
                'email' => 'it@papelmisionero.com.ar',
                'telefono' => '0376-435-1200',
                'direccion' => 'Ruta Nacional 12 Km 1342, Puerto Rico',
                'tipo_cliente' => 'empresa',
                'es_premium' => true,
                'documento' => '30-54321098-7',
                'observaciones' => 'Cliente empresarial con múltiples plantas',
                'activo' => true
            ],
            [
                'nombre' => 'Carlos Ramirez',
                'email' => 'carlos.ramirez@hotmail.com',
                'telefono' => '0376-154-2345',
                'direccion' => 'Calle Corrientes 890, Posadas',
                'tipo_cliente' => 'residencial',
                'es_premium' => false,
                'documento' => '20-35678901-2',
                'observaciones' => 'Cliente residencial estándar',
                'activo' => true
            ],
            [
                'nombre' => 'Ana Beatriz Silva',
                'email' => 'anabeatriz.silva@gmail.com',
                'telefono' => '0376-155-6789',
                'direccion' => 'Av. Quaranta 1245, Posadas',
                'tipo_cliente' => 'residencial',
                'es_premium' => false,
                'documento' => '27-23456789-0',
                'observaciones' => 'Cliente residencial - Barrio Itaembé Guazú',
                'activo' => true
            ],
            [
                'nombre' => 'Supermercado San Martín',
                'email' => 'gerencia@supermercadosanmartin.com',
                'telefono' => '0376-442-5678',
                'direccion' => 'Av. San Martín 2345, Oberá',
                'tipo_cliente' => 'comercial',
                'es_premium' => false,
                'documento' => '30-98765432-1',
                'observaciones' => 'Comercio local - Horario extendido',
                'activo' => true
            ],
            [
                'nombre' => 'Clínica San José',
                'email' => 'administracion@clinicasanjose.com.ar',
                'telefono' => '0376-444-7890',
                'direccion' => 'Calle Bolívar 567, Posadas',
                'tipo_cliente' => 'critico',
                'es_premium' => true,
                'documento' => '30-11223344-6',
                'observaciones' => 'Centro médico privado - Prioridad alta',
                'activo' => true
            ],
            [
                'nombre' => 'Municipalidad de Eldorado',
                'email' => 'sistemas@eldorado.gob.ar',
                'telefono' => '0375-421-3456',
                'direccion' => 'Av. San Martín 915, Eldorado',
                'tipo_cliente' => 'empresa',
                'es_premium' => true,
                'documento' => '30-99887766-5',
                'observaciones' => 'Organismo público - Servicios municipales',
                'activo' => true
            ],
            [
                'nombre' => 'Roberto Fernández',
                'email' => 'roberto.fernandez@yahoo.com.ar',
                'telefono' => '0376-156-4321',
                'direccion' => 'Av. Mitre 678, Garupá',
                'tipo_cliente' => 'residencial',
                'es_premium' => false,
                'documento' => '20-45678912-3',
                'observaciones' => 'Cliente residencial - Zona metropolitana',
                'activo' => true
            ],
            [
                'nombre' => 'Aserradero Dos Hermanos',
                'email' => 'ventas@aserraderodoshermanos.com',
                'telefono' => '0375-422-8901',
                'direccion' => 'Ruta Provincial 17 Km 23, Montecarlo',
                'tipo_cliente' => 'empresa',
                'es_premium' => false,
                'documento' => '30-66554433-2',
                'observaciones' => 'Empresa forestal - Servicios industriales',
                'activo' => true
            ],
            [
                'nombre' => 'Farmacia Central',
                'email' => 'info@farmaciacentral.com.ar',
                'telefono' => '0376-443-5555',
                'direccion' => 'Calle Félix de Azara 123, Posadas',
                'tipo_cliente' => 'comercial',
                'es_premium' => false,
                'documento' => '30-77889900-1',
                'observaciones' => 'Farmacia céntrica - Horario nocturno',
                'activo' => true
            ]
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }

        $this->command->info('10 clientes de TecnoServi creados exitosamente para Misiones:');
        $this->command->info('Hospital Escuela Dr. Ramón Madariaga (Premium)');
        $this->command->info('Papel Misionero S.A. (Premium)');
        $this->command->info('Carlos Ramirez (Residencial)');
        $this->command->info('Ana Beatriz Silva (Residencial)');
        $this->command->info('Supermercado San Martín (Comercial)');
        $this->command->info('Clínica San José (Crítico)');
        $this->command->info('Municipalidad de Eldorado (Empresa)');
        $this->command->info('Roberto Fernández (Residencial)');
        $this->command->info('Aserradero Dos Hermanos (Empresa)');
        $this->command->info('Farmacia Central (Comercial)');
    }
}
