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
        'protein' => 'required|regex:/^[0-9]+(\.[0-9]+)?$/',
        'fat' => 'required|regex:/^[0-9]+(\.[0-9]+)?$/',
        'carbohydrate' => 'required|regex:/^[0-9]+(\.[0-9]+)?$/',
        'kcal' => 'required|numeric',
        'unit_id' => 'required|numeric'
    ];

    public function index()
    {
        $ingredients = Ingredient::all()
                                ->sortBy('name');

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

        $ingredient = Ingredient::create($request->all());

        return redirect('/ingredient/'.$ingredient->id);
    }

    public function ajaxStore(Request $request)
    {
        $validated = $request->validate(array_merge([
            'name' => 'required|unique:ingredients,name'], $this->formValidation));

        $ingredient = Ingredient::create($request->all());

        return response()->json(['id' => $ingredient->id]);
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
        $validated = $request->validate(array_merge([
            'name' => 'required'], $this->formValidation));

        $ingredient = Ingredient::find($id);
        $ingredient->name = $request->name;
        $ingredient->protein = $request->protein;
        $ingredient->fat = $request->fat;
        $ingredient->carbohydrate = $request->carbohydrate;
        $ingredient->kcal = $request->kcal;
        $ingredient->unit_id = $request->unit_id;
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
            {
                $result[] = [
                    'id' => $ingredient->id, 
                    'name' => $ingredient->name, 
                    'unit' => $ingredient->unit->symbol, 
                    'protein' => $ingredient->protein, 
                    'fat' => $ingredient->fat, 
                    'carbohydrate' => $ingredient->carbohydrate,
                    'kcal' => $ingredient->kcal
                ];
            }
        else
            $result = [];

        return response()->json($result);
    }
}
