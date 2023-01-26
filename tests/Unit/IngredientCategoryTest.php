<?php

namespace Tests\Unit;

use App\Models\IngredientCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class IngredientCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ingredient_ingredient_category_relation_existence()
    {
        $this->assertTrue(method_exists(IngredientCategory::class, 'ingredient'));
    }
}
