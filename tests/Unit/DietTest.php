<?php

namespace Tests\Unit;

use App\Models\Diet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class DietTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_diet_relation_existence()
    {
        $this->assertTrue(method_exists(Diet::class, 'diet'));
    }
}
