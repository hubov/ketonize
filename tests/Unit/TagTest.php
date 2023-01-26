<?php

namespace Tests\Unit;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_recipes_tag_relation_existence()
    {
        $this->assertTrue(method_exists(Tag::class, 'recipes'));
    }
}
