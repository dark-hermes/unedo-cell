<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        Paginator::useBootstrapFive();
        Blade::component(class: 'App\View\Components\admin\SidebarItem', alias: 'sidebar-item');
        Blade::component(class: 'App\View\Components\admin\SidebarSubItem', alias: 'sidebar-sub-item');
    }
}
