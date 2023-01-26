<?php

namespace Tests\Unit\Models;

use App\Models\Nutrient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class NutrientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ingredients_nutrient_relation_existence()
    {
        $this->assertTrue(method_exists(Nutrient::class, 'ingredients'));
    }
}
