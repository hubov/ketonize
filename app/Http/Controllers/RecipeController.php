<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Repositories\Interfaces\IngredientCategoryRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\UnitRepositoryInterface;
use App\Services\Interfaces\RecipeCreateOrUpdateInterface;
use App\Services\Interfaces\RecipeSearchInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RecipeController extends Controller
{
    protected $recipeRepository;
    protected $recipeCreateOrUpdate;
    protected $ingredientCategoryRepository;
    protected $unitRepository;
    protected $tagRepository;

    public function __construct(RecipeRepositoryInterface $recipeRepository, RecipeCreateOrUpdateInterface $recipeCreateOrUpdate, IngredientCategoryRepositoryInterface $ingredientCategoryRepository, UnitRepositoryInterface $unitRepository, TagRepositoryInterface $tagRepository)
    {
        $this->recipeRepository = $recipeRepository;
        $this->recipeCreateOrUpdate = $recipeCreateOrUpdate;
        $this->ingredientCategoryRepository = $ingredientCategoryRepository;
        $this->unitRepository = $unitRepository;
        $this->tagRepository = $tagRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('recipe.listing', [
            'recipes' => $this->recipeRepository->getAll()
                                                ->sortBy('name'),
            'units' => $this->unitRepository->getAll(),
            'categories' => $this->ingredientCategoryRepository->getAll()
                                                                ->sortBy('name'),
            'tagsList' => $this->tagRepository->getAll()
                                                ->sortBy('name')
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
        $recipe = $this->recipeCreateOrUpdate->perform($request->input());

        return redirect('/recipe/'.$recipe->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug, $modifier = 100)
    {
        $recipe = $this->recipeRepository->getBySlug($slug);

        $weightTotal = 0;
        foreach  ($recipe->ingredients as $ingredient) {
            $ingredient->pivot->amount = round($ingredient->pivot->amount * $modifier / 100);
            $weightTotal += $ingredient->pivot->amount;
        }

        $recipe->protein *= $modifier / 100;
        $recipe->fat *= $modifier / 100;
        $recipe->carbohydrate *= $modifier / 100;
        $recipe->kcal *= $modifier / 100;

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
            'admin' => Auth()->user()->is('admin'),
            'categories' => $this->ingredientCategoryRepository->getAll()->sortBy('name'),
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
        return View::make('recipe.edit', [
            'recipe' => $this->recipeRepository->getBySlug($slug),
            'units' => $this->unitRepository->getAll(),
            'categories' => $this->ingredientCategoryRepository->getAll()->sortBy('name'),
            'tagsList' => $this->tagRepository->getAll()->sortBy('name')
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
        $recipe = $this->recipeCreateOrUpdate->perform($request->input(), $slug);

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
        $recipe = $this->recipeRepository->getBySlug($request->slug)
                                        ->load('ingredients.unit');

        return response()->json([
            'name' => $recipe->name,
            'image' => $recipe->image,
            'ingredients' => $recipe->ingredients,
            'description' => $recipe->description
         ]);
    }
}
