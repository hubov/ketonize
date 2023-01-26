<?php

namespace Tests\Unit;

use App\Models\ShoppingList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ingredient_shoppingList_relation_existence()
    {
        $this->assertTrue(method_exists(ShoppingList::class, 'ingredient'));
    }

    /** @test */
    public function test_user_shoppingList_relation_existence()
    {
        $this->assertTrue(method_exists(ShoppingList::class, 'user'));
    }
}
