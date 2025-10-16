<?php

namespace App\Observers;

use App\Models\Stock;
use App\Models\ActivityLog;

class StockObserver
{
    /**
     * Handle the Stock "created" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function created(Stock $stock)
    {
        $this->logActivity(
            $stock,
            'created',
            "Stock {$stock->nombre} fue creado"
        );
    }

    /**
     * Handle the Stock "updated" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function updated(Stock $stock)
    {
        $changes = $stock->getChanges();
        $original = $stock->getOriginal();

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

        // Special handling for quantity changes
        $description = "Stock {$stock->nombre} fue actualizado";
        if (isset($changes['cantidad'])) {
            $oldQuantity = $original['cantidad'] ?? 0;
            $newQuantity = $changes['cantidad'];

            if ($newQuantity > $oldQuantity) {
                $description = "Ingreso de stock: {$stock->nombre}";
            } elseif ($newQuantity < $oldQuantity) {
                $description = "Salida de stock: {$stock->nombre}";
            }
        }

        $this->logActivity(
            $stock,
            'updated',
            $description,
            $oldValues,
            $newValues
        );
    }

    /**
     * Handle the Stock "deleted" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function deleted(Stock $stock)
    {
        $this->logActivity(
            $stock,
            'deleted',
            "Stock {$stock->nombre} fue eliminado"
        );
    }

    /**
     * Handle the Stock "restored" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function restored(Stock $stock)
    {
        $this->logActivity(
            $stock,
            'restored',
            "Stock {$stock->nombre} fue restaurado"
        );
    }

    /**
     * Handle the Stock "force deleted" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function forceDeleted(Stock $stock)
    {
        //
    }

    /**
     * Log activity for the stock.
     *
     * @param  \App\Models\Stock  $stock
     * @param  string  $event
     * @param  string  $description
     * @param  array|null  $oldValues
     * @param  array|null  $newValues
     * @return void
     */
    protected function logActivity(Stock $stock, string $event, string $description, ?array $oldValues = null, ?array $newValues = null)
    {
        ActivityLog::create([
            'subject_type' => get_class($stock),
            'subject_id' => $stock->id,
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
