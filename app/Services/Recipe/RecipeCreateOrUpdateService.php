<?php

namespace App\Services\Recipe;

use App\Models\Recipe;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Services\Interfaces\ImageParserInterface;
use App\Services\Interfaces\Recipe\RecipeCreateOrUpdateInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;

class RecipeCreateOrUpdateService implements RecipeCreateOrUpdateInterface
{
    protected $recipeRepository;
    protected $ingredientRepository;
    protected $tagRepository;
    protected $relateIngredientsToRecipe;
    protected $imageParser;
    protected $attributes;
    protected $recipe;

    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
        IngredientRepositoryInterface $ingredientRepository,
        TagRepositoryInterface $tagRepository,
        RelateIngredientsToRecipeInterface $relateIngredientsToRecipe,
        ImageParserInterface $imageParser
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->tagRepository = $tagRepository;
        $this->relateIngredientsToRecipe = $relateIngredientsToRecipe;
        $this->imageParser = $imageParser;

        return $this;
    }

    public function perform(array $attributes, string $slug = NULL) : Recipe
    {
        $this->attributes = $attributes;

        $this->parseImage();

        if ($slug === NULL) {
            $this->create();
        } else {
            $this->update($slug);
        }

        $this->relateIngredientsAndTagsToRecipe();

        return $this->recipe;
    }

    protected function create() : Recipe
    {
        $this->recipe = $this->recipeRepository->create($this->attributes['recipe']);

        return $this->recipe;
    }

    protected function update(string $slug) : Recipe
    {
        $this->recipe = $this->recipeRepository->updateBySlug($slug, $this->attributes['recipe']);

        return $this->recipe;
    }

    protected function relateIngredientsToRecipe() : void
    {
        $relateIngredientsToRecipe = $this->relateIngredientsToRecipe->setRecipe($this->recipe);

        foreach ($this->attributes['ingredients'] as $ingredient) {
            $relateIngredientsToRecipe->addIngredient($ingredient['id'], $ingredient['quantity']);
        }
        $relateIngredientsToRecipe->sync();
    }

    protected function relateTagsToRecipe() : void
    {
        $this->recipeRepository->syncTags($this->recipe->id, $this->attributes['tags']);
    }

    protected function relateIngredientsAndTagsToRecipe() : void
    {
        $this->relateIngredientsToRecipe();
        $this->relateTagsToRecipe();
    }

    protected function parseImage(): void
    {
        if (isset($this->attributes['recipe']['image'])) {
            $imageName = $this->imageParser
                ->setFile($this->attributes['recipe']['image'])
                ->getName($this->attributes['recipe']['name']);

            $this->imageParser
                ->makeRecipeCover();

            $this->imageParser
                ->makeRecipeThumbnail();

            $this->imageParser
                ->keepOriginal();

            $this->attributes['recipe']['image'] = $imageName;
        }
    }
}
