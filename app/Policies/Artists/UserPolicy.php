<?php

namespace App\Policies\Artists;

use App\Models\Artists\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user.
     *
     * @param \App\Models\Artists\User $user  [the authenticatd user instance]
     * @param \App\Models\Artists\User $model [the user model instance]
     *
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        /* Note: profiles published in the public area are viewable by everyone
           and not subject to the rules of this profile policy */

        if ($user->cannot('view_user')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $userOwnsAccount = ($user->id === $model->id);

        if (!$user->isAdmin()) {
            // View policy for non-admin authenticated users
            return $userOwnsAccount;
        }

        // View policy for admins
        return true;
    }


    /**
     * Determine whether the user can create users.
     *
     * @param \App\Models\Artists\User $user [the authenticated user instance]
     *
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->cannot('create_user')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        /* Create policy for authenticated users
           Note: Only admins have authorization to create users */
        return $user->isAdmin();
    }


    /**
     * Determine whether the user can update the user.
     *
     * @param \App\Models\Artists\User $user  [the authenticated user instance]
     * @param \App\Models\Artists\User $model [the user model instance]
     *
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        if ($user->cannot('update_user')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $userOwnsAccount = ($user->id === $model->id);

        if (!$user->isAdmin()) {
            // Update policy for non-admin authenticated users
            return $userOwnsAccount;
        }

        // Update policy for admins
        return true;
    }


    /**
     * Determine whether the user can delete the user.
     *
     * @param \App\Models\Artists\User $user  [the authenticated user instance]
     * @param \App\Models\Artists\User $model [the user model instance]
     *
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        if ($user->cannot('delete_user')) {
            return false; // Authenticated user has not been allocated permission for this action
        }
        
        $userOwnsAccount = ($user->id === $model->id);

        if (!$user->isAdmin()) {
            // Delete policy for non-admin authenticated users
            return $userOwnsAccount;
        }

        // Delete policy for admins
        return true;
    }
}
