<?php

namespace Tests\Unit\Services\ShoppingList;

use App\Models\CustomIngredient;
use App\Models\Ingredient;
use App\Models\ShoppingList;
use App\Repositories\Interfaces\CustomIngredientRepositoryInterface;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\Interfaces\MealInterface;
use App\Services\ShoppingList\UpdateShoppingListService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class UpdateShoppingListTest extends TestCase
{
    public $shopppingList;
    public $ingredientRepository;
    public $shoppingListRepository;
    public $mealService;
    public $customIngredientRepository;
    public $updateShoppingListService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shoppingList = new ShoppingList();
        $this->shoppingList->id = 1;

        $this->ingredientRepository = $this->createMock(IngredientRepositoryInterface::class);
        $this->shoppingListRepository = $this->createMock(ShoppingListRepositoryInterface::class);
        $this->mealService = $this->createMock(MealInterface::class);
        $this->customIngredientRepository = $this->createMock(CustomIngredientRepositoryInterface::class);

        $this->updateShoppingListService = new UpdateShoppingListService($this->shoppingListRepository, $this->mealService, $this->ingredientRepository, $this->customIngredientRepository);
    }

    /** @test */
    public function adds_shopping_list_item_with_existing_ingredient()
    {
        $this->withoutEvents();

        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByName')
            ->withAnyParameters()
            ->willReturn(new Ingredient());

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('getByIngredientUser')
            ->withAnyParameters()
            ->willThrowException(new ModelNotFoundException());
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('createForUser')
            ->withAnyParameters()
            ->willReturn($this->shoppingList);

        $result = $this->updateShoppingListService
            ->setUser(1)
            ->add([
                'item_name' => 'Tomato',
                'amount' => 100
            ]);

        $this->assertIsObject($result);
        $this->assertEquals($this->shoppingList->id, $result->id);
    }

    /** @test */
    public function adds_shopping_list_item_with_custom_ingredient()
    {
        $this->withoutEvents();

        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByName')
            ->withAnyParameters()
            ->willThrowException(new ModelNotFoundException());

        $this->customIngredientRepository
            ->expects($this->once())
            ->method('getOrCreateForUserByName')
            ->withAnyParameters()
            ->willReturn(new CustomIngredient());

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('getByIngredientUser')
            ->withAnyParameters()
            ->willThrowException(new ModelNotFoundException());
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('createForUser')
            ->withAnyParameters()
            ->willReturn($this->shoppingList);

        $result = $this->updateShoppingListService
            ->setUser(1)
            ->add([
                'item_name' => 'Tomato',
                'amount' => 100
            ]);

        $this->assertIsObject($result);
        $this->assertEquals($this->shoppingList->id, $result->id);
    }

    /** @test */
    public function adding_existing_shopping_list_item_updates_it()
    {
        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByName')
            ->withAnyParameters()
            ->willReturn(new Ingredient());

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('getByIngredientUser')
            ->withAnyParameters()
            ->willReturn($this->shoppingList);
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('increase')
            ->withAnyParameters();

        $result = $this->updateShoppingListService
            ->setUser(1)
            ->add([
                'item_name' => 'Tomato',
                'amount' => 100
            ]);

        $this->assertIsObject($result);
        $this->assertEquals($this->shoppingList->id, $result->id);
    }

    /** @test */
    public function adding_trashed_shopping_list_item_updates_it()
    {
        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByName')
            ->withAnyParameters()
            ->willReturn(new Ingredient());

        $shoppingList = $this->createMock(ShoppingList::class);
        $shoppingList
            ->expects($this->exactly(3))
            ->method('__get')
            ->with('id')
            ->willReturn(1);
        $shoppingList
            ->expects($this->once())
            ->method('trashed')
            ->willReturn(true);

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('getByIngredientUser')
            ->withAnyParameters()
            ->willReturn($shoppingList);
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('restore')
            ->withAnyParameters();
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('update')
            ->withAnyParameters();

        $result =$this->updateShoppingListService
                ->setUser(1)
                ->add([
                    'item_name' => 'Tomato',
                    'amount' => 100
            ]);

        $this->assertIsObject($result);
        $this->assertEquals($this->shoppingList->id, $result->id);
    }
}
