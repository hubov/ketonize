<?php

use App\Http\Controllers\DietPlanController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
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
    Route::post('/recipe/{slug}/edit', [RecipeController::class, 'update'])->where('slug', '[0-9a-z\-]+');
    Route::get('/ingredient/{id}', [IngredientController::class, 'edit'])->whereNumber('id');
    Route::post('/ingredient/{id}', [IngredientController::class, 'update'])->whereNumber('id');
    Route::post('/ingredient/new', [IngredientController::class, 'ajaxStore']);
    Route::post('/ingredients', [IngredientController::class, 'store']); // old store
    Route::post('/recipes', [RecipeController::class, 'store']);
});
Route::middleware('auth')->group(function() {
    Route::get('/dashboard/{date}', [DietPlanController::class, 'index'])->where('date', '202[0-9]\-(0[1-9]|1[0-2])\-[0-3][0-9]');
    Route::get('/dashboard', [DietPlanController::class, 'index'])->name('dashboard');
    Route::get('/recipe/{slug}/{modifier}', [RecipeController::class, 'show'])->where('slug', '[0-9a-z\-]+')->where('modifier', '[0-9]+');
    Route::get('/recipe/{slug}', [RecipeController::class, 'show'])->where('slug', '[0-9a-z\-]+');
    Route::get('/recipes', [RecipeController::class, 'index'])->middleware(['auth']);
    Route::get('/ingredients', [IngredientController::class, 'index']);
    Route::get('/ingredient-autocomplete', [IngredientController::class, 'search']);
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');