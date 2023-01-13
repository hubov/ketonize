<?php

use App\Http\Controllers\DietPlanController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ShoppingListController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('role:admin')->group(function() {
    Route::get('/recipe/{slug}/edit', [RecipeController::class, 'edit'])->where('slug', '[0-9a-z\-]+');
    Route::put('/recipe/{slug}/edit', [RecipeController::class, 'update'])->where('slug', '[0-9a-z\-]+');
    Route::delete('/ingredient/{id}/delete', [IngredientController::class, 'destroy'])->whereNumber('id');
    Route::get('/ingredient/{id}', [IngredientController::class, 'edit'])->whereNumber('id');
    Route::put('/ingredient/{id}', [IngredientController::class, 'update'])->whereNumber('id');
    Route::post('/ingredient/new', [IngredientController::class, 'ajaxStore']);
    Route::post('/ingredients/bulk', [IngredientController::class, 'upload']);
    Route::post('/ingredients', [IngredientController::class, 'store']); // old store
    Route::post('/recipes', [RecipeController::class, 'store']);
});
Route::middleware('auth')->group(function() {
    Route::post('/dashboard/is-ready', [DietPlanController::class, 'isReady']);
    Route::middleware('profile.completeness')->group(function() {
        Route::get('/dashboard/{date}', [DietPlanController::class, 'index'])->where('date', '202[0-9]\-(0[1-9]|1[0-2])\-[0-3][0-9]');
        Route::get('/dashboard', [DietPlanController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/generate/{date}', [DietPlanController::class, 'generate'])->where('date', '202[0-9]\-(0[1-9]|1[0-2])\-[0-3][0-9]');
        Route::get('/shopping-list', [ShoppingListController::class, 'index']);
        Route::post('/shopping-list', [ShoppingListController::class, 'update']);
        Route::put('/shopping-list/update', [ShoppingListController::class, 'edit']);
        Route::delete('/shopping-list/delete', [ShoppingListController::class, 'destroy']);
        Route::get('/recipe/{slug}/{modifier}', [RecipeController::class, 'show'])->where('slug', '[0-9a-z\-]+')->where('modifier', '[0-9]+');
        Route::get('/recipe/{slug}', [RecipeController::class, 'show'])->where('slug', '[0-9a-z\-]+');
        Route::post('/recipes/search', [RecipeController::class, 'search'])->middleware(['auth']);
        Route::post('/recipe/search', [RecipeController::class, 'showRaw'])->middleware(['auth']);
        Route::post('/diet/update', [DietPlanController::class, 'update']);
        Route::get('/recipes', [RecipeController::class, 'index'])->middleware(['auth']);
        Route::get('/ingredients', [IngredientController::class, 'index']);
        Route::get('/ingredient-autocomplete', [IngredientController::class, 'search']);
        Route::get('/profile', [ProfileController::class, 'edit']);
        Route::post('/profile', [ProfileController::class, 'update']);
    });
    Route::get('/profile/new', [ProfileController::class, 'create']);
    Route::post('/profile/new', [ProfileController::class, 'store']);
});

require __DIR__.'/auth.php';

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
