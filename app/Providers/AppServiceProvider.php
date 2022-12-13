<?php

namespace App\Providers;

use App\Services\Interfaces\RecipeSearchInterface;
use App\Services\RecipeSearchService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RecipeSearchInterface::class, function () {
            return new RecipeSearchService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
