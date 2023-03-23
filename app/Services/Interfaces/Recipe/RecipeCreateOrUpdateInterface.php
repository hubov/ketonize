<?php

namespace App\Services\Interfaces\Recipe;

use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Services\Interfaces\Image\ImageProcessorInterface;

interface RecipeCreateOrUpdateInterface
{
    public function __construct(
        RecipeRepositoryInterface $profileRepository,
        TagRepositoryInterface $tagRepository,
        RelateIngredientsToRecipeInterface $relateIngredientsToRecipe,
        ImageProcessorInterface $imageParser
    );
    public function perform(array $attributes, string $slug) : Recipe;
}
