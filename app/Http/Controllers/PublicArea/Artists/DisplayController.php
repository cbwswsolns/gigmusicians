<?php

namespace App\Http\Controllers\PublicArea\Artists;

use App\Http\Controllers\Controller;

use App\Models\Artists\Category;
use App\Models\Artists\Profile;

class DisplayController extends Controller
{
    /**
     * Display a listing of profiles
     *
     * @param \App\Models\Artists\Category $category [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function display(Category $category)
    {
        // Note: will be set to empty collection if no categories exist
        $categories = $category->BySortOrder()->get();

        $profiles = collect();

        // Categories exist?
        if ($categories->isNotEmpty()) {
            // Is category model new/injected?
            if (!$category->exists) {
                // Default category to first model in sorted categories collection
                $category = $categories->first();
            }

            /* Default the display to sorted, published or featured profiles as per the given category
               Note: will return empty collection if no profiles exist */
            $profiles = $category->profiles()->with('user')->ByPublishedOrFeaturedAndSortOrder()->get();
        }

        /* Parameters passed to view:
           $categories as collection of Eloquent models
           $profiles as collection of Eloquent models
        */
        return view('public.artists.display', compact('categories', 'profiles'));
    }


    /**
     * Display the given profile
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function displayProfile(Profile $profile)
    {
        /* Parameters passed to view:
           $profile as Eloquent model
        */
        return view('public.artists.profile.display', compact('profile'));
    }


    /**
     * Display the featured profile
     *
     * @return \Illuminate\Http\Response
     */
    public function displayFeaturedProfile()
    {
        // Note: there should only by one featured profile.  If it does not exist, this parameter will be set to null
        $profile = (new Profile)->ByFeatured()->get()->first();

        /* Parameters passed to view:
           $profile as Eloquent model
        */
        return view('public.artists.profile.display', compact('profile'));
    }
}
