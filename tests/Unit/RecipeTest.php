<?php

namespace Tests\Unit;

use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_ingredients_recipe_relation_existence()
    {
        $this->assertTrue(method_exists(Recipe::class, 'ingredients'));
    }

    public function test_tags_recipe_relation_existence()
    {
        $this->assertTrue(method_exists(Recipe::class, 'tags'));
    }

//    public function test_tagsIds_method_in_recipe()
//    {
//        $recipe = Recipe::factory()->has(Tag::factory()->count(3))->create();
//
//        $this->assertEquals(3, count($recipe->tagsIds()));
//    }
}
