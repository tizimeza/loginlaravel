<?php

namespace Database\Seeders;

use App\Models\GrupoTrabajo;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrupoTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener usuarios y vehículos existentes
        $usuarios = User::all();
        $vehiculos = Vehiculo::all();

        if ($usuarios->count() < 3 || $vehiculos->count() < 3) {
            $this->command->warn('Se necesitan al menos 3 usuarios y 3 vehículos para crear los móviles.');
            return;
        }

        // Crear 3 móviles de TecnoServi
        $moviles = [
            [
                'nombre' => 'Móvil Alpha',
                'descripcion' => 'Equipo especializado en instalaciones de servicio de internet y telefonía residencial',
                'lider_id' => $usuarios->first()->id,
                'vehiculo_id' => $vehiculos->first()->id,
                'activo' => true,
                'color' => 'primary',
                'especialidad' => 'instalacion',
                'capacidad_maxima' => 3,
                'zona_trabajo' => 'residencial'
            ],
            [
                'nombre' => 'Móvil Beta',
                'descripcion' => 'Equipo para instalación de fibra óptica y cableado estructurado comercial',
                'lider_id' => $usuarios->skip(1)->first()->id,
                'vehiculo_id' => $vehiculos->skip(1)->first()->id,
                'activo' => true,
                'color' => 'success',
                'especialidad' => 'fibra_optica',
                'capacidad_maxima' => 2,
                'zona_trabajo' => 'comercial'
            ],
            [
                'nombre' => 'Móvil Gamma',
                'descripcion' => 'Equipo de emergencias, reconexiones y soporte técnico',
                'lider_id' => $usuarios->skip(2)->first()->id,
                'vehiculo_id' => $vehiculos->skip(2)->first()->id,
                'activo' => true,
                'color' => 'warning',
                'especialidad' => 'soporte',
                'capacidad_maxima' => 3,
                'zona_trabajo' => 'centro'
            ]
        ];

        foreach ($moviles as $movil) {
            $grupo = GrupoTrabajo::create($movil);
            
            // Asignar miembros adicionales al grupo (simulando 2-3 empleados por móvil)
            $miembrosAdicionales = $usuarios->where('id', '!=', $grupo->lider_id)->take(2);
            $grupo->miembros()->attach($miembrosAdicionales->pluck('id'));
        }

        $this->command->info('✅ 3 móviles de TecnoServi creados exitosamente:');
        $this->command->info('   📡 Móvil Alpha - Instalaciones de Internet/Telefonía Residencial');
        $this->command->info('   🌐 Móvil Beta - Fibra Óptica y Cableado Comercial');
        $this->command->info('   🔧 Móvil Gamma - Soporte Técnico y Emergencias');
    }
}