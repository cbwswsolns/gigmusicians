<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /* Note: as table name is "permissions", there is no need to specify a table property for this model as "permissions" is the default name */


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    // MODEL METHODS

    /**
     * Define the default permmissions
     *
     * @return array
     */
    public static function defaultPermissions()
    {
        return [
            'view_user',
            'create_user',
            'update_user',
            'delete_user',

            'view_category',
            'create_category',
            'update_category',
            'delete_category',

            'view_profile',
            'create_profile',
            'update_profile',
            'delete_profile',

            'set_profile_pending_status',
            'set_profile_submitted_status',
            'set_profile_published_status',
            'set_profile_featured_status',
            'set_profile_recalled_status',
        ];
    }


    // MODEL RELATIONSHIPS

    /**
     * Set up BelongsToMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
