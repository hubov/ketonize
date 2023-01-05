<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\ShoppingList;
use App\Services\Interfaces\GetShoppingListInterface;
use App\Services\Interfaces\UpdateShoppingListInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ShoppingListController extends Controller
{
    protected $getShoppingListService;
    protected $updateShoppingListService;

    public function __construct(GetShoppingListInterface $getShoppingListService, UpdateShoppingListInterface $updateShoppingListService)
    {
        $this->getShoppingListService = $getShoppingListService;
        $this->updateShoppingListService = $updateShoppingListService;
    }

    public function index()
    {
        return View::make('shopping-list', [
            'list' => $this->getShoppingListService->retrieveForUser(Auth()->user()->id),
            'date_from' => date("Y-m-d"),
            'date_to' => date("Y-m-d"),
        ]);
    }

    public function update(Request $request)
    {
        $this->updateShoppingListService->setUser(Auth()->user()->id)
                                        ->setDates($request->date_from, $request->date_to)
                                        ->update();

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
