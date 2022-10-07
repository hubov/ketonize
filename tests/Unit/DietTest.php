<?php

namespace Tests\Unit;

use App\Models\Diet;
use PHPUnit\Framework\TestCase;

class DietTest extends TestCase
{
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
