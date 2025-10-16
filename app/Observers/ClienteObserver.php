<?php

namespace App\Observers;

use App\Models\Cliente;
use App\Models\ActivityLog;

class ClienteObserver
{
    public function created(Cliente $cliente): void
    {
        $this->logActivity($cliente, 'created', "Se creó el cliente: {$cliente->nombre}");
    }

    public function updated(Cliente $cliente): void
    {
        if ($cliente->isDirty()) {
            $changes = $cliente->getDirty();
            $oldValues = [];
            $newValues = [];

            foreach ($changes as $key => $value) {
                if (!in_array($key, ['updated_at', 'created_at'])) {
                    $oldValues[$key] = $cliente->getOriginal($key);
                    $newValues[$key] = $value;
                }
            }

            $this->logActivity($cliente, 'updated', "Se actualizó el cliente: {$cliente->nombre}", $oldValues, $newValues);
        }
    }

    public function deleted(Cliente $cliente): void
    {
        $this->logActivity($cliente, 'deleted', "Se eliminó el cliente: {$cliente->nombre}");
    }

    public function restored(Cliente $cliente): void
    {
        $this->logActivity($cliente, 'restored', "Se restauró el cliente: {$cliente->nombre}");
    }

    protected function logActivity(Cliente $model, string $event, string $description, array $oldValues = [], array $newValues = []): void
    {
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
