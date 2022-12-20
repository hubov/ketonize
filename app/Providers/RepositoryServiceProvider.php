<?php

namespace App\Providers;

use App\Repositories\DietMealDivisionRepository;
use App\Repositories\DietPlanRepository;
use App\Repositories\DietRepository;
use App\Repositories\Interfaces\DietMealDivisionRepositoryInterface;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use App\Repositories\Interfaces\DietRepositoryInterface;
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
            DietPlanRepositoryInterface::class,
            DietPlanRepository::class
        );
        $this->app->bind(
            DietRepositoryInterface::class,
            DietRepository::class
        );
        $this->app->bind(
            DietMealDivisionRepositoryInterface::class,
            DietMealDivisionRepository::class
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
