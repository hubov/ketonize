<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\IngredientCategory;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\Unit;
use App\Services\Interfaces\RecipeSearchInterface;
use App\Services\RecipeSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $recipes = Recipe::all()
                    ->sortBy('name');

        return View::make('recipe.listing', [
            'recipes' => $recipes,
            'units' => Unit::all(),
            'categories' => IngredientCategory::orderBy('name')->get(),
            'tagsList' => Tag::orderBy('name')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreRecipeRequest $request)
    {
        $recipe = Recipe::create([
            'name' => $request->name,
            'image' => ($request->image == NULL) ? 'default' : $request->image,
            'description' => $request->description,
            'preparation_time' => $request->preparation_time,
            'cooking_time' => $request->cooking_time
        ]);

        $recipe->setIngredients($request->ids, $request->quantity);

        foreach ($request->tags as $tag) {
            $recipe->tags()->attach($tag);
        }

        $recipe->save();

        return redirect('/recipe/'.$recipe->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug, $modifier = NULL)
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $weightTotal = 0;
        foreach  ($recipe->ingredients as $ingredient) {
            $ingredient->pivot->amount = ($modifier !== NULL) ? round($ingredient->pivot->amount * $modifier / 100) : 0;
            $weightTotal += $ingredient->pivot->amount;
        }

        if ($modifier !== NULL) {
            $recipe->protein *= $modifier / 100;
            $recipe->fat *= $modifier / 100;
            $recipe->carbohydrate *= $modifier / 100;
            $recipe->kcal *= $modifier / 100;
        }

        $user = Auth::user();
        $isAdmin = $user->is('admin');

        return View::make('recipe.single', [
            'name' => $recipe->name,
            'image' => $recipe->image,
            'protein' => round($recipe->protein),
            'fat' => round($recipe->fat),
            'carbohydrate' => round($recipe->carbohydrate),
            'preparationTime' => $recipe->preparation_time,
            'cookingTime' => $recipe->cooking_time,
            'kcal' => round($recipe->kcal),
            'ingredients' => $recipe->ingredients,
            'description' => $recipe->description,
            'weightTotal' => $weightTotal,
            'admin' => $isAdmin,
            'categories' => IngredientCategory::orderBy('name')->get(),
            'displayMacros' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($slug)
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        return View::make('recipe.edit', [
            'recipe' => $recipe,
            'units' => Unit::all(),
            'categories' => IngredientCategory::orderBy('name')->get(),
            'tagsList' => Tag::orderBy('name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateRecipeRequest $request, $slug)
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $recipe->setIngredients($request->ids, $request->quantity);
        $recipe->fill([
            'name' => $request->name,
            'image' => ($request->image == NULL) ? 'default' : $request->image,
            'description' => $request->description,
            'preparation_time' => $request->preparation_time,
            'cooking_time' => $request->cooking_time
        ]);
        $recipe->save();
        $recipe->tags()->sync($request->tags);

        return redirect('/recipe/'.$recipe->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Search for resources matching given criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request, RecipeSearchInterface $recipeSearch)
    {
        $filters = $request->searchFilter;
        if ($filters['tags'] != '0') {
            $filters['tags'] = explode(",", $filters['tags']);
        } else {
            $filters['tags'] = NULL;
        }

        $recipeSearch = new RecipeSearchService;
        $recipeSearch->filters($filters);

        return response()->json($recipeSearch->search());
    }

    /**
     * Get raw data of a single recipe.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showRaw(Request $request)
    {
        $recipe = Recipe::where('slug', $request->slug)->with('ingredients.unit')->firstOrFail();

        return response()->json([
            'name' => $recipe->name,
            'image' => $recipe->image,
            'ingredients' => $recipe->ingredients,
            'description' => $recipe->description
         ]);
    }
}
