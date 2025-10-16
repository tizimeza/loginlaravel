<?php

namespace App\Observers;

use App\Models\Vehiculo;
use App\Models\ActivityLog;

class VehiculoObserver
{
    /**
     * Handle the Vehiculo "created" event.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return void
     */
    public function created(Vehiculo $vehiculo)
    {
        $this->logActivity(
            $vehiculo,
            'created',
            "Vehiculo con patente {$vehiculo->patente} fue creado"
        );
    }

    /**
     * Handle the Vehiculo "updated" event.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return void
     */
    public function updated(Vehiculo $vehiculo)
    {
        $changes = $vehiculo->getChanges();
        $original = $vehiculo->getOriginal();

        // Exclude timestamps from tracking
        unset($changes['updated_at'], $changes['created_at']);

        if (empty($changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach ($changes as $key => $newValue) {
            $oldValues[$key] = $original[$key] ?? null;
            $newValues[$key] = $newValue;
        }

        $this->logActivity(
            $vehiculo,
            'updated',
            "Vehiculo con patente {$vehiculo->patente} fue actualizado",
            $oldValues,
            $newValues
        );
    }

    /**
     * Handle the Vehiculo "deleted" event.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return void
     */
    public function deleted(Vehiculo $vehiculo)
    {
        $this->logActivity(
            $vehiculo,
            'deleted',
            "Vehiculo con patente {$vehiculo->patente} fue eliminado"
        );
    }

    /**
     * Handle the Vehiculo "restored" event.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return void
     */
    public function restored(Vehiculo $vehiculo)
    {
        $this->logActivity(
            $vehiculo,
            'restored',
            "Vehiculo con patente {$vehiculo->patente} fue restaurado"
        );
    }

    /**
     * Handle the Vehiculo "force deleted" event.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return void
     */
    public function forceDeleted(Vehiculo $vehiculo)
    {
        //
    }

    /**
     * Log activity for the vehiculo.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @param  string  $event
     * @param  string  $description
     * @param  array|null  $oldValues
     * @param  array|null  $newValues
     * @return void
     */
    protected function logActivity(Vehiculo $vehiculo, string $event, string $description, ?array $oldValues = null, ?array $newValues = null)
    {
        ActivityLog::create([
            'subject_type' => get_class($vehiculo),
            'subject_id' => $vehiculo->id,
            'user_id' => auth()->id(),
            'event' => $event,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
