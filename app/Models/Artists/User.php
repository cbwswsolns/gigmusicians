<?php

namespace App\Models\Artists;

use App\Models\Role;

use App\Models\Traits\HasRelatedMedia;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRelatedMedia;

    use Notifiable;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'artist_users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    // MODEL METHODS

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }


    /**
     * Publish the related profile
     *
     * @return void
     */
    public function publish()
    {
        $this->profile()->save();
    }


    /**
     * Assign specified role to user
     *
     * @param \App\Models\Role $role [the role instance]
     *
     * @return void
     */
    public function assignRole(Role $role)
    {
        return $this->roles()->save($role);
    }


    /**
     * Determine if user has the specified role
     *
     * @param \App\Models\Role $role [the role instance]
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return !! $role->intersect($this->roles)->count();
    }


    /**
     * Determine if the user has the role of Administrator
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }


    // MODEL RELATIONSHIPS
    
    /**
     * Set up BelongsToMany relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'artist_user_role');
    }


    /**
     * Set up HasOne relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }


    /**
     * Set up HasManyThrough relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function images()
    {
        return $this->hasManyThrough('App\Models\Image', 'App\Models\Artists\Profile');
    }


    /**
     * Set up HasManyThrough relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function musicfiles()
    {
        return $this->hasManyThrough('App\Models\MusicFile', 'App\Models\Artists\Profile');
    }
}
