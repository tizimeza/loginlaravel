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
        // Crear clientes de ejemplo para TecnoServi
        $clientes = [
            [
                'nombre' => 'Hospital San Rafael',
                'email' => 'it@hospitalsanrafael.com',
                'telefono' => '011-4567-8900',
                'direccion' => 'Av. Corrientes 1234, CABA',
                'tipo_cliente' => 'hospital',
                'es_premium' => true,
                'documento' => '30-12345678-9',
                'observaciones' => 'Cliente crítico - Servicio 24/7',
                'activo' => true
            ],
            [
                'nombre' => 'Empresa TechCorp S.A.',
                'email' => 'sistemas@techcorp.com',
                'telefono' => '011-2345-6789',
                'direccion' => 'Av. Santa Fe 5678, CABA',
                'tipo_cliente' => 'empresa',
                'es_premium' => true,
                'documento' => '30-87654321-0',
                'observaciones' => 'Cliente empresarial con múltiples sucursales',
                'activo' => true
            ],
            [
                'nombre' => 'Juan Pérez',
                'email' => 'juan.perez@email.com',
                'telefono' => '011-1234-5678',
                'direccion' => 'Av. Rivadavia 123, CABA',
                'tipo_cliente' => 'residencial',
                'es_premium' => false,
                'documento' => '20-12345678-9',
                'observaciones' => 'Cliente residencial estándar',
                'activo' => true
            ],
            [
                'nombre' => 'María González',
                'email' => 'maria.gonzalez@email.com',
                'telefono' => '011-9876-5432',
                'direccion' => 'Calle Falsa 456, CABA',
                'tipo_cliente' => 'residencial',
                'es_premium' => false,
                'documento' => '27-98765432-1',
                'observaciones' => 'Cliente residencial - Zona céntrica',
                'activo' => true
            ],
            [
                'nombre' => 'Comercio El Buen Precio',
                'email' => 'info@buenprecio.com',
                'telefono' => '011-3456-7890',
                'direccion' => 'Av. Córdoba 789, CABA',
                'tipo_cliente' => 'comercial',
                'es_premium' => false,
                'documento' => '30-11223344-5',
                'observaciones' => 'Comercio local - Horario comercial',
                'activo' => true
            ],
            [
                'nombre' => 'Centro Médico Integral',
                'email' => 'admin@centromedico.com',
                'telefono' => '011-4567-8901',
                'direccion' => 'Av. Callao 321, CABA',
                'tipo_cliente' => 'critico',
                'es_premium' => true,
                'documento' => '30-55667788-9',
                'observaciones' => 'Centro médico - Prioridad alta',
                'activo' => true
            ]
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }

        $this->command->info('✅ 6 clientes de TecnoServi creados exitosamente:');
        $this->command->info('🏥 Hospital San Rafael (Premium)');
        $this->command->info('🏢 Empresa TechCorp S.A. (Premium)');
        $this->command->info('👤 Juan Pérez (Residencial)');
        $this->command->info('👤 María González (Residencial)');
        $this->command->info('🏪 Comercio El Buen Precio (Comercial)');
        $this->command->info('🏥 Centro Médico Integral (Crítico)');
    }
}