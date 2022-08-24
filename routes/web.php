<?php

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

Route::get('/recipe/{slug}', [RecipeController::class, 'show'])->where('slug', '[0-9a-z\-]+');
Route::get('/recipes', [RecipeController::class, 'index']);
Route::post('/recipes', [RecipeController::class, 'store']);
Route::get('/ingredients', [IngredientController::class, 'index']);
Route::get('/ingredient-autocomplete', [IngredientController::class, 'search']);
Route::get('/ingredient/{id}', [IngredientController::class, 'edit'])->whereNumber('id');
Route::post('/ingredient/{id}', [IngredientController::class, 'update'])->whereNumber('id');
Route::post('/ingredient/new', [IngredientController::class, 'ajaxStore']);
Route::post('/ingredients', [IngredientController::class, 'store']); // old store
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');