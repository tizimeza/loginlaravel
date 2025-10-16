<?php

namespace Database\Seeders;

use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdenTrabajoSeeder extends Seeder
{
    protected $faker;
    
    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verificar que hay vehículos, usuarios y clientes existentes
        $vehiculosCount = Vehiculo::count();
        $usuariosCount = User::count();
        $clientesCount = Cliente::count();

        if ($vehiculosCount === 0) {
            $this->command->info('No hay vehículos disponibles. Por favor, ejecuta primero el seeder de vehículos.');
            return;
        }

        if ($usuariosCount === 0) {
            $this->command->info('No hay usuarios disponibles. Por favor, crea usuarios primero.');
            return;
        }

        if ($clientesCount === 0) {
            $this->command->info('No hay clientes disponibles. Por favor, ejecuta primero el seeder de clientes.');
            return;
        }

        // Obtener IDs existentes para usar en las órdenes
        $vehiculoIds = Vehiculo::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        $clienteIds = Cliente::pluck('id')->toArray();

        // Crear órdenes de trabajo con diferentes estados
        for ($i = 0; $i < 5; $i++) {
            OrdenTrabajo::factory()->pendiente()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
                'cliente_id' => $this->faker->randomElement($clienteIds),
            ]);
        }

        for ($i = 0; $i < 3; $i++) {
            OrdenTrabajo::factory()->completado()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
                'cliente_id' => $this->faker->randomElement($clienteIds),
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            OrdenTrabajo::factory()->urgente()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
                'cliente_id' => $this->faker->randomElement($clienteIds),
            ]);
        }

        for ($i = 0; $i < 4; $i++) {
            OrdenTrabajo::factory()->prioridadAlta()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
                'cliente_id' => $this->faker->randomElement($clienteIds),
            ]);
        }

        // Crear algunas órdenes adicionales con estados variados
        for ($i = 0; $i < 6; $i++) {
            OrdenTrabajo::factory()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
                'cliente_id' => $this->faker->randomElement($clienteIds),
            ]);
        }

        $this->command->info('✅ 20 órdenes de trabajo creadas exitosamente con clientes asignados.');
    }
}
