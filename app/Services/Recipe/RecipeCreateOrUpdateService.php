<?php

namespace App\Services\Recipe;

use App\Models\Recipe;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Services\Interfaces\Recipe\RecipeCreateOrUpdateInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;

class RecipeCreateOrUpdateService implements RecipeCreateOrUpdateInterface
{
    protected $recipeRepository;
    protected $ingredientRepository;
    protected $tagRepository;
    protected $relateIngredientsToRecipe;

    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
        IngredientRepositoryInterface $ingredientRepository,
        TagRepositoryInterface $tagRepository,
        RelateIngredientsToRecipeInterface $relateIngredientsToRecipe
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->tagRepository = $tagRepository;
        $this->relateIngredientsToRecipe = $relateIngredientsToRecipe;

        return $this;
    }

    public function perform(array $attributes, string $slug = NULL) : Recipe
    {
        $sortedAttributes = $this->sortAttributes($attributes);

        if ($slug === NULL) {
            return $this->create($sortedAttributes);
        } else {
            return $this->update($sortedAttributes, $slug);
        }
    }

    protected function sortAttributes($attributes)
    {
        return [
            'recipe' => [
                'name' => $attributes['name'],
                'image' => $attributes['image'],
                'description' => $attributes['description'],
                'preparation_time' => $attributes['preparation_time'],
                'cooking_time' => $attributes['cooking_time']
            ],
            'ingredients' => [
                'ids' => $attributes['ids'],
                'quantity' => $attributes['quantity']
            ],
            'tags' => $attributes['tags']
        ];
    }

    protected function create(array $sortedAttributes) : Recipe
    {
        $recipe = $this->recipeRepository->create($sortedAttributes['recipe']);

        $this->relateIngredientsToRecipe($recipe->id, $sortedAttributes['ingredients']['ids'], $sortedAttributes['ingredients']['quantity']);

        foreach ($sortedAttributes['tags'] as $tag) {
            $recipe->tags()->attach($tag);
        }

        $recipe->save();

        return $recipe;
    }

    protected function update(array $sortedAttributes, string $slug) : Recipe
    {
        $recipe = $this->recipeRepository->updateBySlug($slug, $sortedAttributes['recipe']);

        $this->relateIngredientsToRecipe($recipe->id, $sortedAttributes['ingredients']['ids'], $sortedAttributes['ingredients']['quantity']);

        $recipe->tags()->sync($sortedAttributes['tags']);

        return $recipe;
    }

    protected function relateIngredientsToRecipe(int $recipeId, array $ingredients, array $quantities)
    {
        $relateIngredientsToRecipe = $this->relateIngredientsToRecipe->setRecipe($recipeId);
        foreach ($ingredients as $key => $ingredientId) {
            $relateIngredientsToRecipe->addIngredient($ingredientId, $quantities[$key]);
        }
        $relateIngredientsToRecipe->sync();
    }
}
