<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $formValidation = [
        'protein' => 'required|regex:/^[0-9]+(\.[0-9])?$/',
        'fat' => 'required|regex:/^[0-9]+(\.[0-9])?$/',
        'carbohydrate' => 'required|regex:/^[0-9]+(\.[0-9])?$/',
        'kcal' => 'required|numeric'
    ];

    public function index()
    {
        $ingredients = Ingredient::all()
                                ->sortBy('name');
        foreach ($ingredients as $ingredient)
        {
            $ingredient->protein /= 10;
            $ingredient->fat /= 10;
            $ingredient->carbohydrate /= 10;
        }

        return View::make('ingredient.listing', [
            'ingredients' => $ingredients,
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
            'name' => 'required|unique:ingredients,name'], $this->formValidation));

        $request->protein *= 10;
        $request->fat *= 10;
        $request->carbohydrate *= 10;

        $ingredient = Ingredient::create($request->all());

        return redirect('/ingredient/'.$ingredient->id);
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
        $ingredient = Ingredient::find($id);
        $ingredient->protein /= 10;
        $ingredient->fat /= 10;
        $ingredient->carbohydrate /= 10;

        return View::make('ingredient.edit', [
            'ingredient' => $ingredient,
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
    public function update(Request $request, $id)
    {
        // $validated = $request->validated();
        $validated = $request->validate(array_merge([
            'name' => 'required'], $this->formValidation));

        $ingredient = Ingredient::find($id);
        $ingredient->name = $request->name;
        $ingredient->protein = $request->protein * 10;
        $ingredient->fat = $request->fat * 10;
        $ingredient->carbohydrate = $request->carbohydrate * 10;
        $ingredient->kcal = $request->kcal;
        $ingredient->unit_id = $request->unit;

        $ingredient->save();

        return redirect('/ingredient/'.$ingredient->id);
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

    public function search(Request $request)
    {
        $ingredients = Ingredient::where('name', 'like', '%'.$request->input('name').'%')->limit(5)->get();

        if (count($ingredients) > 0)
            foreach ($ingredients as $ingredient)
                $result[] = ['id' => $ingredient->id, 'name' => $ingredient->name];
        else
            $result = [];

        // dd($result);

        return response()->json($result);
    }
}
