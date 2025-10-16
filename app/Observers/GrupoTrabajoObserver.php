<?php

namespace App\Observers;

use App\Models\GrupoTrabajo;
use App\Models\ActivityLog;

class GrupoTrabajoObserver
{
    /**
     * Handle the GrupoTrabajo "created" event.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return void
     */
    public function created(GrupoTrabajo $grupoTrabajo)
    {
        $this->logActivity(
            $grupoTrabajo,
            'created',
            "Grupo de trabajo {$grupoTrabajo->nombre} fue creado"
        );
    }

    /**
     * Handle the GrupoTrabajo "updated" event.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return void
     */
    public function updated(GrupoTrabajo $grupoTrabajo)
    {
        $changes = $grupoTrabajo->getChanges();
        $original = $grupoTrabajo->getOriginal();

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
            $grupoTrabajo,
            'updated',
            "Grupo de trabajo {$grupoTrabajo->nombre} fue actualizado",
            $oldValues,
            $newValues
        );
    }

    /**
     * Handle the GrupoTrabajo "deleted" event.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return void
     */
    public function deleted(GrupoTrabajo $grupoTrabajo)
    {
        $this->logActivity(
            $grupoTrabajo,
            'deleted',
            "Grupo de trabajo {$grupoTrabajo->nombre} fue eliminado"
        );
    }

    /**
     * Handle the GrupoTrabajo "restored" event.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return void
     */
    public function restored(GrupoTrabajo $grupoTrabajo)
    {
        $this->logActivity(
            $grupoTrabajo,
            'restored',
            "Grupo de trabajo {$grupoTrabajo->nombre} fue restaurado"
        );
    }

    /**
     * Handle the GrupoTrabajo "force deleted" event.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @return void
     */
    public function forceDeleted(GrupoTrabajo $grupoTrabajo)
    {
        //
    }

    /**
     * Log activity for the grupo trabajo.
     *
     * @param  \App\Models\GrupoTrabajo  $grupoTrabajo
     * @param  string  $event
     * @param  string  $description
     * @param  array|null  $oldValues
     * @param  array|null  $newValues
     * @return void
     */
    protected function logActivity(GrupoTrabajo $grupoTrabajo, string $event, string $description, ?array $oldValues = null, ?array $newValues = null)
    {
        ActivityLog::create([
            'subject_type' => get_class($grupoTrabajo),
            'subject_id' => $grupoTrabajo->id,
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
