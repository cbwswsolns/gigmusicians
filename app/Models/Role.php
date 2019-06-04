<?php

namespace App\Models;

use App\Models\Artists\User;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /* Note: as table name is "roles", there is no need to specify a table property for this model as "roles" is the default name */


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    // MODEL METHODS

    /**
     * Allocate a permission to role.
     *
     * @param Permission $permission [the permission instance]
     *
     * @return boolean
     */
    public function assignPermission(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }


    // MODEL RELATIONSHIPS

    /**
     * Set up BelongsToMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }


    /**
     * Set up BelongsToMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'artist_user_role');
    }
}
