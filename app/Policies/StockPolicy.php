<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        // Todos pueden ver el stock
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Stock $stock)
    {
        // Todos pueden ver un producto específico
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Solo Admin y Supervisor pueden crear productos
        return $user->hasAnyRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Stock $stock)
    {
        // Solo Admin y Supervisor pueden editar productos
        return $user->hasAnyRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Stock $stock)
    {
        // Solo Admin puede eliminar productos
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Stock $stock)
    {
        // Solo Admin puede restaurar productos
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Stock $stock)
    {
        // Solo Admin puede eliminar permanentemente
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can adjust stock.
     */
    public function ajustarStock(User $user, Stock $stock)
    {
        // Admin y Supervisor pueden ajustar cantidades de stock
        return $user->hasAnyRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can toggle active status.
     */
    public function toggleActivo(User $user, Stock $stock)
    {
        // Admin y Supervisor pueden activar/desactivar productos
        return $user->hasAnyRole(['admin', 'supervisor']);
    }
}
