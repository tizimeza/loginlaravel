<?php

namespace App\Policies;

use App\Models\GrupoTrabajo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GrupoTrabajoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        // Todos pueden ver grupos de trabajo
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GrupoTrabajo $grupoTrabajo)
    {
        // Todos pueden ver un grupo específico
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Solo Admin y Supervisor pueden crear grupos
        return $user->hasAnyRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GrupoTrabajo $grupoTrabajo)
    {
        // Admin y Supervisor pueden editar cualquier grupo
        // El líder del grupo también puede editar su grupo
        if ($user->hasAnyRole(['admin', 'supervisor'])) {
            return true;
        }

        return $grupoTrabajo->lider_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GrupoTrabajo $grupoTrabajo)
    {
        // Solo Admin puede eliminar grupos
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GrupoTrabajo $grupoTrabajo)
    {
        // Solo Admin puede restaurar grupos
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GrupoTrabajo $grupoTrabajo)
    {
        // Solo Admin puede eliminar permanentemente
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can toggle active status.
     */
    public function toggleActivo(User $user, GrupoTrabajo $grupoTrabajo)
    {
        // Admin y Supervisor pueden activar/desactivar grupos
        return $user->hasAnyRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can add or remove members.
     */
    public function manageMiembros(User $user, GrupoTrabajo $grupoTrabajo)
    {
        // Admin y Supervisor pueden gestionar miembros
        // El líder también puede gestionar su equipo
        if ($user->hasAnyRole(['admin', 'supervisor'])) {
            return true;
        }

        return $grupoTrabajo->lider_id === $user->id;
    }
}
