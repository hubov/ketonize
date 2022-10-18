<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_unit_ingredient_relation_existence()
    {
        $this->assertTrue(method_exists(Ingredient::class, 'unit'));
    }

    public function test_recipes_ingredient_relation_existence()
    {
        $this->assertTrue(method_exists(Ingredient::class, 'recipes'));
    }

    public function test_category_ingredient_relation_existence()
    {
        $this->assertTrue(method_exists(Ingredient::class, 'category'));
    }

    public function test_nutrients_ingredient_relation_existence()
    {
        $this->assertTrue(method_exists(Ingredient::class, 'nutrients'));
    }
}
