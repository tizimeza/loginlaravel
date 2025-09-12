<?php

namespace Database\Seeders;

use App\Models\OrdenTrabajo;
use App\Models\Cliente;
use App\Models\GrupoTrabajo;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdenTrabajoTecnoServiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener datos existentes
        $clientes = Cliente::all();
        $gruposTrabajo = GrupoTrabajo::all();
        $tecnicos = User::all();
        $vehiculos = Vehiculo::all();

        if ($clientes->count() < 3 || $gruposTrabajo->count() < 3 || $tecnicos->count() < 3) {
            $this->command->warn('Se necesitan al menos 3 clientes, 3 grupos de trabajo y 3 técnicos para crear las órdenes.');
            return;
        }

        // Crear una orden de trabajo de ejemplo para TecnoServi
        $cliente = $clientes->where('es_premium', true)->first();
        
        OrdenTrabajo::create([
            'numero_orden' => OrdenTrabajo::generarNumeroOrden(),
            'tipo_servicio' => 'instalacion',
            'cliente_id' => $cliente->id,
            'cliente_nombre' => $cliente->nombre,
            'cliente_telefono' => $cliente->telefono,
            'cliente_email' => $cliente->email,
            'descripcion_problema' => 'Instalación de servicio de internet de alta velocidad',
            'fecha_ingreso' => now()->subDays(2),
            'estado' => 'en_proceso',
            'prioridad' => 'alta',
            'observaciones' => 'Cliente premium - Prioridad máxima',
            'es_cliente_premium' => true,
            'grupo_trabajo_id' => $gruposTrabajo->first()->id,
            'user_id' => $tecnicos->first()->id,
            'vehiculo_id' => $vehiculos->first()->id
        ]);

        $this->command->info('✅ 1 orden de trabajo de TecnoServi creada exitosamente:');
        $this->command->info('🏥 Instalación Hospital San Rafael (En Proceso - Crítica)');
    }
}