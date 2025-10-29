<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Camara;
use Illuminate\Auth\Access\HandlesAuthorization;

class CamaraPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_camara');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Camara $camara): bool
    {
        return $user->can('view_camara');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_camara');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Camara $camara): bool
    {
        return $user->can('update_camara');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Camara $camara): bool
    {
        return $user->can('delete_camara');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_camara');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Camara $camara): bool
    {
        return $user->can('force_delete_camara');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_camara');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Camara $camara): bool
    {
        return $user->can('restore_camara');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_camara');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Camara $camara): bool
    {
        return $user->can('replicate_camara');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_camara');
    }
}
