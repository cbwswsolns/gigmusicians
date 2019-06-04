<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...

        view()->composer(
            ['public.layouts.nav.category-sub-menu',
             'public.layouts.nav.category-sub-menu-no-js',
             'secure.partials.nav.category-sub-menu',
             'secure.partials.nav.category-sub-menu-no-js',
             'secure.artists.profile.edit',
             'secure.artists.profile.create'
            ],
            'App\Http\ViewComposers\Artists\CategoryComposer'
        );
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
