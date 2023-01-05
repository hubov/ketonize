<?php

namespace App\Providers;

use App\Services\DietPlanService;
use App\Services\IngredientSearchService;
use App\Services\IngredientService;
use App\Services\Interfaces\DietPlanInterface;
use App\Services\Interfaces\IngredientInterface;
use App\Services\Interfaces\IngredientSearchInterface;
use App\Services\Interfaces\ProfileCreateOrUpdateInterface;
use App\Services\Interfaces\RecipeCreateOrUpdateInterface;
use App\Services\Interfaces\RecipeSearchInterface;
use App\Services\Interfaces\RelateIngredientsToRecipeInterface;
use App\Services\Interfaces\GetShoppingListInterface;
use App\Services\Interfaces\UpdateShoppingListInterface;
use App\Services\ProfileCreateOrUpdateService;
use App\Services\RecipeCreateOrUpdateService;
use App\Services\RecipeSearchService;
use App\Services\RelateIngredientsToRecipeService;
use App\Services\GetShoppingListService;
use App\Services\UpdateShoppingListService;
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
        $this->app->bind(GetShoppingListInterface::class, GetShoppingListService::class);
        $this->app->bind(IngredientInterface::class, IngredientService::class);
        $this->app->bind(IngredientSearchInterface::class, IngredientSearchService::class);
        $this->app->bind(ProfileCreateOrUpdateInterface::class, ProfileCreateOrUpdateService::class);
        $this->app->bind(RecipeCreateOrUpdateInterface::class, RecipeCreateOrUpdateService::class);
        $this->app->bind(RelateIngredientsToRecipeInterface::class, RelateIngredientsToRecipeService::class);
        $this->app->bind(UpdateShoppingListInterface::class, UpdateShoppingListService::class);
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
