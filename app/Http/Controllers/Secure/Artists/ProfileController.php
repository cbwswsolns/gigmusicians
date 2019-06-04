<?php

namespace App\Http\Controllers\Secure\Artists;

use App\Http\Controllers\Controller;

use App\Http\Requests\Artists\ProfileStoreFormRequest;
use App\Http\Requests\Artists\ProfileUpdateFormRequest;

use App\Models\Artists\Profile;

use App\Services\ResourceItemSorting;

use App\Services\Artists\ProfileService;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Profile service instance
     *
     * @var \App\Services\Artists\ProfileService;
     */
    protected $profileService;


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
     * @param \App\Services\Artists\ProfileService $profileService [the profile service instance]
     *
     * @return void
     */
    public function __construct(ProfileService $profileService)
    {
        // Restrict listed controller methods to only be accessible to users with the role of "admin"
        $this->middleware('role:admin')->only('index', 'ajaxSortUpdate');

        $this->profileService = $profileService;

        $this->viewPrefix = 'secure.artists.profile';

        $this->routePrefix = 'secure.artists.profile';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = $this->profileService->index();

        $profileStates = $this->profileService->getProfileStates();

        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $profiles as collection of Eloquent models (with their related user Eloquent models)
           $profileStates as array
           $routePrefix as string
        */
        return view($this->viewPrefix.'.index', compact('profiles', 'profileStates', 'routePrefix'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $className = Profile::class;

        // Invoke Policy method to check if current autheticated user can create this resource
        $this->authorize('create', $className);

        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $routePrefix as string

           Note: This function will not return the view if authorization fails */
        return view($this->viewPrefix.'.create', compact('routePrefix'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Artists\ProfileStoreFormRequest $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileStoreFormRequest $request)
    {
        // Invoke Policy method to check if current autheticated user can create this resource
        $this->authorize('create', Profile::class);

        $profile = $this->profileService->store($request->validated());

        // Note: This function will not redirect if authorization fails
        return redirect()->route($this->routePrefix.'.show', $profile)->with('messageSuccess', 'Profile creation successful!');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can view this resource
        $this->authorize('view', $profile);

        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $profile as Eloquent model

           Note: This function will not return the view if authorization fails */
        return view($this->viewPrefix.'.show', compact('profile', 'routePrefix'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can update this resource
        $this->authorize('update', $profile);

        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $profile as Eloquent model
           $routePrefix as string

           Note: This function will not return the view if authorization fails */
        return view($this->viewPrefix.'.edit', compact('profile', 'routePrefix'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Artists\ProfileUpdateFormRequest $request [the current request instance]
     * @param \App\Models\Artists\Profile                         $profile [the profile model instance]
     *
     * @return Illuminate\Http\Response
     */
    public function update(ProfileUpdateFormRequest $request, Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can update this resource
        $this->authorize('update', $profile);

        $this->profileService->update($request->validated(), $profile);

        // Note: This function will not redirect if authorization fails
        return redirect()->route($this->routePrefix.'.show', $profile)->with('messageSuccess', 'Profile update successful!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can delete this resource
        $this->authorize('delete', $profile);

        $this->profileService->delete($profile);

        // Note: This function will not redirect if authorization fails
        return redirect()->back()->with('messageSuccess', 'Profile Deletion Successful!');
    }


    /**
     * Update the specified resource in storage (set profile pending status)
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function setPendingStatus(Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can set profile state to pending
        $this->authorize('set-pending-status', $profile);

        $profile->laststatus = $profile->status;
        $profile->status = $profile::PENDING;
        $profile->save();

        // Note: This function will not redirect if authorization fails
        return redirect()->route($this->routePrefix.'.show', $profile);
    }


    /**
     * Update the specified resource in storage (set profile submitted status)
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function setSubmittedStatus(Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can set profile state to submitted
        $this->authorize('set-submitted-status', $profile);

        $profile->laststatus = $profile->status;
        $profile->status = $profile::SUBMITTED;
        $profile->save();

        // Note: This function will not redirect if authorization fails
        return redirect()->route($this->routePrefix.'.show', $profile);
    }


    /**
     * Update the specified resource in storage (set profile published status)
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function setPublishedStatus(Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can set profile state to published
        $this->authorize('set-published-status', $profile);

        $profile->laststatus = $profile->status;
        $profile->status = $profile::PUBLISHED;
        $profile->save();

        // Note: This function will not redirect if authorization fails
        return redirect()->route($this->routePrefix.'.show', $profile);
    }


    /**
     * Update the specified resource in storage (set profile featured status)
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function setFeaturedStatus(Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can set profile state to featured
        $this->authorize('set-featured-status', $profile);

        /* Mass update all profile records which are set to featured to published
           (There should only ever be one profile with featured status!) */
        $profile->where('status', $profile::FEATURED)->update(['laststatus' => $profile::FEATURED, 'status' => $profile::PUBLISHED]);

        $profile->laststatus = $profile->status;
        $profile->status = $profile::FEATURED;
        $profile->save();

        // Note: This function will not redirect if authorization fails
        return redirect()->route($this->routePrefix.'.show', $profile);
    }


    /**
     * Update the specified resource in storage (set profile recalled status)
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function setRecalledStatus(Profile $profile)
    {
        // Invoke Policy method to check if current autheticated user can set profile state to recalled
        $this->authorize('set-recalled-status', $profile);

        $profile->laststatus = $profile->status;
        $profile->status = $profile::RECALLED;
        $profile->save();

        // Note: This function will not redirect if authorization fails
        return redirect()->route($this->routePrefix.'.show', $profile);
    }


    /**
     * AJAX request - perform a sorting update on the resource
     *
     * @param \Illuminate\Http\Request         $request        [the current request instance]
     * @param \App\Library\ResourceItemSorting $profileSorting [the sorting object instance]
     * @param \App\Models\Artists\Profile      $profile        [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxSortUpdate(Request $request, ResourceItemSorting $profileSorting, Profile $profile)
    {
        $profiles = $profile->all();

        $profileSorting->sortUpdate($request, $profiles);

        return response('Sort Update Successful', 200);
    }


    /**
     * AJAX request - delete image file from storage along with associated database record
     *
     * @param \Illuminate\Http\Request    $request [the current request instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxDeleteProfileImageFile(Request $request, Profile $profile)
    {
        $image = $profile->images()->find($request->id);

        // Associated stored file is deleted via a model "deleting" event listener
        $image->delete($request->id);

        return response('Deletion Successful', 200);
    }


    /**
     * AJAX request - delete music file from storage along with associated database record
     *
     * @param \Illuminate\Http\Request    $request [the current request instance]
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxDeleteProfileMusicFile(Request $request, Profile $profile)
    {
        $musicFile = $profile->musicfiles()->find($request->id);

        // Associated stored file is deleted via a model "deleting" event listener
        $musicFile->delete($request->id);

        return response('Deletion Successful', 200);
    }
}
