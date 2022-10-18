<?php

namespace Tests\Unit;

use App\Models\Nutrient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class NutrientTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_ingredients_nutrient_relation_existence()
    {
        $this->assertTrue(method_exists(Nutrient::class, 'ingredients'));
    }
}
