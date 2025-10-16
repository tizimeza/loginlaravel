<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        // Todos los usuarios autenticados pueden ver clientes
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cliente $cliente)
    {
        // Todos los usuarios autenticados pueden ver un cliente específico
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Solo Admin y Supervisor pueden crear clientes
        return $user->hasAnyRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cliente $cliente)
    {
        // Solo Admin y Supervisor pueden editar clientes
        return $user->hasAnyRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cliente $cliente)
    {
        // Solo Admin puede eliminar clientes
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cliente $cliente)
    {
        // Solo Admin puede restaurar clientes
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cliente $cliente)
    {
        // Solo Admin puede eliminar permanentemente
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can toggle active status.
     */
    public function toggleActivo(User $user, Cliente $cliente)
    {
        // Admin y Supervisor pueden activar/desactivar
        return $user->hasAnyRole(['admin', 'supervisor']);
    }
}
