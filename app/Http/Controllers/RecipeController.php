<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $validated = $request->validate([
            'name' => 'required|unique:recipes,name',
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|numeric|min:1',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|numeric|min:1',
            'description' => 'required|string'
        ]);

        $recipe = new Recipe;
        $recipe->name = $request->name;
        if ($request->image == NULL)
            $recipe->image = 'default';
        else
            $recipe->image = $request->image;
        $recipe->protein = $request->protein;
        $recipe->fat = $request->fat;
        $recipe->carbohydrate = $request->carbohydrate;
        $recipe->kcal = $request->kcal;
        $recipe->description = $request->description;
        $recipe->save();
        foreach ($request->ids as $i => $id)
            $recipe->ingredients()->attach($id, ['amount' => $request->quantity[$i]]);

        return redirect('/recipe/'.$recipe->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
