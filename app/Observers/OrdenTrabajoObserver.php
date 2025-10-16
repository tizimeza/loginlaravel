<?php

namespace App\Observers;

use App\Models\OrdenTrabajo;
use App\Models\ActivityLog;

class OrdenTrabajoObserver
{
    /**
     * Handle the OrdenTrabajo "created" event.
     */
    public function created(OrdenTrabajo $ordenTrabajo): void
    {
        $this->logActivity($ordenTrabajo, 'created', 'Se creó la orden de trabajo #' . $ordenTrabajo->numero_orden);
    }

    /**
     * Handle the OrdenTrabajo "updated" event.
     */
    public function updated(OrdenTrabajo $ordenTrabajo): void
    {
        // Solo registrar si hay cambios significativos
        if ($ordenTrabajo->isDirty()) {
            $changes = $ordenTrabajo->getDirty();
            $oldValues = [];
            $newValues = [];

            // Capturar valores anteriores y nuevos
            foreach ($changes as $key => $value) {
                // Ignorar timestamps automáticos
                if (!in_array($key, ['updated_at', 'created_at'])) {
                    $oldValues[$key] = $ordenTrabajo->getOriginal($key);
                    $newValues[$key] = $value;
                }
            }

            // Descripción especial si cambió el estado
            if (isset($changes['estado'])) {
                $description = "Se cambió el estado de '{$ordenTrabajo->getOriginal('estado')}' a '{$ordenTrabajo->estado}' en la orden #{$ordenTrabajo->numero_orden}";
            } else {
                $description = "Se actualizó la orden de trabajo #{$ordenTrabajo->numero_orden}";
            }

            $this->logActivity($ordenTrabajo, 'updated', $description, $oldValues, $newValues);
        }
    }

    /**
     * Handle the OrdenTrabajo "deleted" event.
     */
    public function deleted(OrdenTrabajo $ordenTrabajo): void
    {
        $this->logActivity($ordenTrabajo, 'deleted', 'Se eliminó la orden de trabajo #' . $ordenTrabajo->numero_orden);
    }

    /**
     * Handle the OrdenTrabajo "restored" event.
     */
    public function restored(OrdenTrabajo $ordenTrabajo): void
    {
        $this->logActivity($ordenTrabajo, 'restored', 'Se restauró la orden de trabajo #' . $ordenTrabajo->numero_orden);
    }

    /**
     * Método helper para registrar la actividad
     */
    protected function logActivity(
        OrdenTrabajo $model,
        string $event,
        string $description,
        array $oldValues = [],
        array $newValues = []
    ): void {
        ActivityLog::create([
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'user_id' => auth()->id(),
            'event' => $event,
            'description' => $description,
            'old_values' => !empty($oldValues) ? $oldValues : null,
            'new_values' => !empty($newValues) ? $newValues : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
