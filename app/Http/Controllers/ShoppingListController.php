<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Services\Interfaces\ShoppingList\DeleteShoppingListInterface;
use App\Services\Interfaces\ShoppingList\EditShoppingListInterface;
use App\Services\Interfaces\ShoppingList\GetShoppingListInterface;
use App\Services\Interfaces\ShoppingList\UpdateShoppingListInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ShoppingListController extends Controller
{
    protected $getShoppingListService;
    protected $updateShoppingListService;
    protected $editShoppingListService;
    protected $deleteShoppingListService;

    public function __construct(GetShoppingListInterface $getShoppingListService, UpdateShoppingListInterface $updateShoppingListService, EditShoppingListInterface $editShoppingListService, DeleteShoppingListInterface $deleteShoppingListService)
    {
        $this->getShoppingListService = $getShoppingListService;
        $this->updateShoppingListService = $updateShoppingListService;
        $this->editShoppingListService = $editShoppingListService;
        $this->deleteShoppingListService = $deleteShoppingListService;
    }

    public function index()
    {
        return View::make('shopping-list', [
            'list' => $this->getShoppingListService->retrieveForUser(Auth()->user()->id),
            'trashed' => $this->getShoppingListService->getTrashed(),
            'date_from' => date("Y-m-d"),
            'date_to' => date("Y-m-d"),
        ]);
    }

    public function update(Request $request)
    {
        $this->updateShoppingListService
            ->setUser(
                Auth()->user()->id
            )
            ->setDates(
                $request->date_from,
                $request->date_to
            )->update();

        return redirect('/shopping-list');
    }

    public function edit(Request $request)
    {
        $this->editShoppingListService
            ->setUser(
                Auth()->user()->id
            );

        return response()->json(
            $this->editShoppingListService
                ->update(
                    $request->id,
                    $request->amount
                )
        );
    }

    public function destroy(Request $request)
    {
        $this->deleteShoppingListService->setUser(Auth()->user()->id);

        return response()->json(
            $this->deleteShoppingListService
                ->delete(
                    $request->id
                )
        );
    }
}
