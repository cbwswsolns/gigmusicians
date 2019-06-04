<?php

namespace App\Providers;

use App\Models\Permission;

use App\Models\Artists\Profile;
use App\Models\Artists\User;

use App\Policies\Artists\ProfilePolicy;
use App\Policies\Artists\UserPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Profile::class => ProfilePolicy::class,
        User::class => UserPolicy::class,
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (Schema::hasTable('permissions')) {
            foreach ($this->getPermissions() as $permission) {
                Gate::define(
                    $permission->name,
                    function ($user) use ($permission) {
                        return $user->hasRole($permission->roles);
                    }
                );
            }
        }
    }


    /**
     * Register any authentication / authorization services.
     *
     * @return \Illuminate\Database\Eloquent\Collection [Permission models eager loaded with Role models]
     */
    protected function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}
