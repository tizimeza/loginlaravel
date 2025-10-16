<?php

namespace App\Policies;

use App\Models\OrdenTrabajo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrdenTrabajoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        // Todos los usuarios autenticados pueden ver órdenes
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrdenTrabajo $ordenTrabajo)
    {
        // Admin y Supervisor pueden ver cualquier orden
        if ($user->hasAnyRole(['admin', 'supervisor'])) {
            return true;
        }

        // Técnico solo puede ver sus propias órdenes asignadas
        if ($user->hasRole('tecnico')) {
            return $ordenTrabajo->user_id === $user->id
                || $ordenTrabajo->grupoTrabajo?->miembros->contains($user->id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Admin y Supervisor pueden crear órdenes
        return $user->hasAnyRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrdenTrabajo $ordenTrabajo)
    {
        // Admin y Supervisor pueden editar cualquier orden
        if ($user->hasAnyRole(['admin', 'supervisor'])) {
            return true;
        }

        // Técnico puede editar sus órdenes asignadas (pero con limitaciones)
        if ($user->hasRole('tecnico')) {
            return $ordenTrabajo->user_id === $user->id
                || $ordenTrabajo->grupoTrabajo?->miembros->contains($user->id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrdenTrabajo $ordenTrabajo)
    {
        // Solo Admin puede eliminar órdenes
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrdenTrabajo $ordenTrabajo)
    {
        // Solo Admin puede restaurar órdenes
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrdenTrabajo $ordenTrabajo)
    {
        // Solo Admin puede eliminar permanentemente
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can change the status.
     */
    public function cambiarEstado(User $user, OrdenTrabajo $ordenTrabajo)
    {
        // Admin y Supervisor pueden cambiar cualquier estado
        if ($user->hasAnyRole(['admin', 'supervisor'])) {
            return true;
        }

        // Técnico puede cambiar el estado de sus órdenes asignadas
        if ($user->hasRole('tecnico')) {
            return $ordenTrabajo->user_id === $user->id
                || $ordenTrabajo->grupoTrabajo?->miembros->contains($user->id);
        }

        return false;
    }

    /**
     * Determine whether the user can assign the order.
     */
    public function asignar(User $user, OrdenTrabajo $ordenTrabajo)
    {
        // Solo Admin y Supervisor pueden asignar órdenes
        return $user->hasAnyRole(['admin', 'supervisor']);
    }
}
