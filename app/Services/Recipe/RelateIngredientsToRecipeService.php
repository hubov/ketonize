<?php

namespace App\Services\Recipe;

use App\Http\Traits\UniversalIngredientPicker;
use App\Models\Interfaces\IngredientModelInterface;
use App\Models\Interfaces\RecipeModelInterface;
use App\Repositories\Interfaces\CustomIngredientRepositoryInterface;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;
use Illuminate\Support\Str;

class RelateIngredientsToRecipeService implements RelateIngredientsToRecipeInterface
{
    use UniversalIngredientPicker;

    protected $ingredientRepository;
    protected $customIngredientRepository;
    protected $recipe;
    protected $userId;
    protected $ingredients = [];
    protected $customIngredients = [];

    public function __construct(
        IngredientRepositoryInterface $ingredientRepository,
        CustomIngredientRepositoryInterface $customIngredientRepository,
    ) {
        $this->ingredientRepository = $ingredientRepository;
        $this->customIngredientRepository = $customIngredientRepository;

        return $this;
    }

    public function setRecipe(RecipeModelInterface $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function setUser(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function addIngredient(int $ingredientId, int $amount): self
    {
        $this->appendIngredient($ingredientId, $amount);

        return $this;
    }

    public function addIngredientByName(string $ingredientName, int $amount, int $unitId): self
    {
        $ingredient = $this->getOrCreateIngredientByName([
            'name' => $ingredientName,
            'unit' => $unitId
        ]);

        $ingredientType = $this->getIngredientType($ingredient);
        $this->appendIngredient($ingredient->id, $amount, $ingredientType);

        return $this;
    }

    protected function appendIngredient(int $ingredientId, int $amount, string $ingredientType = 'ingredients'): void
    {
        if (isset($this->$ingredientType[$ingredientId])) {
            $this->$ingredientType[$ingredientId]['amount'] += $amount;
        } else {
            $this->$ingredientType[$ingredientId] = [
                'amount' => $amount
            ];
        }
    }

    protected function getIngredientType(IngredientModelInterface $ingredient): string
    {
        return Str::pluralStudly(
            Str::lcfirst(
                (new \ReflectionClass($ingredient))
                    ->getShortName()
            )
        );
    }

    public function sync() : void
    {
        $this->recipe->ingredients()->sync($this->ingredients);
        $this->recipe->customIngredients()->sync($this->customIngredients);
    }
}
