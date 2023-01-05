<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\ShoppingList;
use App\Services\Interfaces\GetShoppingListInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ShoppingListController extends Controller
{
    protected $shoppingListService;

    public function __construct(GetShoppingListInterface $shoppingListService)
    {
        $this->shoppingListService = $shoppingListService;
    }

    public function index()
    {
        return View::make('shopping-list', [
            'list' => $this->shoppingListService->retrieveForUser(Auth()->user()->id),
            'date_from' => date("Y-m-d"),
            'date_to' => date("Y-m-d"),
        ]);
    }

    public function update(Request $request)
    {
        $meals = Meal::join('diet_plans', 'meals.diet_plan_id', 'diet_plans.id')
                            ->where('user_id', Auth()->user()->id)
                            ->where('date_on', '>=', $request->date_from)
                            ->where('date_on', '<=', $request->date_to)
                            ->get();

        $ingredients = [];
        foreach ($meals as $meal) {
            foreach ($meal->recipe->ingredients as $ingredient) {
                if (isset($ingredients[$ingredient->id])) {
                    $ingredients[$ingredient->id]['amount'] += round($ingredient->pivot->amount * $meal->modifier / 100);
                } else {
                    $ingredients[$ingredient->id] = [
                            'name' => $ingredient->name,
                            'amount' => round($ingredient->pivot->amount * $meal->modifier / 100)
                    ];
                }
            }
        }

        ShoppingList::where('user_id', Auth()->user()->id)->delete();

        foreach ($ingredients as $id => $ingredient) {
            ShoppingList::create([
                'user_id' => Auth()->user()->id,
                'ingredient_id' => $id,
                'amount' => $ingredient['amount']
            ]);
        }

        return redirect('/shopping-list');
    }

    public function edit(Request $request)
    {
        $list = ShoppingList::where('id', $request->id)
                    ->where('user_id', Auth()->user()->id)
                    ->first();
        if ($list !== NULL) {
            $list->amount = $request->amount;
            $list->save();
        } else {
            return response()->json(FALSE);
        }

        return response()->json(TRUE);
    }

    public function destroy(Request $request)
    {
        ShoppingList::where('id', $request->id)
                    ->where('user_id', Auth()->user()->id)
                    ->delete();

        return response()->json(TRUE);
    }
}
