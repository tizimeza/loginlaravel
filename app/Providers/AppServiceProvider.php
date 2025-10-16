<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use App\Models\Stock;
use App\Models\GrupoTrabajo;
use App\Observers\ClienteObserver;
use App\Observers\OrdenTrabajoObserver;
use App\Observers\VehiculoObserver;
use App\Observers\StockObserver;
use App\Observers\GrupoTrabajoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fix for older MySQL / MariaDB versions where index key length is limited
        // Ensures migrations that create unique indexes on varchar(255) don't fail
        Schema::defaultStringLength(191);

        // Registrar Observers para auditoría automática
        Cliente::observe(ClienteObserver::class);
        OrdenTrabajo::observe(OrdenTrabajoObserver::class);
        Vehiculo::observe(VehiculoObserver::class);
        Stock::observe(StockObserver::class);
        GrupoTrabajo::observe(GrupoTrabajoObserver::class);
    }
}
