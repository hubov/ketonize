<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Unit;
use Illuminate\Http\Request;
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
            'units' => Unit::all()
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
     * @return \Illuminate\Http\Response
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
        $recipe->save();

        return redirect('/recipe/'.$recipe->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $weightTotal = 0;
        foreach  ($recipe->ingredients as $ingredient) {
            $weightTotal += $ingredient->pivot->amount;
        }

        return View::make('recipe.single', [
            'name' => $recipe->name,
            'protein' => $recipe->protein,
            'fat' => $recipe->fat,
            'carbohydrate' => $recipe->carbohydrate,
            'kcal' => $recipe->kcal,
            'ingredients' => $recipe->ingredients,
            'description' => $recipe->description,
            'weightTotal' => $weightTotal
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
            'units' => Unit::all()
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
        $ingredients = Ingredient::whereIn('id', $request->ids)->get();
        foreach  ($request->ids as $i => $id) {
            $recipe->protein += $request->quantity[$i] * $ingredients->find($id)->protein / 100;
            $recipe->fat += $request->quantity[$i] * $ingredients->find($id)->fat / 100;
            $recipe->carbohydrate += $request->quantity[$i] * $ingredients->find($id)->carbohydrate / 100;
            $recipe->kcal += $request->quantity[$i] * $ingredients->find($id)->kcal / 100;
        }

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
