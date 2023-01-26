<?php

namespace Tests\Unit;

use App\Models\Diet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class DietTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_diet_relation_existence()
    {
        $this->assertTrue(method_exists(Diet::class, 'diet'));
    }
}
