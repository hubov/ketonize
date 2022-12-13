<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */

    public function index()
    {
        $ingredients = Ingredient::all()
                                ->sortBy('name');

        return View::make('ingredient.listing', [
            'ingredients' => $ingredients,
            'units' => Unit::all(),
            'categories' => IngredientCategory::orderBy('name')->get()
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
    public function store(StoreIngredientRequest $request)
    {
        $ingredient = Ingredient::create($request->all());

        return redirect('/ingredient/'.$ingredient->id);
    }

    public function ajaxStore(StoreIngredientRequest $request)
    {
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
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $ingredient = Ingredient::find($id);

        return View::make('ingredient.edit', [
            'ingredient' => $ingredient,
            'units' => Unit::all(),
            'categories' => IngredientCategory::orderBy('name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateIngredientRequest $request, $id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->fill([
            'name' => $request->name,
            'ingredient_category_id' => $request->ingredient_category_id,
            'protein' => $request->protein,
            'fat' => $request->fat,
            'carbohydrate' => $request->carbohydrate,
            'kcal' => $request->kcal,
            'unit_id' => $request->unit_id
        ]);
        $ingredient->save();

        return redirect('/ingredient/'.$ingredient->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::find($id);
        if (count($ingredient->recipes) > 0) {
            foreach ($ingredient->recipes as $recipe) {
                $results[] = $recipe->slug;
            }
            return response()->json(['error' => TRUE, 'recipes' => $results], 403);
        }

        Ingredient::destroy($id);

        return response()->json(TRUE);
    }

    public function search(Request $request)
    {
        $ingredients = Ingredient::where('name', 'like', '%'.$request->input('name').'%')->limit(20)->get();

        if (count($ingredients) > 0) {
            $i = 0;
            foreach ($ingredients as $ingredient) {
                $result[levenshtein($request->input('name'), $ingredient->name)*100+$i] = [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'unit' => $ingredient->unit->symbol,
                    'protein' => $ingredient->protein,
                    'fat' => $ingredient->fat,
                    'carbohydrate' => $ingredient->carbohydrate,
                    'kcal' => $ingredient->kcal
                ];
                $i++;
            }
        } else {
            $result = [];
        }

        return response()->json($result);
    }

    public function upload(Request $request)
    {
        if ($request->file('bulk_upload')->isValid()) {
            $file = fopen($request->file('bulk_upload')->path(), 'r');
            $head = fgetcsv($file, 4096, ',', '"');

            while (($data = fgetcsv($file, 4096, ",")) !== FALSE) {
                for ($i = 3; $i <= 33; $i++) {
                    if ($data[$i][0] == '<') {
                        $data[$i] = substr($data[$i], 1);
                    } else {
                        if (($data[$i] == 'n.d.') || ($data[$i] == 'tr.')) {
                            $data[$i] = 0;
                        }
                    }
                }

                $category = IngredientCategory::where('name', $data[2])->first();
                $ingredient = Ingredient::create([
                    'name' => $data[0],
                    'protein' => $data[13],
                    'fat' => $data[4],
                    'carbohydrate' => $data[9],
                    'kcal' => $data[3],
                    'ingredient_category_id' => $category->id,
                    'unit_id' => $data[1]
                ]);

                unset($data[0]);
                unset($data[1]);
                unset($data[2]);
                unset($data[3]);
                unset($data[4]);
                unset($data[9]);
                unset($data[13]);
                $data = array_values($data);

                foreach ($data as $i => $value) {
                    if ($value > 0) {
                        $ingredient->nutrients()->attach(($i + 1), ['amount' => $value]);
                    }
                }
            }
        }
    }
}
