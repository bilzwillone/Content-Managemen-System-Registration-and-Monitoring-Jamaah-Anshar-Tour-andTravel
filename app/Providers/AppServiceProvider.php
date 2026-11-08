<?php

namespace App\Providers;

use App\Models\Page;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'partials.public-navbar',
            'welcome',
            'packages.index',
            'packages.show',
            'pages.show',
        ], function ($view) {

            $navbarPages = Page::query()
                ->where('is_published', true)
                ->where('show_in_navbar', true)
                ->orderBy('nav_order')
                ->orderBy('id')
                ->get();

            $view->with('navbarPages', $navbarPages);

        });
    }
}
