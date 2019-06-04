<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;

use App\Models\Artists\Profile;

class HomeController extends Controller
{
    /**
     * Fetch profile images, featured profile image and the "contact us" image
     * Display a link to each
     *
     * @param \App\Models\Artists\Profile $profile [the profile model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function display(Profile $profile)
    {
        $profileImages = array();
        $featuredImage = array();
        $contactImage = array();

        $profiles = $profile->ByPublishedOrFeaturedAndSortOrder()->get();

        if ($profiles->isNotEmpty()) {
            foreach ($profiles as $profile) {
                // All profiles should have required at least one image as per profile create validation
                if (($profile->images()->get())->isNotEmpty()) {
                    $profileImages[] = '/storage/'.$profile->images()->pluck('filename')->all()[0];
                }
            }

            // Does a featured profile exist?
            if (($featuredProfile = $profile->ByFeatured()->get()->first())) {
                // All profiles should have required at least one image as per profile create validation
                if (($featuredImages = $featuredProfile->images)->isNotEmpty()) {
                    $featuredImage[] = '/storage/'.$featuredImages->first()->filename;
                }
            }
        }

        $contactImage[] = '/pexels-photo.jpg';
    
        /* Parameters passed to view:
           $profileImages as array
           $featuredImage as array
           $contactImage as array
        */
        return view('public.home', compact('profileImages', 'featuredImage', 'contactImage'));
    }
}
