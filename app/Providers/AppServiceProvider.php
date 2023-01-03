<?php

namespace App\Providers;

use App\Services\DietPlanService;
use App\Services\IngredientCategoryService;
use App\Services\IngredientSearchService;
use App\Services\IngredientService;
use App\Services\Interfaces\DietPlanInterface;
use App\Services\Interfaces\IngredientCategoryInterface;
use App\Services\Interfaces\IngredientInterface;
use App\Services\Interfaces\IngredientSearchInterface;
use App\Services\Interfaces\ProfileCreateOrUpdateInterface;
use App\Services\Interfaces\RecipeSearchInterface;
use App\Services\ProfileCreateOrUpdateService;
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
        $this->app->bind(IngredientCategoryInterface::class, IngredientCategoryService::class);
        $this->app->bind(IngredientSearchInterface::class, IngredientSearchService::class);
        $this->app->bind(ProfileCreateOrUpdateInterface::class, ProfileCreateOrUpdateService::class);
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
