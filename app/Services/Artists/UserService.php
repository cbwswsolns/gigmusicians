<?php

namespace App\Services\Artists;

use App\Models\Artists\User;

class UserService
{
    /**
     * User model instance
     *
     * @var App\Models\Artists\User
     */
    protected $user;


    /**
     * Create a new user service instance.
     *
     * @param App\Models\Artists\User $user [the user model instance]
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Index method
     *
     * @return Illuminate\Support\Collection
     */
    public function index()
    {
        // Return an array of users
        return $this->user->all();
    }


    /**
     * Update user method
     *
     * @param array                   $data [the data to use to update the given user]
     * @param App\Models\Artists\User $user [the user model instance]
     *
     * @return void
     */
    public function update(array $data, User $user)
    {
        // Encrypt the new password
        $data['password'] = bcrypt($data['password']);

        $user->update($data);
    }


    /**
     * Delete method
     *
     * @param \App\Models\Artists\User $user [the user model to delete]
     *
     * @return void
     */
    public function delete(User $user)
    {
        /* Associated stored files will be deleted via a model "deleting" event listener.
           Associated related/child records will be deleted (via "on cascade" implementation) */
        $user->delete();
    }
}
