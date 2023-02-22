<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UnitRepositoryInterface;
use App\Services\IngredientCategoryService;
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
    protected $ingredientCategoryService;
    protected $unitRepository;

    public function __construct(
        GetShoppingListInterface $getShoppingListService,
        UpdateShoppingListInterface $updateShoppingListService,
        EditShoppingListInterface $editShoppingListService,
        DeleteShoppingListInterface $deleteShoppingListService,
        IngredientCategoryService $ingredientCategoryService,
        UnitRepositoryInterface $unitRepository
    ) {
        $this->getShoppingListService = $getShoppingListService;
        $this->updateShoppingListService = $updateShoppingListService;
        $this->editShoppingListService = $editShoppingListService;
        $this->deleteShoppingListService = $deleteShoppingListService;
        $this->ingredientCategoryService = $ingredientCategoryService;
        $this->unitRepository = $unitRepository;
    }

    public function index()
    {
        return View::make('shopping-list', [
            'list' => $this->getShoppingListService->retrieveForUser(Auth()->user()->id),
            'trashed' => $this->getShoppingListService->getTrashed(),
            'categories' => $this->ingredientCategoryService->getAllByName(),
            'units' => $this->unitRepository->getAll(),
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

    public function trash(Request $request)
    {
        $this->deleteShoppingListService->setUser(Auth()->user()->id);

        return response()->json(
            $this->deleteShoppingListService
                ->trash(
                    $request->id
                )
        );
    }

    public function restore(Request $request)
    {
        $this->deleteShoppingListService->setUser(Auth()->user()->id);

        return response()->json(
            $this->deleteShoppingListService
                ->restore(
                    $request->id
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

    public function add(Request $request)
    {
        $this->updateShoppingListService
            ->setUser(Auth()->user()->id)
            ->add(
                $request->all()
        );

        return response()->redirectTo('/shopping-list');
    }
}
