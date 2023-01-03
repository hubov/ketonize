<?php

namespace App\Providers;

use App\Repositories\DietMealDivisionRepository;
use App\Repositories\DietPlanRepository;
use App\Repositories\DietRepository;
use App\Repositories\IngredientRepository;
use App\Repositories\Interfaces\DietMealDivisionRepositoryInterface;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use App\Repositories\Interfaces\DietRepositoryInterface;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\MealRepository;
use App\Repositories\RecipeRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            DietMealDivisionRepositoryInterface::class,
            DietMealDivisionRepository::class
        );
        $this->app->bind(
            DietPlanRepositoryInterface::class,
            DietPlanRepository::class
        );
        $this->app->bind(
            DietRepositoryInterface::class,
            DietRepository::class
        );
        $this->app->bind(
            IngredientRepositoryInterface::class,
            IngredientRepository::class
        );
        $this->app->bind(
            MealRepositoryInterface::class,
            MealRepository::class
        );
        $this->app->bind(
            RecipeRepositoryInterface::class,
            RecipeRepository::class
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
