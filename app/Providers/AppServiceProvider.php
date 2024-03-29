<?php

namespace App\Providers;

use App\Models\Recipe;
use App\Models\User;
use App\Observers\RecipeObserver;
use App\Services\AddMealsToDietPlanService;
use App\Services\DietPlanService;
use App\Services\Image\RecipeImageProcessor;
use App\Services\IngredientCategoryService;
use App\Services\IngredientSearchService;
use App\Services\IngredientService;
use App\Services\Interfaces\AddMealsToDietPlanInterface;
use App\Services\Interfaces\DietPlanInterface;
use App\Services\Interfaces\Image\ImageProcessorInterface;
use App\Services\Interfaces\IngredientCategoryInterface;
use App\Services\Interfaces\IngredientInterface;
use App\Services\Interfaces\IngredientSearchInterface;
use App\Services\Interfaces\MealInterface;
use App\Services\Interfaces\ProfileCreateOrUpdateInterface;
use App\Services\Interfaces\Recipe\RecipeCreateOrUpdateInterface;
use App\Services\Interfaces\Recipe\RecipeSearchInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;
use App\Services\Interfaces\Recipe\SelectRecipeForDietInterface;
use App\Services\Interfaces\ShoppingList\DeleteShoppingListInterface;
use App\Services\Interfaces\ShoppingList\EditShoppingListInterface;
use App\Services\Interfaces\ShoppingList\GetShoppingListInterface;
use App\Services\Interfaces\ShoppingList\UpdateShoppingListInterface;
use App\Services\Interfaces\UserDietInterface;
use App\Services\MealService;
use App\Services\ProfileCreateOrUpdateService;
use App\Services\Recipe\RecipeCreateOrUpdateService;
use App\Services\Recipe\RecipeSearchService;
use App\Services\Recipe\RelateIngredientsToRecipeService;
use App\Services\Recipe\SelectRecipeForDietService;
use App\Services\ShoppingList\DeleteShoppingListService;
use App\Services\ShoppingList\EditShoppingListService;
use App\Services\ShoppingList\GetShoppingListService;
use App\Services\ShoppingList\UpdateShoppingListService;
use App\Services\UserDietService;
use Illuminate\Support\Facades\Gate;
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
        $this->app->bind(AddMealsToDietPlanInterface::class, AddMealsToDietPlanService::class);
        $this->app->bind(DeleteShoppingListInterface::class, DeleteShoppingListService::class);
        $this->app->bind(DietPlanInterface::class, DietPlanService::class);
        $this->app->bind(EditShoppingListInterface::class, EditShoppingListService::class);
        $this->app->bind(GetShoppingListInterface::class, GetShoppingListService::class);
        $this->app->bind(ImageProcessorInterface::class, RecipeImageProcessor::class);
        $this->app->bind(IngredientInterface::class, IngredientService::class);
        $this->app->bind(IngredientCategoryInterface::class, IngredientCategoryService::class);
        $this->app->bind(IngredientSearchInterface::class, IngredientSearchService::class);
        $this->app->bind(MealInterface::class, MealService::class);
        $this->app->bind(ProfileCreateOrUpdateInterface::class, ProfileCreateOrUpdateService::class);
        $this->app->bind(RecipeCreateOrUpdateInterface::class, RecipeCreateOrUpdateService::class);
        $this->app->bind(RecipeSearchInterface::class, RecipeSearchService::class);
        $this->app->bind(RelateIngredientsToRecipeInterface::class, RelateIngredientsToRecipeService::class);
        $this->app->bind(SelectRecipeForDietInterface::class, SelectRecipeForDietService::class);
        $this->app->bind(UpdateShoppingListInterface::class, UpdateShoppingListService::class);
        $this->app->bind(UserDietInterface::class, UserDietService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Recipe::observe(RecipeObserver::class);
        Gate::define('viewWebSocketsDashboard', function (User $user) {
            return $user->is('admin');
        });
    }
}
