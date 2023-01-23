<?php

namespace App\Services\Recipe;

use App\Models\Recipe;
use App\Models\UserDiet;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Services\Interfaces\Recipe\RecipeSearchInterface;
use App\Services\Interfaces\Recipe\SelectRecipeForDietInterface;

class SelectRecipeForDietService implements SelectRecipeForDietInterface
{
    const MIN_RECIPE_ATTRIBUTE_SCALE = 0.5;
    const MAX_RECIPE_ATTRIBUTE_SCALE = 1.5;
    protected $recipeRepository;
    protected $tags;
    protected $userDiet;
    protected $ignoreRecipeIds;
    protected $recipeAttributes;

    public function __construct(RecipeRepositoryInterface $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function setUserDiet(UserDiet $userDiet): self
    {
        $this->userDiet = $userDiet;

        return $this;
    }

    public function ignoreRecipes(array $ignoreRecipeIds): self
    {
        $this->ignoreRecipeIds = $ignoreRecipeIds;

        return $this;
    }

    public function get(): Recipe
    {
        return $this->recipeRepository->getOneByAttributesAndTagsWithoutIds(
            $this->setRecipeAttributes(),
            $this->tags,
            $this->ignoreRecipeIds
        );
    }

    protected function setRecipeAttributes()
    {
        return $this->recipeAttributes = [
            'protein_ratio' => [$this->userDiet->getProteinRatio() * self::MIN_RECIPE_ATTRIBUTE_SCALE, $this->userDiet->getProteinRatio() * self::MAX_RECIPE_ATTRIBUTE_SCALE],
            'carbohydrate_ratio' => [0, $this->userDiet->getCarbohydrateRatio() * self::MAX_RECIPE_ATTRIBUTE_SCALE]
        ];
    }
}
