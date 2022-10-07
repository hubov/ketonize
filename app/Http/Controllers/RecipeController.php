<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $formValidation = [
        'ids' => 'required|array|min:1',
        'ids.*' => 'required|numeric|min:1',
        'quantity' => 'required|array|min:1',
        'quantity.*' => 'required|numeric|min:1',
        'description' => 'required|string',
        'preparation_time' => 'required|numeric',
        'cooking_time' => 'required|numeric'
    ];

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
    public function store(Request $request)
    {
        $validated = $request->validate(array_merge([
            'name' => 'required|unique:recipes,name'], $this->formValidation));

        $recipe = new Recipe;
        $recipe->name = $request->name;
        $recipe->slug = Str::of($request->name)->slug('-');
        if ($request->image == NULL)
            $recipe->image = 'default';
        else
            $recipe->image = $request->image;
        $recipe->protein = 0;
        $recipe->fat = 0;
        $recipe->carbohydrate = 0;
        $recipe->kcal = 0;
        $recipe->protein_ratio = 0;
        $recipe->fat_ratio = 0;
        $recipe->carbohydrate_ratio = 0;
        $recipe->description = $request->description;
        $recipe->preparation_time = $request->preparation_time;
        $recipe->cooking_time = $request->cooking_time;
        $recipe->total_time = $request->preparation_time + $request->cooking_time;
        $recipe->save();

        $ingredients = Ingredient::whereIn('id', $request->ids)->get();
        foreach ($request->ids as $i => $id)
        {
            $recipe->ingredients()->attach($id, ['amount' => $request->quantity[$i]]);
            $recipe->protein += $request->quantity[$i] * $ingredients->find($id)->protein / 100;
            $recipe->fat += $request->quantity[$i] * $ingredients->find($id)->fat / 100;
            $recipe->carbohydrate += $request->quantity[$i] * $ingredients->find($id)->carbohydrate / 100;
            $recipe->kcal += $request->quantity[$i] * $ingredients->find($id)->kcal / 100;
        }
        $macros = $recipe->protein + $recipe->fat + $recipe->carbohydrate;
        $recipe->protein_ratio = round($recipe->protein / $macros * 100);
        $recipe->fat_ratio = round($recipe->fat / $macros * 100);
        $recipe->carbohydrate_ratio = round($recipe->carbohydrate / $macros * 100);

        foreach ($request->tags as $tag)
            $recipe->tags()->attach($tag);

        $recipe->save();

        return redirect('/recipe/'.$recipe->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $modifier = NULL)
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $weightTotal = 0;
        foreach  ($recipe->ingredients as $ingredient) {
            if ($modifier !== NULL)
                $ingredient->pivot->amount = round($ingredient->pivot->amount * $modifier / 100);

            $weightTotal += $ingredient->pivot->amount;
        }

        if ($modifier !== NULL)
        {
            $recipe->protein *= $modifier / 100;
            $recipe->fat *= $modifier / 100;
            $recipe->carbohydrate *= $modifier / 100;
            $recipe->kcal *= $modifier / 100;
        }

        $user = Auth::user();
        $isAdmin = $user->is('admin');

        return View::make('recipe.single', [
            'name' => $recipe->name,
            'protein' => round($recipe->protein),
            'fat' => round($recipe->fat),
            'carbohydrate' => round($recipe->carbohydrate),
            'kcal' => round($recipe->kcal),
            'ingredients' => $recipe->ingredients,
            'description' => $recipe->description,
            'weightTotal' => $weightTotal,
            'admin' => $isAdmin,
            'categories' => IngredientCategory::orderBy('name')->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $validated = $request->validate(array_merge([
            'name' => 'required'], $this->formValidation));

        $recipe = Recipe::where('slug', $slug)->firstOrFail();
        $recipe->protein = 0;
        $recipe->fat = 0;
        $recipe->carbohydrate = 0;
        $recipe->kcal = 0;
        $recipe->protein_ratio = 0;
        $recipe->fat_ratio = 0;
        $recipe->carbohydrate_ratio = 0;

        $ingredients = Ingredient::whereIn('id', $request->ids)->get();
        foreach  ($request->ids as $i => $id) {
            $recipe->protein += $request->quantity[$i] * $ingredients->find($id)->protein / 100;
            $recipe->fat += $request->quantity[$i] * $ingredients->find($id)->fat / 100;
            $recipe->carbohydrate += $request->quantity[$i] * $ingredients->find($id)->carbohydrate / 100;
            $recipe->kcal += $request->quantity[$i] * $ingredients->find($id)->kcal / 100;
        }
        $macros = $recipe->protein + $recipe->fat + $recipe->carbohydrate;
        $recipe->protein_ratio = round($recipe->protein / $macros * 100);
        $recipe->fat_ratio = round($recipe->fat / $macros * 100);
        $recipe->carbohydrate_ratio = round($recipe->carbohydrate / $macros * 100);
        $recipe->name = $request->name;
        $recipe->slug = Str::of($request->name)->slug('-');
        if ($request->image == NULL)
            $recipe->image = 'default';
        else
            $recipe->image = $request->image;
        $recipe->description = $request->description;
        $recipe->preparation_time = $request->preparation_time;
        $recipe->cooking_time = $request->cooking_time;
        $recipe->total_time = $request->preparation_time + $request->cooking_time;
        $recipe->save();
        foreach ($request->ids as $i => $id)
            $ingredientsCurrent[$id] = ['amount' => $request->quantity[$i]];
        $recipe->ingredients()->sync($ingredientsCurrent);
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
}
