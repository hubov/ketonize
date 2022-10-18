<?php

namespace Tests\Unit;

use App\Models\IngredientCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class IngredientCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_ingredient_ingredient_category_relation_existence()
    {
        $this->assertTrue(method_exists(IngredientCategory::class, 'ingredient'));
    }
}
