<?php

namespace Tests\Unit\Models;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unit_ingredient_relation_existence()
    {
        $this->assertTrue(method_exists(Ingredient::class, 'unit'));
    }

    /** @test */
    public function recipes_ingredient_relation_existence()
    {
        $this->assertTrue(method_exists(Ingredient::class, 'recipes'));
    }

    /** @test */
    public function category_ingredient_relation_existence()
    {
        $this->assertTrue(method_exists(Ingredient::class, 'category'));
    }

    /** @test */
    public function nutrients_ingredient_relation_existence()
    {
        $this->assertTrue(method_exists(Ingredient::class, 'nutrients'));
    }
}
