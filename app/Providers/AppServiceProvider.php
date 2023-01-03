<?php

namespace App\Providers;

use App\Services\DietPlanService;
use App\Services\IngredientService;
use App\Services\Interfaces\DietPlanInterface;
use App\Services\Interfaces\IngredientInterface;
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
        $this->app->bind(DietPlanInterface::class, DietPlanService::class);
        $this->app->bind(IngredientInterface::class, IngredientService::class);
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
