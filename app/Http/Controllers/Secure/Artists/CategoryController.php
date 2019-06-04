<?php

namespace App\Http\Controllers\Secure\Artists;

use App\Http\Controllers\Controller;

use App\Http\Requests\Artists\CategoryStoreFormRequest;
use App\Http\Requests\Artists\CategoryUpdateFormRequest;

use App\Models\Artists\Category;
use App\Models\Artists\Profile;

use App\Services\ResourceItemSorting;

use App\Services\Artists\CategoryService;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Category service instance
     *
     * @var \App\Services\Artists\CategoryService;
     */
    protected $categoryService;


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
     * @param \App\Services\Artists\CategoryService $categoryService [the category service instance]
     *
     * @return void
     */
    public function __construct(CategoryService $categoryService)
    {
        // This controller is only authorised for use by users assigned with the "admin" role
        $this->middleware('role:admin');

        $this->categoryService = $categoryService;

        $this->viewPrefix = 'secure.artists.category';

        $this->routePrefix = 'secure.artists.category';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Note: Will be set to empty collection if no categories exist
        $categories = $this->categoryService->index();

        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $categories as collection of Eloquent models
           $routePrefix as string
        */
        return view($this->viewPrefix.'.index', compact('categories', 'routePrefix'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $routePrefix as string
        */
        return view($this->viewPrefix.'.create', compact('routePrefix'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Artists\CategoryStoreFormRequest $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreFormRequest $request)
    {
        $category = $this->categoryService->store($request->validated());

        return redirect()->route($this->routePrefix.'.show', $category)->with('messageSuccess', 'Category Creation Successful!');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Artists\Category $category [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // Note: Will be set to empty collection if no related category profiles exist
        $profiles = $category->profiles()->with('user')->get()->sortby('sortorder');

        $profileStates = (new Profile)->getProfileStates();

        /* Parameters passed to view:
           $category as Eloquent model
           $profiles as collection of Eloquent models
           $profileStates as array
        */
        return view($this->viewPrefix.'.show', compact('category', 'profiles', 'profileStates'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Artists\Category $category [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $routePrefix = $this->routePrefix;

        /* Parameters passed to view:
           $category as Eloquent model
           $routePrefix as string
        */
        return view($this->viewPrefix.'.edit', compact('category', 'routePrefix'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Artists\CategoryUpdateFormRequest $request  [the current request instance]
     * @param \App\Models\Artists\Category                         $category [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateFormRequest $request, Category $category)
    {
        $this->categoryService->update($request->validated(), $category);

        $routeParameters = array($category);

        return redirect()->route($this->routePrefix.'.show', $routeParameters)->with('messageSuccess', 'Category Update Successful!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Artists\Category $category [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return redirect()->back()->with('messageSuccess', 'Category Deletion Successful!');
    }


    /**
     * AJAX request - perform a sorting update on a resource
     *
     * @param \Illuminate\Http\Request         $request         [the current request instance]
     * @param \App\Library\ResourceItemSorting $categorySorting [the sorting object instance]
     * @param \App\Models\Artists\Category     $category        [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxSortUpdate(Request $request, ResourceItemSorting $categorySorting, Category $category)
    {
        $categories = $category->all();

        $categorySorting->sortUpdate($request, $categories);

        return response('Sort Update Successful', 200);
    }
}
