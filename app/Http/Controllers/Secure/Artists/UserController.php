<?php

namespace App\Http\Controllers\Secure\Artists;

use App\Http\Controllers\Controller;

use App\Http\Requests\Artists\UserUpdateFormRequest;

use App\Models\Artists\User;

use App\Services\Artists\UserService;

class UserController extends Controller
{
    /**
     * User service instance
     *
     * @var \App\Services\Artists\UserService;
     */
    protected $userService;


    /**
     * View name prefix
     *
     * @var string
     */
    protected $viewPrefix;


    /**
     * Route name prefix
     *
     * @var string
     */
    protected $routePrefix;


    /**
     * Create a new controller instance.
     *
     * @param \App\Services\Artists\UserService $userService [the user service instance]
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        // This index method in this controller is only authorised for use by users assigned with the "admin" role
        $this->middleware('role:admin')->only('index');

        $this->userService = $userService;

        $this->viewPrefix = 'secure.artists.user';

        $this->routePrefix = 'secure.artists.user';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userService->index();

        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $users as collection of Eloquent models
           $routePrefix as string
        */
        return view($this->viewPrefix.'.index', compact('users', 'routePrefix'));
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Artists\User $user [the user model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Invoke Policy method to check if current autheticated user can view this resource
        $this->authorize('view', $user);

        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $user as Eloquent model
           $routePrefix as string

           Note: This function will not return the view if authorization fails */
        return view($this->viewPrefix.'.show', compact('user', 'routePrefix'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Artists\User $user [the user model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Invoke Policy method to check if current autheticated user can update this resource
        $this->authorize('update', $user);

        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $user as Eloquent model
           $routePrefix as string

           Note: This function will not return the view if authorization fails */
        return view($this->viewPrefix.'.edit', compact('user', 'routePrefix'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Artists\UserFormUpdateRequest $request [the current request instance]
     * @param \App\Models\Artists\User                         $user    [the user model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateFormRequest $request, User $user)
    {
        // Invoke Policy method to check if current autheticated user can update this resource
        $this->authorize('update', $user);
    
        $this->userService->update($request->validated(), $user);

        // Note: This function will not redirect if authorization fails
        return redirect()->route($this->routePrefix.'.show', $user)->with('messageSuccess', 'Update Successful!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Artists\User $user [the user model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Invoke Policy method to check if current autheticated user can delete this resource
        $this->authorize('delete', $user);

        $this->userService->delete($user);

        // Note: This function will not redirect if authorization fails
        return redirect()->back()->with('messageSuccess', 'Deletion Successful!');
    }
}
