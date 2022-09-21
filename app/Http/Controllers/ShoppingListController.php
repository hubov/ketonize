<?php

namespace App\Http\Controllers;

use App\Models\DietPlan;
use App\Models\IngredientCategory;
use App\Models\ShoppingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ShoppingListController extends Controller
{
    public function index()
    {
        $list = ShoppingList::join('ingredients', 'shopping_lists.ingredient_id', '=', 'ingredients.id')->where('user_id', Auth::user()->id)->select('shopping_lists.*')->orderBy('ingredients.ingredient_category_id')->orderBy('ingredients.name')->get();

        foreach ($list as $l)
        {
            $category = IngredientCategory::find($l->ingredient->ingredient_category_id)->name;
            $categorisedList[$category][] = $l;
        }

        $date = date("Y-m-d");

        return View::make('shopping-list', [
            'list' => $categorisedList,
            'date_from' => $date,
            'date_to' => $date,
        ]); 
    }

    public function update(Request $request)
    {
        $meals = DietPlan::where('user_id', Auth::user()->id)
                                ->where('date_on', '>=', $request->date_from)
                                ->where('date_on', '<=', $request->date_to)
                                ->get();
        $ingredients = [];
        foreach ($meals as $meal)
        {
            foreach ($meal->recipe->ingredients as $ingredient)
            {
                if (isset($ingredients[$ingredient->id]))
                {
                    $ingredients[$ingredient->id]['amount'] += round($ingredient->pivot->amount * $meal->modifier / 100);
                }
                else
                {
                    $ingredients[$ingredient->id] = [
                            'name' => $ingredient->name,
                            'amount' => round($ingredient->pivot->amount * $meal->modifier / 100)
                    ];
                }
            }
        }

        $oldList = ShoppingList::where('user_id', Auth::user()->id)->get();
        foreach ($oldList as $list)
        {
            $list->delete();
        }

        foreach ($ingredients as $id => $ingredient)
        {
            $newList = new ShoppingList;
            $newList->user_id = Auth::user()->id;
            $newList->ingredient_id = $id;
            $newList->amount = $ingredient['amount'];
            $newList->save();
        }

        return redirect('/shopping-list');
    }

    public function edit(Request $request)
    {
        $list = ShoppingList::find($request->id);
        $list->amount = $request->amount;
        $list->save();

        return response()->json(TRUE);
    }

    public function destroy(Request $request)
    {
        $list = ShoppingList::find($request->id);
        $list->delete();

        return response()->json(TRUE);
    }
}
