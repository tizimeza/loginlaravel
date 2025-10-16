<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\OrdenTrabajo::class => \App\Policies\OrdenTrabajoPolicy::class,
        \App\Models\Cliente::class => \App\Policies\ClientePolicy::class,
        \App\Models\Vehiculo::class => \App\Policies\VehiculoPolicy::class,
        \App\Models\Stock::class => \App\Policies\StockPolicy::class,
        \App\Models\GrupoTrabajo::class => \App\Policies\GrupoTrabajoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
