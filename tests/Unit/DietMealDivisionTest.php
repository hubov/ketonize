<?php

namespace Tests\Unit;

use App\Models\DietMealDivision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class DietMealDivisionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_tag_dietMealDivision_relation_existence()
    {
        $this->assertTrue(method_exists(DietMealDivision::class, 'tag'));
    }
}
