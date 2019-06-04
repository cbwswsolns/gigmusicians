<?php

namespace App\Policies\Artists;

use App\Models\Artists\Profile;
use App\Models\Artists\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the profile.
     *
     * @param \App\Models\Artists\User    $user    [the authenticated user instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return mixed
     */
    public function view(User $user, Profile $profile)
    {
        /* Note: profiles published in the public area are viewable by everyone
           and not subject to the rules of this profile policy */

        if ($user->cannot('view_profile')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $userOwnsProfile = ($user->id === $profile->user_id);

        if (!$user->isAdmin()) {
            // View policy for non-admin authenticated users
            return $userOwnsProfile;
        }

        // View policy for admins
        return ($userOwnsProfile || !$profile->isPending());
    }


    /**
     * Determine whether the user can create profiles.
     *
     * @param \App\Models\Artists\User $user [the authenticated user instance]
     *
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->cannot('create_profile')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        if (!$user->isAdmin()) {
            /* Create policy for non-admin authenticated users
               Note: only one profile can be created */
            return is_null($user->profile);
        }

        // Create policy for admins
        return true;
    }


    /**
     * Determine whether the user can update the profile.
     *
     * @param \App\Models\Artists\User    $user    [the authenticated user instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return mixed
     */
    public function update(User $user, Profile $profile)
    {
        if ($user->cannot('update_profile')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $userOwnsProfile = ($user->id === $profile->user_id);

        if (!$user->isAdmin()) {
            // Update policy for non-admin authenticated users
            return ($userOwnsProfile && $profile->isPending());
        }

        // Update policy for admins
        return ($userOwnsProfile || !$profile->isPending());
    }


    /**
     * Determine whether the user can delete the profile.
     *
     * @param \App\Models\Artists\User    $user    [the authenticated user instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return mixed
     */
    public function delete(User $user, Profile $profile)
    {
        if ($user->cannot('delete_profile')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $userOwnsProfile = ($user->id === $profile->user_id);

        if (!$user->isAdmin()) {
            // Delete policy for non-admin authenticated users
            return $userOwnsProfile;
        }

        // Delete policy for admins
        return ($userOwnsProfile || !$profile->isPending());
    }


    /**
     * Determine whether the user can set the profile pending status.
     *
     * @param \App\Models\Artists\User $user [the authenticated user instance]
     *
     * @return mixed
     */
    public function setPendingStatus(User $user)
    {
        if ($user->cannot('set_profile_pending_status')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $stateTransitionAllow = $user->isAdmin();

        return $stateTransitionAllow;
    }


    /**
     * Determine whether the user can set the profile submitted status.
     *
     * @param \App\Models\Artists\User    $user    [the authenticated user instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return mixed
     */
    public function setSubmittedStatus(User $user, Profile $profile)
    {
        if ($user->cannot('set_profile_submitted_status')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $userOwnsProfile = ($user->id === $profile->user_id);

        $stateTransitionAllow = ($userOwnsProfile && $profile->isPending()) ||
                                  ($user->isAdmin() && $profile->isPublished());

        return $stateTransitionAllow;
    }


    /**
     * Determine whether the user can set the profile published status.
     *
     * @param \App\Models\Artists\User    $user    [the authenticated user instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return mixed
     */
    public function setPublishedStatus(User $user, Profile $profile)
    {
        if ($user->cannot('set_profile_published_status')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $stateTransitionAllow = $user->isAdmin() &&
                                  ($profile->isSubmitted() || $profile->isFeatured());

        return $stateTransitionAllow;
    }


    /**
     * Determine whether the user can set the profile featured status.
     *
     * @param \App\Models\Artists\User    $user    [the authenticated user instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return mixed
     */
    public function setFeaturedStatus(User $user, Profile $profile)
    {
        if ($user->cannot('set_profile_featured_status')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $stateTransitionAllow = $user->isAdmin() && $profile->isPublished();

        return $stateTransitionAllow;
    }


    /**
     * Determine whether the user can set the profile recalled status.
     *
     * @param \App\Models\Artists\User    $user    [the authenticated user instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return mixed
     */
    public function setRecalledStatus(User $user, Profile $profile)
    {
        if ($user->cannot('set_profile_recalled_status')) {
            return false; // Authenticated user has not been allocated permission for this action
        }

        $userOwnsProfile = ($user->id === $profile->user_id);

        $stateTransitionAllow = (!$user->isAdmin()) &&
                                  $userOwnsProfile &&
                                  ($profile->isSubmitted() || $profile->isPublished() || $profile->isFeatured());

        return $stateTransitionAllow;
    }
}
